<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $transactions = Transaction::where('user_id', $userId)
            ->with(['category', 'sourceAccount', 'destinationAccount'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $accounts = Account::where('user_id', $userId)->get();
        $categories = Category::where('user_id', $userId)->get();

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        DB::transaction(function () use ($validated) {
            Transaction::create($validated);

            if ($validated['type'] === 'Pengeluaran' && !empty($validated['source_account_id'])) {
                Account::where('id', $validated['source_account_id'])->decrement('current_balance', $validated['amount']);
            } elseif ($validated['type'] === 'Pemasukan' && !empty($validated['destination_account_id'])) {
                Account::where('id', $validated['destination_account_id'])->increment('current_balance', $validated['amount']);
            } elseif (in_array($validated['type'], ['Transfer Antar Rekening', 'Tarik Tunai', 'Setor Tunai'])) {
                if (!empty($validated['source_account_id'])) {
                    Account::where('id', $validated['source_account_id'])->decrement('current_balance', $validated['amount']);
                }
                if (!empty($validated['destination_account_id'])) {
                    Account::where('id', $validated['destination_account_id'])->increment('current_balance', $validated['amount']);
                }
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat!');
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($transaction) {
            if ($transaction->type === 'Pengeluaran' && $transaction->source_account_id) {
                Account::where('id', $transaction->source_account_id)->increment('current_balance', $transaction->amount);
            } elseif ($transaction->type === 'Pemasukan' && $transaction->destination_account_id) {
                Account::where('id', $transaction->destination_account_id)->decrement('current_balance', $transaction->amount);
            } elseif (in_array($transaction->type, ['Transfer Antar Rekening', 'Tarik Tunai', 'Setor Tunai'])) {
                if ($transaction->source_account_id) {
                    Account::where('id', $transaction->source_account_id)->increment('current_balance', $transaction->amount);
                }
                if ($transaction->destination_account_id) {
                    Account::where('id', $transaction->destination_account_id)->decrement('current_balance', $transaction->amount);
                }
            }

            $transaction->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus dan saldo kas diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)->get();
        $totalBalance = $accounts->sum('current_balance');

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'Pemasukan')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'Pengeluaran')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $recentTransactions = Transaction::where('user_id', $userId)
            ->with(['category', 'sourceAccount', 'destinationAccount'])
            ->latest('date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', compact('accounts', 'totalBalance', 'monthlyIncome', 'monthlyExpense', 'recentTransactions'));
    }
}

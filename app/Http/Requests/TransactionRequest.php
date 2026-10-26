<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'date'                   => 'required|date',
            'type'                   => 'required|in:Pemasukan,Pengeluaran,Transfer Antar Rekening,Tarik Tunai,Setor Tunai',
            'amount'                 => 'required|numeric|min:500',
            'category_id'            => 'nullable|exists:categories,id',
            'source_account_id'      => 'nullable|exists:accounts,id',
            'destination_account_id' => 'nullable|exists:accounts,id|different:source_account_id',
            'description'            => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'                   => 'Tanggal transaksi wajib diisi.',
            'type.required'                   => 'Pilih jenis transaksi.',
            'amount.required'                 => 'Nominal transaksi wajib diisi.',
            'amount.min'                      => 'Nominal transaksi minimal Rp 500.',
            'destination_account_id.different' => 'Rekening tujuan tidak boleh sama dengan rekening asal.',
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'ticket.event'])
            ->orderByDesc('id_transaksi')
            ->get();

        $allTransactions = Transaction::count();

        $totalTransactions = Transaction::where('status_pembayaran', 'success')
            ->count();

        $totalRevenue = Transaction::where('status_pembayaran', 'success')
            ->sum('total_harga');

        return view('admin.transaction.index', compact(
            'transactions',
            'allTransactions',
            'totalTransactions',
            'totalRevenue'
        ));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['ticket.event.artist'])
            ->where('id_user', $request->user()->id)
            ->where('status_pembayaran', 'success')
            ->orderByDesc('id_transaksi')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tiket' => 'required|exists:tickets,id',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
            'service_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request, $validated) {
                $ticket = Ticket::whereKey($validated['id_tiket'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $serviceFee = (float) ($validated['service_fee'] ?? 0);
                $total = ($ticket->price * $validated['jumlah']) + $serviceFee;

                $transaction = Transaction::create([
                    'id_user' => $request->user()->id,
                    'id_tiket' => $ticket->id,
                    'jumlah' => $validated['jumlah'],
                    'total_harga' => $total,
                    'tanggal_transaksi' => now(),
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'pending',
                    'payment_reference' => 'TRX-' . Str::upper(Str::random(12)),
                ]);

                return $transaction->load(['ticket.event.artist']);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    public function pay(Request $request, Transaction $transaction)
    {
        if ($transaction->id_user !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        try {
            $transaction = DB::transaction(function () use ($transaction) {
                $transaction = Transaction::whereKey($transaction->id_transaksi)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaction->status_pembayaran === 'success') {
                    return $transaction->load(['ticket.event.artist']);
                }

                $ticket = Ticket::whereKey($transaction->id_tiket)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($ticket->stock < $transaction->jumlah) {
                    abort(400, 'Stock tidak cukup');
                }

                $ticket->decrement('stock', $transaction->jumlah);

                $transaction->update([
                    'status_pembayaran' => 'success',
                ]);

                return $transaction->load(['ticket.event.artist']);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }
}

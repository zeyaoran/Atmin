<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tickets = Ticket::with('event')->get();

        $paymentMethods = ['QRIS', 'Transfer Bank', 'E-Wallet', 'Credit Card'];

        $months = [
            1 => 800,
            2 => 850,
            3 => 950,
            4 => 1100,
            5 => 1300, 
        ];

        foreach ($months as $month => $total) {
            for ($i = 0; $i < $total; $i++) {

                $ticket = $tickets->random();
                $event = $ticket->event; // biar sinkron

                $user = $users->random();
                $qty = rand(1, 5);

                $totalHarga = $ticket->price * $qty;

                Transaction::create([
                    'id_user' => $user->id,
                    'id_tiket' => $ticket->id,
                    'jumlah' => $qty,
                    'total_harga' => $totalHarga,

                    'tanggal_transaksi' => Carbon::create(
                        2026,
                        $month,
                        rand(1, 28),
                        rand(0, 23),
                        rand(0, 59),
                        rand(0, 59)
                    ),

                    'metode_pembayaran' => $paymentMethods[array_rand($paymentMethods)],
                    'status_pembayaran' => 'success',
                    'payment_reference' => 'TRX-' . uniqid(),
                ]);
            }
        }
    }
}
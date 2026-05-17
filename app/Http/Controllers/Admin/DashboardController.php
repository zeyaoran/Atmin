<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $base = Transaction::where('status_pembayaran', 'success');

        /*
        |--------------------------------------------
        | TOTALS
        |--------------------------------------------
        */
        $totalTransactions = (clone $base)->count();
        $totalRevenue = (clone $base)->sum('total_harga');

        /*
        |--------------------------------------------
        | MONTHLY
        |--------------------------------------------
        */
        $monthly = (clone $base)
            ->select(
                DB::raw('MONTH(tanggal_transaksi) as month'),
                DB::raw('SUM(total_harga) as revenue'),
                DB::raw('SUM(jumlah) as tickets')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $salesByMonth = collect(range(1, 12))->map(fn ($m) => [
            'label' => Carbon::create()->month($m)->format('F'),
            'revenue' => (float) ($monthly[$m]->revenue ?? 0),
            'tickets_sold' => (int) ($monthly[$m]->tickets ?? 0),
        ]);

        /*
        |--------------------------------------------
        | ARTIST
        |--------------------------------------------
        */
        $artistRaw = (clone $base)
            ->join('tickets', 'transactions.id_tiket', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->join('artists', 'events.artist_id', '=', 'artists.id')
            ->select(
                'artists.name',
                DB::raw('SUM(transactions.jumlah) as total')
            )
            ->groupBy('artists.name')
            ->orderByDesc('total')
            ->get();

        $artistLabels = $artistRaw->pluck('name');
        $artistData = $artistRaw->pluck('total')->map(fn ($v) => (int) $v)->values();

        /*
        |--------------------------------------------
        | LOCATION
        |--------------------------------------------
        */
        $salesByLocation = Transaction::where('status_pembayaran', 'success')
            ->join('tickets', 'transactions.id_tiket', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->select(
                DB::raw("COALESCE(events.country, 'Unknown') as location"),
                DB::raw('SUM(transactions.jumlah) as total')
            )
            ->groupBy(DB::raw("COALESCE(events.country, 'Unknown')"))
            ->orderByDesc('total')
            ->get();

        $locationLabels = $salesByLocation->pluck('location')->values();
        $locationData = $salesByLocation->pluck('total')->map(fn ($v) => (int) $v)->values();

        /*
        |--------------------------------------------
        | PAYMENT
        |--------------------------------------------
        */
        $paymentMethods = (clone $base)
            ->select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('metode_pembayaran')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------
        | LATEST
        |--------------------------------------------
        */
        $latestTransactions = Transaction::with(['user', 'ticket.event'])
            ->orderByDesc('id_transaksi')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------
        | RETURN VIEW
        |--------------------------------------------
        */
        return view('admin.dashboard', [

            // STATS
            'artists' => Artist::count(),
            'events' => Event::count(),
            'tickets' => Ticket::count(),
            'users' => User::count(),
            'transactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,

            // MONTH
            'salesMonthLabels' => $salesByMonth->pluck('label'),
            'salesMonthRevenue' => $salesByMonth->pluck('revenue'),
            'salesMonthQty' => $salesByMonth->pluck('tickets_sold'),

            // ARTIST
            'artistLabels' => $artistLabels,
            'artistData' => $artistData,

            // LOCATION
            'locationLabels' => $locationLabels,
            'locationData' => $locationData,

            // PAYMENT
            'paymentLabels' => $paymentMethods->pluck('metode_pembayaran'),
            'paymentData' => $paymentMethods->pluck('total'),

            // LATEST
            'latestTransactions' => $latestTransactions,
        ]);
    }
}

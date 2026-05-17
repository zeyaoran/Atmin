@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    {{-- HEADER --}}
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">
                    Transactions
                </p>

                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">
                    Ticketing transaction history
                </h1>

                <p class="mt-1.5 max-w-xl text-[13px] text-slate-400">
                    Monitor payments, ticket quantities, and transaction methods
                    in one compact view.
                </p>
            </div>
        </div>
    </div>

    {{-- METRICS --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        {{-- TOTAL SEMUA --}}
        <div class="glass metric-card">
            <p class="text-sm text-slate-400">Total Transactions</p>
            <p class="metric-value">
                {{ $allTransactions }}
            </p>
        </div>

        {{-- SUCCESS --}}
        <div class="glass metric-card">
            <p class="text-sm text-slate-400">Success</p>
            <p class="metric-value">
                {{ $totalTransactions }}
            </p>
        </div>

        {{-- REVENUE --}}
        <div class="glass metric-card">
            <p class="text-sm text-slate-400">Total Revenue</p>
            <p class="metric-value accent-text">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="overflow-x-auto">

                <table class="min-w-[950px] w-full text-sm">

                    <thead class="bg-white/5 text-left text-slate-300">

                        <tr>

                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Payment</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-white/10">

                        @forelse($transactions as $trx)

                            <tr class="bg-white/[0.03]">

                                {{-- ID --}}
                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $trx->id_transaksi }}
                                </td>

                                {{-- TANGGAL --}}
                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ optional($trx->tanggal_transaksi)->format('d M Y, H:i') ?: '-' }}
                                </td>

                                {{-- USER --}}
                                <td class="px-4 py-3.5 font-medium text-white">
                                    {{ optional($trx->user)->name ?? '-' }}
                                </td>

                                {{-- EVENT --}}
                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ optional(optional($trx->ticket)->event)->title ?? '-' }}
                                </td>

                                {{-- QTY --}}
                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $trx->jumlah }}
                                </td>

                                {{-- TOTAL --}}
                                <td class="px-4 py-3.5 font-semibold text-fuchsia-100">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3.5">

                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $trx->status_pembayaran == 'success'
                                            ? 'bg-emerald-400/10 text-emerald-200'
                                            : ($trx->status_pembayaran == 'pending'
                                                ? 'bg-amber-400/10 text-amber-200'
                                                : 'bg-pink-400/10 text-pink-100') }}">

                                        {{ ucfirst($trx->status_pembayaran) }}

                                    </span>

                                </td>

                                {{-- PAYMENT --}}
                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $trx->metode_pembayaran }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-slate-400">
                                    No transactions yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>
    </div>

</section>
@endsection

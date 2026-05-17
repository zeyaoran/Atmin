@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    {{-- HEADER --}}
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Dashboard</p>

                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">
                    Ticketing system summary
                </h1>

                <p class="mt-1.5 max-w-2xl text-[13px] text-slate-400">
                    Monitor artists, events, tickets, users, transactions, and revenue in one page.
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                <p class="text-[10px] uppercase tracking-[0.25em] text-slate-500">Date</p>
                <p class="mt-1 text-sm font-medium text-white">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    @php
        $cards = [
            ['label' => 'Artists', 'value' => $artists, 'icon' => 'ri-mic-line'],
            ['label' => 'Events', 'value' => $events, 'icon' => 'ri-calendar-event-line'],
            ['label' => 'Tickets', 'value' => $tickets, 'icon' => 'ri-ticket-2-line'],
            ['label' => 'Users', 'value' => $users, 'icon' => 'ri-team-line'],
            ['label' => 'Transactions', 'value' => $transactions, 'icon' => 'ri-bank-card-line'],
            ['label' => 'Revenue', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'icon' => 'ri-line-chart-line'],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($cards as $card)
            <div class="glass metric-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                        <p class="metric-value {{ $card['label'] === 'Revenue' ? 'accent-text' : '' }}">
                            {{ $card['value'] }}
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-lg text-fuchsia-100">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- CHART --}}
    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                <h2 class="text-sm font-semibold text-white">Revenue</h2>
                <p class="text-xs text-slate-400">Monthly analytics</p>
            </div>

            <div class="p-4">
                <div id="salesChart" class="h-[280px]"></div>
            </div>
        </div>
    </div>

    {{-- SMALL CHARTS --}}
    <div class="grid gap-5 lg:grid-cols-3">
        <div class="glass rounded-[24px] p-4 lg:p-5">
            <div class="overflow-hidden rounded-[22px] border border-white/10">
                <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white">Artist</h3>
                </div>
                <div class="p-4">
                    <div id="artistChart" class="h-[220px]"></div>
                </div>
            </div>
        </div>

        <div class="glass rounded-[24px] p-4 lg:p-5">
            <div class="overflow-hidden rounded-[22px] border border-white/10">
                <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white">Locations</h3>
                </div>
                <div class="p-4">
                    <div id="locationChart" class="h-[220px]"></div>
                </div>
            </div>
        </div>

        <div class="glass rounded-[24px] p-4 lg:p-5">
            <div class="overflow-hidden rounded-[22px] border border-white/10">
                <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white">Payment</h3>
                </div>
                <div class="p-4">
                    <div id="paymentChart" class="h-[220px]"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                <h2 class="text-sm font-semibold text-white">Transactions</h2>
                <p class="text-xs text-slate-400">Latest data</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[800px] w-full text-sm">
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Buyer</th>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($latestTransactions as $trx)
                            <tr class="bg-white/[0.03]">
                                <td class="px-4 py-3.5 text-slate-300">#{{ $trx->id_transaksi }}</td>
                                <td class="px-4 py-3.5 font-medium text-white">{{ optional($trx->user)->name ?: '-' }}</td>
                                <td class="px-4 py-3.5 text-slate-300">{{ optional(optional($trx->ticket)->event)->title ?: '-' }}</td>
                                <td class="px-4 py-3.5 text-slate-300">{{ $trx->jumlah }}</td>
                                <td class="px-4 py-3.5 text-slate-300">{{ $trx->metode_pembayaran }}</td>
                                <td class="px-4 py-3.5 font-semibold text-fuchsia-100">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-400">
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

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
const chartBase = {
    chart: {
        toolbar: { show: false },
        foreColor: "#94a3b8",
        background: "transparent",
        zoom: { enabled: false }
    },
    grid: {
        borderColor: "rgba(255,255,255,0.06)",
        strokeDashArray: 5
    },
    tooltip: {
        theme: "dark",
        fillSeriesColor: false
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        labels: {
            colors: "#cbd5e1"
        }
    }
};

new ApexCharts(document.querySelector("#salesChart"), {
    ...chartBase,
    chart: {
        type: "area",
        height: 280,
        ...chartBase.chart
    },
    series: [
        {
            name: "Revenue",
            data: {!! json_encode($salesMonthRevenue) !!}
        },
        {
            name: "Tickets",
            data: {!! json_encode($salesMonthQty) !!}
        }
    ],
    colors: ["#a855f7", "#ec4899"],
    stroke: {
        curve: "smooth",
        width: 3
    },
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.32,
            opacityTo: 0.02
        }
    },
    xaxis: {
        categories: {!! json_encode($salesMonthLabels) !!},
        axisBorder: { color: "rgba(255,255,255,0.08)" },
        axisTicks: { color: "rgba(255,255,255,0.08)" }
    },
    yaxis: {
        min: 0,
        tickAmount: 5,
        labels: {
            formatter: function (val) {
                return Math.round(val);
            }
        }
    }
}).render();

new ApexCharts(document.querySelector("#artistChart"), {
    ...chartBase,
    chart: {
        type: "bar",
        height: 220,
        ...chartBase.chart
    },
    series: [{
        data: {!! json_encode($artistData) !!}
    }],
    xaxis: {
        categories: {!! json_encode($artistLabels) !!}
    },
    plotOptions: {
        bar: {
            borderRadius: 8,
            columnWidth: "45%"
        }
    },
    colors: ["#a855f7"]
}).render();

new ApexCharts(document.querySelector("#locationChart"), {
    ...chartBase,
    chart: {
        type: "donut",
        height: 220,
        ...chartBase.chart
    },
    series: {!! json_encode($locationData ?? []) !!},
    labels: {!! json_encode($locationLabels ?? []) !!},
    colors: ["#a855f7", "#ec4899", "#818cf8", "#f59e0b", "#38bdf8"],
    stroke: {
        width: 0
    },
    legend: {
        position: "bottom",
        labels: {
            colors: "#cbd5e1"
        }
    }
}).render();

new ApexCharts(document.querySelector("#paymentChart"), {
    ...chartBase,
    chart: {
        type: "donut",
        height: 220,
        ...chartBase.chart
    },
    series: {!! json_encode($paymentData) !!},
    labels: {!! json_encode($paymentLabels) !!},
    colors: ["#a855f7", "#ec4899", "#818cf8", "#f59e0b"],
    stroke: {
        width: 0
    },
    legend: {
        position: "bottom",
        labels: {
            colors: "#cbd5e1"
        }
    }
}).render();
</script>
@endsection

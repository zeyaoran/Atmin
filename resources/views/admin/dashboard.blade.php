@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    {{-- HEADER --}}
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Dashboard</p>
                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold text-white">
                    Ticketing system summary
                </h1>
                <p class="mt-1 max-w-2xl text-[13px] text-slate-400">
                    Monitor artists, events, tickets, users, transactions, and revenue in one page.
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 shrink-0 self-start sm:self-center">
                <p class="text-[10px] uppercase tracking-[0.25em] text-slate-500">Date</p>
                <p class="mt-1 text-sm font-medium text-white">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    @php
        $cards = [
            ['label' => 'Artists', 'value' => $artists ?? 0, 'icon' => 'ri-mic-line'],
            ['label' => 'Events', 'value' => $events ?? 0, 'icon' => 'ri-calendar-event-line'],
            ['label' => 'Tickets', 'value' => $tickets ?? 0, 'icon' => 'ri-ticket-2-line'],
            ['label' => 'Users', 'value' => $users ?? 0, 'icon' => 'ri-team-line'],
            ['label' => 'Transactions', 'value' => $transactions ?? 0, 'icon' => 'ri-bank-card-line'],
            ['label' => 'Revenue', 'value' => 'Rp ' . number_format($totalRevenue ?? 0, 0, ',', '.'), 'icon' => 'ri-line-chart-line'],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($cards as $card)
            <div class="glass metric-card p-5 rounded-[22px] border border-white/5 bg-white/[0.02]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                        <p class="metric-value mt-2 text-2xl font-bold text-white {{ $card['label'] === 'Revenue' ? 'accent-text text-fuchsia-400' : '' }}">
                            {{ $card['value'] }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-xl text-fuchsia-300">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- CHART UTAMA: REVENUE --}}
    <div class="glass rounded-[24px] p-5 border border-white/5 bg-white/[0.02]">
        <div class="overflow-hidden rounded-[18px] border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3.5">
                <h2 class="text-sm font-semibold text-white">Revenue Analytics</h2>
                <p class="text-xs text-slate-400">Monthly overview of revenue and tickets sold</p>
            </div>
            <div class="p-4 bg-white/[0.01]">
                <div id="salesChart" class="h-[300px]"></div>
            </div>
        </div>
    </div>

    {{-- GRID LAYOUT: ARTIST HORIZONTAL & DONUTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ARTIST CHART (Horizontal Bar Chart) --}}
<div class="glass rounded-[24px] p-5 border border-white/5 bg-white/[0.02] lg:col-span-2">
    <div class="overflow-hidden rounded-[18px] border border-white/10 h-full flex flex-col">

        <div class="border-b border-white/10 bg-white/5 px-4 py-3.5">
            <h3 class="text-sm font-semibold text-white">Artist Performance</h3>
            <p class="text-xs text-slate-400">Ticket distribution across all listed artists</p>
        </div>

        <div class="p-4 bg-white/[0.01] flex-1 flex items-center justify-center">

            @php
                $artistHasData = isset($artistData) && array_sum($artistData) > 0;
            @endphp

            @if(!$artistHasData)
                <div class="text-center text-slate-400">
                    <p class="text-sm">No artist data available</p>
                    <p class="text-xs mt-1">Chart will appear when data is added</p>
                </div>
            @else
                <div id="artistChart" class="w-full"></div>
            @endif

        </div>
    </div>
</div>

        {{-- KELOMPOK DONUT CHARTS --}}
        <div class="space-y-6">

            {{-- LOCATIONS --}}
            <div class="glass rounded-[24px] p-5 border border-white/5 bg-white/[0.02]">
                <div class="overflow-hidden rounded-[18px] border border-white/10">
                    <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                        <h3 class="text-sm font-semibold text-white">Top Locations</h3>
                    </div>
                    <div class="p-4 bg-white/[0.01]">
                        <div id="locationChart" class="h-[210px]"></div>
                    </div>
                </div>
            </div>

            {{-- PAYMENT --}}
            <div class="glass rounded-[24px] p-5 border border-white/5 bg-white/[0.02]">
                <div class="overflow-hidden rounded-[18px] border border-white/10">
                    <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                        <h3 class="text-sm font-semibold text-white">Payment Methods</h3>
                    </div>
                    <div class="p-4 bg-white/[0.01]">
                        <div id="paymentChart" class="h-[210px]"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="glass rounded-[24px] p-5 border border-white/5 bg-white/[0.02]">
        <div class="overflow-hidden rounded-[18px] border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3.5">
                <h2 class="text-sm font-semibold text-white">Recent Transactions</h2>
                <p class="text-xs text-slate-400">Latest user payment status</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[800px] w-full text-sm">
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold">ID</th>
                            <th class="px-5 py-3.5 font-semibold">Buyer</th>
                            <th class="px-5 py-3.5 font-semibold">Event</th>
                            <th class="px-5 py-3.5 font-semibold text-center">Qty</th>
                            <th class="px-5 py-3.5 font-semibold">Payment</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($latestTransactions ?? [] as $trx)
                            <tr class="bg-white/[0.01] hover:bg-white/[0.04] transition-colors">
                                <td class="px-5 py-4 text-slate-400 font-mono">#{{ $trx->id_transaksi }}</td>
                                <td class="px-5 py-4 font-medium text-white">{{ optional($trx->user)->name ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-300">{{ optional(optional($trx->ticket)->event)->title ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-300 text-center font-semibold">{{ $trx->jumlah }}</td>
                                <td class="px-5 py-4 text-slate-300">
                                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10 text-xs font-medium">
                                        {{ $trx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-semibold text-fuchsia-300 text-right">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">
                                    No transactions recorded yet.
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
        strokeDashArray: 4
    },
    tooltip: {
        theme: "dark",
        fillSeriesColor: false
    },
    dataLabels: { enabled: false },
    legend: { labels: { colors: "#cbd5e1" } }
};

// ── REVENUE CHART ──────────────────────────────────────────
const salesChartEl = document.querySelector("#salesChart");
if (salesChartEl) {
    new ApexCharts(salesChartEl, {
        ...chartBase,
        chart: { type: "area", height: 300, ...chartBase.chart },
        series: [
            { name: "Revenue", data: {!! json_encode($salesMonthRevenue ?? []) !!} },
            { name: "Tickets",  data: {!! json_encode($salesMonthQty ?? []) !!} }
        ],
        colors: ["#c084fc", "#f472b6"],
        stroke: { curve: "smooth", width: 3 },
        fill: {
            type: "gradient",
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.01 }
        },
        xaxis: {
            categories: {!! json_encode($salesMonthLabels ?? []) !!},
            axisBorder: { color: "rgba(255,255,255,0.08)" },
            axisTicks:  { color: "rgba(255,255,255,0.08)" }
        },
        yaxis: {
            min: 0,
            tickAmount: 5,
            labels: { formatter: val => Math.round(val) }
        }
    }).render();
}

// ── ARTIST CHART ─────────────────────────────
const artistEl = document.querySelector("#artistChart");

if (artistEl) {

    const artistRaw = {!! json_encode($artistData ?? []) !!};
    const artistLabels = {!! json_encode($artistLabels ?? []) !!};

    const total = artistRaw.reduce((a, b) => a + b, 0);

    if (total > 0) {

        const pairs = artistLabels.map((label, i) => ({
            label,
            value: artistRaw[i] ?? 0
        }));

        const dynamicHeight = Math.min(
            Math.max(340, pairs.length * 45),
            900 // ⬅️ biar gak kepanjangan banget
        );

        new ApexCharts(artistEl, {
            ...chartBase,

            chart: {
                type: "bar",
                height: dynamicHeight,
                ...chartBase.chart
            },

            series: [{
                name: "Tickets",
                data: pairs.map(p => p.value)
            }],

            xaxis: {
                categories: pairs.map(p => p.label),
                axisBorder: { color: "rgba(255,255,255,0.08)" },
                labels: {
                    style: { colors: "#94a3b8" }
                }
            },

            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: "60%",
                    distributed: true
                }
            },

            dataLabels: {
                enabled: true,
                offsetX: 8,
                style: {
                    colors: ["#fff"],
                    fontSize: "11px"
                },
                formatter: function (val, opt) {
                    return pairs[opt.dataPointIndex].value;
                }
            },

            colors: [
                "#a855f7","#ec4899","#818cf8","#f59e0b",
                "#38bdf8","#34d399","#fb923c","#f43f5e"
            ],

            legend: { show: false },

            tooltip: {
                theme: "dark",
                y: {
                    formatter: (val, { dataPointIndex }) =>
                        pairs[dataPointIndex].value + " tickets"
                }
            }
        }).render();
    }
}

// ── LOCATION CHART ─────────────────────────────────────────
const locationChartEl = document.querySelector("#locationChart");
if (locationChartEl) {
    const locationRawData   = {!! json_encode($locationData ?? []) !!};
    const locationRawLabels = {!! json_encode($locationLabels ?? []) !!};

    const safeLocData = Array.isArray(locationRawData) ? locationRawData : [];
    const safeLocLabels = Array.isArray(locationRawLabels) ? locationRawLabels : [];

    const locPairs = safeLocLabels
        .map((l, i) => ({ label: l, val: safeLocData[i] || 0 }))
        .filter(p => p.val > 0);

    if (locPairs.length > 0) {
        const locTotal  = locPairs.reduce((a, p) => a + p.val, 0);
        const locScaled = locPairs.map(p => Math.max(p.val, Math.ceil(locTotal * 0.05)));

        new ApexCharts(locationChartEl, {
            ...chartBase,
            chart: { type: "donut", height: 210, ...chartBase.chart },
            series: locScaled,
            labels: locPairs.map(p => p.label),
            colors: ["#a855f7", "#ec4899", "#818cf8", "#f59e0b", "#38bdf8"],
            stroke: { width: 0 },
            plotOptions: { pie: { donut: { size: "70%" }, expandOnClick: false } },
            tooltip: {
                theme: "dark",
                y: { formatter: (val, { seriesIndex }) => locPairs[seriesIndex].val + ' tickets' }
            },
            legend: { position: "right", labels: { colors: "#cbd5e1" } }
        }).render();
    }
}

// ── PAYMENT CHART ──────────────────────────────────────────
const paymentChartEl = document.querySelector("#paymentChart");
if (paymentChartEl) {
    const paymentRawData   = {!! json_encode($paymentData ?? []) !!};
    const paymentRawLabels = {!! json_encode($paymentLabels ?? []) !!};

    const safePayData = Array.isArray(paymentRawData) ? paymentRawData : [];
    const safePayLabels = Array.isArray(paymentRawLabels) ? paymentRawLabels : [];

    const payPairs = safePayLabels
        .map((l, i) => ({ label: l, val: safePayData[i] || 0 }))
        .filter(p => p.val > 0);

    if (payPairs.length > 0) {
        const payTotal  = payPairs.reduce((a, p) => a + p.val, 0);
        const payScaled = payPairs.map(p => Math.max(p.val, Math.ceil(payTotal * 0.05)));

        new ApexCharts(paymentChartEl, {
            ...chartBase,
            chart: { type: "donut", height: 210, ...chartBase.chart },
            series: payScaled,
            labels: payPairs.map(p => p.label),
            colors: ["#a855f7", "#ec4899", "#818cf8", "#f59e0b", "#6366f1"],
            stroke: { width: 0 },
            plotOptions: { pie: { donut: { size: "70%" }, expandOnClick: false } },
            tooltip: {
                theme: "dark",
                y: { formatter: (val, { seriesIndex }) => payPairs[seriesIndex].val + ' transaksi' }
            },
            legend: { position: "right", labels: { colors: "#cbd5e1" } }
        }).render();
    }
}
</script>
@endsection
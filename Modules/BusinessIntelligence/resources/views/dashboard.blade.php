@extends('layouts.app')

@section('content')
    <div id="dashboard-view" class="tab-content active-tab" style="display:block;">
        <div class="subheader-bar">
            <div class="subheader-title">
                <h3>Executive Dashboard</h3>
                <p>Real-time overview of business performance, sales forecast, and operational efficiency.</p>
            </div>
            <div class="subheader-controls">
                <div class="control-date-selector">
                    <i data-lucide="calendar" class="control-icon-sm"></i>
                    {{ now()->format('M d') }} - {{ now()->addDays(7)->format('M d, Y') }}
                </div>
                <button id="syncNowBtn" class="control-btn" data-tip="Sync Data" onclick="syncAllDepartments()">
                    <i data-lucide="refresh-cw" class="control-icon"></i>
                </button>
            </div>
        </div>
        <div class="content-container">
            <section class="kpi-grid">
                @foreach($kpis as $kpi)
                    <div class="kpi-card">
                        <div class="kpi-icon-container"><i data-lucide="{{ $kpi['icon'] }}" class="kpi-icon"></i></div>
                        <div class="kpi-details">
                            <div class="kpi-label">{{ $kpi['label'] }}</div>
                            <div class="kpi-value">{{ $kpi['value'] }}</div>
                            <div class="kpi-change {{ $kpi['change_class'] }}">{{ $kpi['change'] }}</div>
                        </div>
                    </div>
                @endforeach
            </section>

            <div class="dashboard-layout-grid">
                <div class="section-column">
                    {{-- Historical Sales Trend --}}
                    <div class="ui-card" style="flex: none;">
                        <div class="card-header">
                            <div class="card-title">Historical Sales Trend <span class="info-dot"
                                    data-tooltip="Tracks sales performance over the selected time period.">i</span></div>
                            <select id="salesRange" class="control-date-selector chart-range-select"
                                onchange="changeSalesRange()">
                                <option value="7d">7 Days</option>
                                <option value="1m">1 Month</option>
                                <option value="1y">1 Year</option>
                            </select>
                        </div>
                        <div class="placeholder-graph-box chart-box"><canvas id="salesTrendChart"></canvas></div>
                        <div class="forecast-sub-row">
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="rotate-cw" class="sub-icon"></i>Repeat Purchase
                                    Rate</div>
                                <div class="sub-box-val" id="subRepeatRate">0%</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subRepeatChange">↑ 0%</div>
                            </div>
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="star" class="sub-icon"></i>High Demand Products
                                </div>
                                <div class="sub-box-val" id="subHighDemand">0</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subDemandChange">↑ 0</div>
                            </div>
                            <div class="sub-box">
                                <div class="sub-box-label"><i data-lucide="trending-up" class="sub-icon"></i>Revenue Growth
                                </div>
                                <div class="sub-box-val" id="subRevenueGrowth">0%</div>
                                <div class="kpi-change change-up" style="font-size:0.75rem;" id="subGrowthChange">↑ 0%</div>
                            </div>
                        </div>
                    </div>

                    {{-- Products Driving Growth --}}
                    <div class="ui-card fixed-target-height-card" style="flex: 1;">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Products Driving Growth <span class="info-dot"
                                        data-tooltip="Top selling products ranked by units sold this month with inventory coverage and revenue data.">i</span>
                                </div>
                                <p style="font-size: 11px; color: var(--slate-500); margin-top: 2px;">Top 10 Products</p>
                            </div>
                        </div>
                        <div class="scrollable-card-body">
                            <table class="product-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Units Sold</th>
                                        <th>vs Last 30 Days</th>
                                        <th>Inventory Coverage</th>
                                        <th>Stock Status</th>
                                        <th class="text-right">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $product)
                                        @php
                                            $change = $product['prev_units'] > 0 ? round((($product['units_sold'] - $product['prev_units']) / $product['prev_units']) * 100) : 0;
                                            $coverageClass = $product['coverage'] >= 80 ? 'health-green' : ($product['coverage'] >= 60 ? 'health-yellow' : ($product['coverage'] >= 40 ? 'health-orange' : 'health-red'));
                                            $stockDays = round($product['coverage'] * 0.365);
                                            $stockTooltip = match ($product['stock_status']) {
                                                'Low Stock' => 'Below reorder threshold. Restock urgently.',
                                                'Adequate' => 'Stock is sufficient but monitor closely.',
                                                default => 'Stock levels are healthy.'
                                            };
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $index + 1 }}.</strong> {{ $product['name'] }}</td>
                                            <td>{{ number_format($product['units_sold']) }}</td>
                                            <td>
                                                <span class="{{ $change >= 0 ? 'change-up' : 'change-down' }}"
                                                    data-tip="{{ number_format($product['prev_units']) }} units last month → {{ number_format($product['units_sold']) }} units this month">
                                                    {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <div class="coverage-cell" data-tip="{{ $stockDays }} days of stock remaining">
                                                    <span class="coverage-text">{{ $product['coverage'] }}%</span>
                                                    <div class="coverage-bar">
                                                        <div class="coverage-fill {{ $coverageClass }}"
                                                            style="width: {{ $product['coverage'] }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge {{ $product['stock_class'] }}"
                                                    data-tip="{{ $stockTooltip }}">{{ $product['stock_status'] }}</span></td>
                                            <td class="text-right">₱{{ number_format($product['revenue']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center;color:var(--slate-500);padding:2rem;">No
                                                product data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="section-column">
                    {{-- Operational Efficiency --}}
                    <div class="ui-card" style="flex: none;">
                        <div class="card-header">
                            <div class="card-title">Operational Efficiency <span class="info-dot"
                                    data-tooltip="Comprehensive overview of operational health, manufacturing, and fulfillment performance.">i</span>
                            </div>
                        </div>
                        <div class="op-health-row">
                            <div class="op-health-card">
                                <div class="op-donut op-donut-lg">
                                    <svg viewBox="0 0 36 36" class="op-donut-svg">
                                        <path class="op-donut-track"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="op-donut-fill {{ $operationalEfficiency['overall']['class'] }}"
                                            stroke-dasharray="{{ $operationalEfficiency['overall']['percent'] }}, 100"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <span
                                        class="op-donut-text op-donut-text-lg">{{ $operationalEfficiency['overall']['percent'] }}%</span>
                                </div>
                                <div class="op-health-info">
                                    <h4>Overall Efficiency</h4>
                                    <div class="op-health-badge {{ $operationalEfficiency['overall']['class'] }}">
                                        {{ $operationalEfficiency['overall']['status'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="op-summary-card {{ $operationalEfficiency['overall']['class'] }}">
                                <div class="op-summary-header">
                                    @php
                                        $summaryIcon = match ($operationalEfficiency['overall']['class']) {
                                            'health-green' => 'check-circle',
                                            'health-yellow' => 'alert-circle',
                                            'health-orange' => 'alert-triangle',
                                            default => 'x-circle',
                                        };
                                    @endphp
                                    <i data-lucide="{{ $summaryIcon }}" class="op-summary-check"></i>
                                    <h4>Operations Summary</h4>
                                </div>
                                <p>{{ $operationalEfficiency['summary_text'] }}</p>
                            </div>
                        </div>
                        <div class="op-health-row">
                            <div class="op-dept-full-card">
                                <div class="op-health-card" style="border:none; padding:0 0 0.75rem 0;">
                                    <div class="op-donut">
                                        <svg viewBox="0 0 36 36" class="op-donut-svg">
                                            <path class="op-donut-track"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path
                                                class="op-donut-fill {{ $operationalEfficiency['manufacturing']['class'] }}"
                                                stroke-dasharray="{{ $operationalEfficiency['manufacturing']['percent'] }}, 100"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span
                                            class="op-donut-text">{{ $operationalEfficiency['manufacturing']['percent'] }}%</span>
                                    </div>
                                    <div class="op-health-info">
                                        <h4>Manufacturing Health</h4>
                                        <div class="op-health-badge {{ $operationalEfficiency['manufacturing']['class'] }}">
                                            {{ $operationalEfficiency['manufacturing']['health'] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="op-metrics-mini">
                                    @foreach($operationalEfficiency['manufacturing']['metrics'] as $metric)
                                        <div class="op-metric-item">
                                            <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                            <span class="op-metric-label">{{ $metric['label'] }}</span>
                                            <span class="op-metric-val">{{ $metric['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="op-dept-full-card">
                                <div class="op-health-card" style="border:none; padding:0 0 0.75rem 0;">
                                    <div class="op-donut">
                                        <svg viewBox="0 0 36 36" class="op-donut-svg">
                                            <path class="op-donut-track"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="op-donut-fill {{ $operationalEfficiency['fulfillment']['class'] }}"
                                                stroke-dasharray="{{ $operationalEfficiency['fulfillment']['percent'] }}, 100"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span
                                            class="op-donut-text">{{ $operationalEfficiency['fulfillment']['percent'] }}%</span>
                                    </div>
                                    <div class="op-health-info">
                                        <h4>Order Fulfillment Health</h4>
                                        <div class="op-health-badge {{ $operationalEfficiency['fulfillment']['class'] }}">
                                            {{ $operationalEfficiency['fulfillment']['health'] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="op-metrics-mini">
                                    @foreach($operationalEfficiency['fulfillment']['metrics'] as $metric)
                                        <div class="op-metric-item">
                                            <i data-lucide="{{ $metric['icon'] }}" class="op-metric-icon"></i>
                                            <span class="op-metric-label">{{ $metric['label'] }}</span>
                                            <span class="op-metric-val">{{ $metric['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Alert Feed – separate card --}}
                    <div class="ui-card" style="display:flex; flex-direction:column; gap:0.75rem;">
                        <div class="op-risks-header">
                            <div class="op-risks-title-row">
                                <i data-lucide="alert-triangle" class="op-risks-icon"></i>
                                <h4>Alert Feed</h4>
                                <span class="op-health-badge" id="opRisksTotal"
                                    style="background: var(--slate-200); color: var(--deep-navy);">—</span>
                            </div>
                            <a href="{{ route('live-monitor') }}" class="view-ai-btn">See All</a>
                        </div>
                        <div class="op-risks-counts" id="opRisksCounts">
                            <div class="op-risk-count op-risk-count-critical">
                                <span class="op-risk-count-num">—</span>
                                <span class="op-risk-count-label">Critical</span>
                            </div>
                            <div class="op-risk-count op-risk-count-warning">
                                <span class="op-risk-count-num">—</span>
                                <span class="op-risk-count-label">Warning</span>
                            </div>
                            <div class="op-risk-count op-risk-count-info">
                                <span class="op-risk-count-num">—</span>
                                <span class="op-risk-count-label">Info</span>
                            </div>
                        </div>
                        <span class="op-severity-label">Recent</span>
                        <div class="op-risks-mini-grid" id="opRisksMini"></div>
                        <div class="op-severity-section">
                            <div class="op-severity-bar" id="opRisksBar">
                                <div class="op-severity-seg health-red" style="width:33%;"></div>
                                <div class="op-severity-seg health-orange" style="width:33%;"></div>
                                <div class="op-severity-seg health-blue" style="width:34%;"></div>
                            </div>
                            <div class="op-severity-legend" id="opRisksLegend">
                                <span><span class="op-legend-dot health-red"></span>Critical —%</span>
                                <span><span class="op-legend-dot health-orange"></span>Warning —%</span>
                                <span><span class="op-legend-dot health-blue"></span>Info —%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let salesTrendChart;

        const verticalLinePlugin = {
            id: 'verticalLine',
            afterDraw(chart) {
                if (chart.tooltip?._active?.length) {
                    const activePoint = chart.tooltip._active[0];
                    const ctx = chart.ctx;
                    const x = activePoint.element.x;
                    const topY = chart.scales.y.top;
                    const bottomY = chart.scales.y.bottom;
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(x, topY);
                    ctx.lineTo(x, bottomY);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#1B6FC8';
                    ctx.setLineDash([4, 4]);
                    ctx.stroke();
                    ctx.restore();
                }
            }
        };

        function timeAgo(timestamp) {
            const seconds = Math.floor((Date.now() - new Date(timestamp).getTime()) / 1000);
            if (seconds < 10) return 'Just now';
            if (seconds < 60) return seconds + 's ago';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + 'm ago';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + 'h ago';
            return Math.floor(hours / 24) + 'd ago';
        }

        async function syncAllDepartments() {
            const btn = document.getElementById('syncNowBtn');
            const icon = btn.querySelector('.control-icon');
            btn.disabled = true;
            icon.classList.add('spin-icon');
            try {
                const res = await fetch('/api/sync-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (!res.ok && res.status !== 207) {
                    throw new Error(data.message || 'Sync failed.');
                }
                window.location.reload();
            } catch (e) {
                btn.disabled = false;
                icon.classList.remove('spin-icon');
                alert('Sync failed: ' + e.message);
            }
        }

        async function fetchOpRisks() {
            try {
                const res = await fetch('/api/live-feed');
                const data = await res.json();
                updateOpRisks(data);
            } catch (e) { }
        }

        function updateOpRisks(data) {
            const total = data.summary.critical + data.summary.warning + data.summary.info;
            document.getElementById('opRisksTotal').textContent = total + ' Active';
            const counts = document.querySelectorAll('#opRisksCounts .op-risk-count-num');
            if (counts[0]) counts[0].textContent = data.summary.critical;
            if (counts[1]) counts[1].textContent = data.summary.warning;
            if (counts[2]) counts[2].textContent = data.summary.info;
            const totalSev = total > 0 ? total : 1;
            const bar = document.getElementById('opRisksBar');
            bar.innerHTML = `
                                    <div class="op-severity-seg health-red" style="width:${Math.round((data.summary.critical / totalSev) * 100)}%;"></div>
                                    <div class="op-severity-seg health-orange" style="width:${Math.round((data.summary.warning / totalSev) * 100)}%;"></div>
                                    <div class="op-severity-seg health-blue" style="width:${Math.round((data.summary.info / totalSev) * 100)}%;"></div>`;
            document.getElementById('opRisksLegend').innerHTML = `
                                    <span><span class="op-legend-dot health-red"></span>Critical ${Math.round((data.summary.critical / totalSev) * 100)}%</span>
                                    <span><span class="op-legend-dot health-orange"></span>Warning ${Math.round((data.summary.warning / totalSev) * 100)}%</span>
                                    <span><span class="op-legend-dot health-blue"></span>Info ${Math.round((data.summary.info / totalSev) * 100)}%</span>`;
            const miniGrid = document.getElementById('opRisksMini');
            if (data.alerts && data.alerts.length > 0) {
                miniGrid.innerHTML = data.alerts.slice(0, 3).map(a => `
                                        <div class="op-risk-mini-card op-risk-mini-${a.severity}">
                                            <div class="op-risk-mini-header">
                                                <span class="op-risk-mini-category">${a.department}</span>
                                                <span class="op-risk-mini-days" data-timestamp="${a.timestamp}">${timeAgo(a.timestamp)}</span>
                                            </div>
                                            <p class="op-risk-mini-issue">${a.title}</p>
                                        </div>`).join('');
            }
        }

        function initSalesChart() {
            const ctx = document.getElementById('salesTrendChart');
            if (!ctx) return;
            salesTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [0, 0, 0, 0, 0, 0, 0],
                        borderColor: '#1B6FC8',
                        backgroundColor: 'rgba(27,111,200,0.15)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#1B6FC8',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0B1E3D',
                            bodyColor: '#0B1E3D',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            cornerRadius: 6,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#E2E8F0' },
                            border: { display: true, color: '#E2E8F0' },
                            ticks: { precision: 0 },
                            grace: '10%'
                        },
                        x: {
                            grid: { display: false },
                            border: { display: true, color: '#E2E8F0' }
                        }
                    }
                },
                plugins: [verticalLinePlugin]
            });
            changeSalesRange();

            // Apply dark mode colors if needed
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                const gridColor = '#64748B';
                salesTrendChart.options.scales.y.grid.color = gridColor;
                salesTrendChart.options.scales.y.border.color = gridColor;
                salesTrendChart.options.scales.x.border.color = gridColor;
                salesTrendChart.options.scales.y.ticks.color = '#94A3B8';
                salesTrendChart.options.scales.x.ticks.color = '#94A3B8';
                salesTrendChart.update();
            }
        }

        async function changeSalesRange() {
            const range = document.getElementById('salesRange')?.value || '7d';
            try {
                const res = await fetch(`/api/sales-forecast?range=${range}`);
                const data = await res.json();
                if (salesTrendChart) {
                    salesTrendChart.data.labels = data.labels;
                    salesTrendChart.data.datasets[0].data = data.sales;

                    if (data.year) {
                        salesTrendChart.options.plugins.title = {
                            display: true,
                            text: data.year.toString(),
                            position: 'top',
                            align: 'end',
                            font: { size: 11 },
                            color: '#64748B'
                        };
                    } else {
                        salesTrendChart.options.plugins.title = { display: false };
                    }

                    salesTrendChart.update();
                }
            } catch (e) { }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initSalesChart();
            fetchOpRisks();
            setInterval(fetchOpRisks, 30000);
            setInterval(() => {
                document.querySelectorAll('.op-risk-mini-days').forEach(el => {
                    const ts = el.getAttribute('data-timestamp');
                    if (ts) el.textContent = timeAgo(ts);
                });
            }, 10000);
        });

        let salesChart;
        const clientScope = @json(request()->integer('client_id') ?: null);
        async function loadSalesTrend() {
            const range = document.getElementById('salesRange').value;
            const response = await fetch(@json(route('bi.sales-forecast')).concat('?range=', range, clientScope ? '&client_id=' + clientScope : ''));
            const data = await response.json();
            salesChart?.destroy();
            salesChart = new Chart(document.getElementById('salesTrendChart'), { type: 'line', data: { labels: data.labels, datasets: [{ label: 'Invoice revenue', data: data.sales, borderColor: '#1B6FC8', backgroundColor: 'rgba(27,111,200,.12)', fill: true, tension: .35 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });
        }
        document.getElementById('salesRange').addEventListener('change', loadSalesTrend); loadSalesTrend();
    </script>
@endsection
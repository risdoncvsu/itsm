@extends('bi::layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block">
    <div class="subheader-bar">
        <div class="subheader-title">
            <h3>Executive Dashboard</h3>
            <p>Live, client-scoped metrics from your connected Nexora modules.</p>
        </div>
        <div class="subheader-controls">
            @if($clientId)<span class="control-date-selector">Client #{{ $clientId }}</span>@else<span class="control-date-selector">Choose a client to test BI</span>@endif
            <a href="{{ route('bi.live-monitor') }}" class="control-btn" title="View activity"><i data-lucide="activity" class="control-icon"></i></a>
        </div>
    </div>
    <div class="content-container">
        @if(! $clientId)
            <div class="ui-card" style="padding:1.25rem"><strong>BI is ready.</strong> Root-admin testing requires a client scope, e.g. <code>/bi/dashboard?client_id=23</code>. Employee accounts are scoped automatically.</div>
        @endif
        <section class="kpi-grid">
            <div class="kpi-card"><div class="kpi-icon-container"><i data-lucide="dollar-sign" class="kpi-icon"></i></div><div class="kpi-details"><div class="kpi-label">Revenue collected</div><div class="kpi-value">₱{{ number_format($metrics['revenue'], 2) }}</div><div class="kpi-change change-up">Finance</div></div></div>
            <div class="kpi-card"><div class="kpi-icon-container"><i data-lucide="wallet" class="kpi-icon"></i></div><div class="kpi-details"><div class="kpi-label">Net after expenses</div><div class="kpi-value">₱{{ number_format($metrics['profit'], 2) }}</div><div class="kpi-change {{ $metrics['profit'] >= 0 ? 'change-up' : 'change-down' }}">Live calculation</div></div></div>
            <div class="kpi-card"><div class="kpi-icon-container"><i data-lucide="package" class="kpi-icon"></i></div><div class="kpi-details"><div class="kpi-label">Inventory items</div><div class="kpi-value">{{ number_format($metrics['inventory_items']) }}</div><div class="kpi-change {{ $metrics['inventory_low_stock'] ? 'change-down' : 'change-up' }}">{{ $metrics['inventory_low_stock'] }} low-stock alerts</div></div></div>
            <div class="kpi-card"><div class="kpi-icon-container"><i data-lucide="shopping-cart" class="kpi-icon"></i></div><div class="kpi-details"><div class="kpi-label">Fulfillment orders</div><div class="kpi-value">{{ number_format($metrics['fulfillment_orders']) }}</div><div class="kpi-change change-up">Order Fulfillment</div></div></div>
            <div class="kpi-card"><div class="kpi-icon-container"><i data-lucide="factory" class="kpi-icon"></i></div><div class="kpi-details"><div class="kpi-label">Active work orders</div><div class="kpi-value">{{ number_format($metrics['manufacturing_active']) }}</div><div class="kpi-change change-up">Manufacturing</div></div></div>
        </section>
        <div class="dashboard-layout-grid">
            <div class="section-column">
                <div class="ui-card"><div class="card-header"><div class="card-title">Invoice Revenue Trend</div><select id="salesRange" class="control-date-selector"><option value="7d">7 Days</option><option value="1m">1 Month</option><option value="1y">1 Year</option></select></div><div class="placeholder-graph-box chart-box"><canvas id="salesTrendChart"></canvas></div></div>
            </div>
            <div class="section-column">
                <div class="ui-card"><div class="card-header"><div class="card-title">Connected Module Health</div></div>
                    <div class="op-metrics-grid" style="padding:1rem">
                        <div class="op-metric-item"><span class="op-metric-label">Open Purchase Orders</span><span class="op-metric-val">{{ $metrics['procurement_open'] }}</span></div>
                        <div class="op-metric-item"><span class="op-metric-label">Overdue Invoices</span><span class="op-metric-val">{{ $metrics['finance_overdue'] }}</span></div>
                        <div class="op-metric-item"><span class="op-metric-label">Delayed Shipments</span><span class="op-metric-val">{{ $metrics['fulfillment_delayed'] }}</span></div>
                        <div class="op-metric-item"><span class="op-metric-label">Catalog Records</span><span class="op-metric-val">{{ $metrics['ecommerce_products'] }}</span></div>
                    </div>
                    <p style="padding:0 1rem 1rem;color:var(--slate-500);font-size:12px">No data is copied into ITSM. This dashboard reads only the current client’s records from each owning module database.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let salesChart;
const clientScope = @json(request()->integer('client_id') ?: null);
async function loadSalesTrend() {
    const range = document.getElementById('salesRange').value;
    const response = await fetch(@json(route('bi.sales-forecast')).concat('?range=', range, clientScope ? '&client_id=' + clientScope : ''));
    const data = await response.json();
    salesChart?.destroy();
    salesChart = new Chart(document.getElementById('salesTrendChart'), {type: 'line', data: {labels: data.labels, datasets: [{label: 'Invoice revenue', data: data.sales, borderColor: '#1B6FC8', backgroundColor: 'rgba(27,111,200,.12)', fill: true, tension: .35}]}, options: {responsive: true, maintainAspectRatio: false, plugins: {legend: {display: false}}, scales: {y: {beginAtZero: true}}}});
}
document.getElementById('salesRange').addEventListener('change', loadSalesTrend); loadSalesTrend();
</script>
@endsection

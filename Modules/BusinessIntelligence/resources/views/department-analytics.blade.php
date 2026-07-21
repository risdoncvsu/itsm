@extends('bi::layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block">
    <div class="subheader-bar"><div class="subheader-title"><h3>Department Analytics</h3><p>Compare operational KPIs without crossing client boundaries.</p></div></div>
    <div class="content-container">
        <div class="ui-card" style="padding:1rem;margin-bottom:1rem"><label for="department" class="kpi-label">Department</label><select id="department" class="control-date-selector"><option value="finance">Finance & Accounting</option><option value="inventory">Inventory & Warehouse</option><option value="procurement">Procurement</option><option value="manufacturing">Manufacturing</option><option value="fulfillment">Order Fulfillment</option><option value="ecommerce">E-commerce & CRM</option></select></div>
        <section id="stats" class="kpi-grid"></section>
        <div class="dashboard-layout-grid"><div class="section-column"><div class="ui-card"><div class="card-header"><div id="chart1Title" class="card-title">Overview</div></div><div class="placeholder-graph-box chart-box"><canvas id="chart1"></canvas></div></div></div><div class="section-column"><div class="ui-card"><div class="card-header"><div id="chart2Title" class="card-title">Breakdown</div></div><div class="placeholder-graph-box chart-box"><canvas id="chart2"></canvas></div></div></div></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let firstChart, secondChart;
const endpoint = @json(url('/bi/api/department'));
const clientScope = @json(request()->integer('client_id') ?: null);
const colors = ['#1B6FC8','#4A9EE8','#16A34A','#D97706','#DC2626','#7BBEF0'];
function draw(canvas, chart, data) { chart?.destroy(); return new Chart(document.getElementById(canvas), {type: data.type || 'bar', data: {labels: (data.data || []).map(x => x.label), datasets: [{data: (data.data || []).map(x => x.value), backgroundColor: colors, borderColor: '#1B6FC8', borderWidth: 1}]}, options: {responsive:true,maintainAspectRatio:false,plugins:{legend:{display:data.type === 'doughnut'}},scales:data.type === 'doughnut' ? {} : {y:{beginAtZero:true}}}}); }
async function loadDepartment() { const response = await fetch(endpoint + '/' + document.getElementById('department').value + (clientScope ? '?client_id=' + clientScope : '')); const data = await response.json(); document.getElementById('stats').innerHTML = (data.stats || []).map(x => `<div class="kpi-card"><div class="kpi-details"><div class="kpi-label">${x.label}</div><div class="kpi-value">${typeof x.value === 'number' ? x.value.toLocaleString() : x.value}</div></div></div>`).join(''); document.getElementById('chart1Title').textContent = data.chart1.label; document.getElementById('chart2Title').textContent = data.chart2.label; firstChart = draw('chart1', firstChart, data.chart1); secondChart = draw('chart2', secondChart, data.chart2); }
document.getElementById('department').addEventListener('change', loadDepartment); loadDepartment();
</script>
@endsection

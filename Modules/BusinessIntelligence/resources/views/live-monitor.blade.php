@extends('bi::layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block">
    <div class="subheader-bar"><div class="subheader-title"><h3>Live Monitor</h3><p>Client-scoped operational alerts. Refreshes every 30 seconds.</p></div><div class="subheader-controls"><button class="control-btn" onclick="loadFeed()"><i data-lucide="refresh-cw" class="control-icon"></i></button></div></div>
    <div class="content-container"><div id="summary" class="live-summary-bar"></div><div id="feed" class="live-alerts-grid"><p style="color:var(--slate-500)">Loading activity…</p></div></div>
</div>
@endsection

@section('scripts')
<script>
const feedEndpoint = @json(route('bi.live-feed'));
const clientScope = @json(request()->integer('client_id') ?: null);
async function loadFeed() { const response = await fetch(feedEndpoint + (clientScope ? '?client_id=' + clientScope : '')); const data = await response.json(); document.getElementById('summary').innerHTML = [['Active alerts', data.alerts.length, ''], ['Critical', data.summary.critical, 'live-summary-critical'], ['Warnings', data.summary.warning, 'live-summary-warning'], ['Information', data.summary.info, 'live-summary-info']].map(x => `<div class="live-summary-item ${x[2]}"><span class="live-summary-count">${x[1]}</span><span class="live-summary-label">${x[0]}</span></div>`).join(''); document.getElementById('feed').innerHTML = data.alerts.length ? data.alerts.map(a => `<div class="live-alert-card live-alert-${a.severity}"><div class="live-alert-meta"><span class="live-alert-dept">${a.department}</span><span class="live-alert-title">${a.title}</span></div><p class="live-alert-desc">${a.description}</p></div>`).join('') : '<p style="color:var(--slate-500);padding:1rem">All systems are operating normally for this client.</p>'; }
loadFeed(); setInterval(loadFeed, 30000);
</script>
@endsection

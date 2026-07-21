@extends('bi::layouts.app')

@section('content')
<div class="tab-content active-tab" style="display:block"><div class="subheader-bar"><div class="subheader-title"><h3>AI Insights</h3><p>AI analysis will use this client’s pre-scoped BI aggregates.</p></div></div><div class="content-container"><div class="ui-card" style="padding:1.5rem"><h4 style="margin:0 0 .75rem">Ready for an inference API key</h4><p style="color:var(--slate-500);line-height:1.6">The Business Intelligence dashboard is already client-scoped. When you add the DigitalOcean inference API key, AI requests will be made server-side from these safe aggregates—not from raw module tables and never from browser JavaScript.</p></div></div></div>
@endsection

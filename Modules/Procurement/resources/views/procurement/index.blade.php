{{--
    resources/views/procurement/index.blade.php

    This view exists to match the requested folder layout:

        procurement/index
        procurement/partials/{dashboard,purchase-orders,suppliers,
            requisition,deliveries}

    The actual "/procurement" route redirects straight to the Dashboard
    page (see routes/web.php), so in normal use this file is never
    rendered â€” it's kept here as a safe fallback landing page in
    case something links to view('procurement.index') directly.
--}}
@extends('procurement::layouts.dashboard')

@php($pageKey = '')

@section('title', 'Procurement')

@section('content')
<section style="padding:48px; text-align:center;">
    <h1>Nexora Procurement</h1>
    <p style="color:var(--muted); margin-top:8px;">
        Redirecting to the <a href="{{ route('procurement.dashboard') }}" style="color:var(--blue); font-weight:600;">Dashboard</a>â€¦
    </p>
</section>
@endsection


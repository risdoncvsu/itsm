<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nexora — Business Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bi/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <header class="header">
        <a href="{{ route('bi.dashboard') }}" class="nexora-logo">
            <img src="{{ asset('bi/images/Banner Transparent.png') }}" alt="Nexora">
        </a>
        <div class="header-right">
            <span style="font-size:12px;color:var(--slate-500)">{{ session('employee_name', auth()->user()?->username ?? 'Nexora user') }}</span>
        </div>
    </header>
    <div class="app-body">
        <aside>
            <div class="nav-menu">
                <a href="{{ route('bi.dashboard') }}" class="nav-item {{ request()->routeIs('bi.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <div class="nav-item-title"><i data-lucide="layout-dashboard" class="nav-icon"></i>Dashboard</div>
                    <div class="nav-item-sub">Executive overview</div>
                </a>
                <a href="{{ route('bi.department-analytics') }}" class="nav-item {{ request()->routeIs('bi.department-analytics') ? 'active' : '' }}" data-tooltip="Department Analytics">
                    <div class="nav-item-title"><i data-lucide="building-2" class="nav-icon"></i>Department Analytics</div>
                    <div class="nav-item-sub">KPI deep dive</div>
                </a>
                <a href="{{ route('bi.live-monitor') }}" class="nav-item {{ request()->routeIs('bi.live-monitor') ? 'active' : '' }}" data-tooltip="Live Monitor">
                    <div class="nav-item-title"><i data-lucide="activity" class="nav-icon"></i>Live Monitor</div>
                    <div class="nav-item-sub">Client activity feed</div>
                </a>
                <a href="{{ route('bi.ai-insights') }}" class="nav-item {{ request()->routeIs('bi.ai-insights') ? 'active' : '' }}" data-tooltip="AI Insights">
                    <div class="nav-item-title"><i data-lucide="brain" class="nav-icon"></i>AI Insights</div>
                    <div class="nav-item-sub">Coming next</div>
                </a>
            </div>
            <div class="sidebar-footer"><i data-lucide="shield-check" class="footer-icon"></i><span>Client-scoped BI</span></div>
        </aside>
        <main>@yield('content')</main>
    </div>
    <script>lucide.createIcons();</script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Procurement') — Nexora ERP</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/procurement.css') }}">
</head>
<body data-page="{{ $pageKey ?? '' }}">

@include('procurement::procurement.partials.topbar')

<div class="app">
    @include('procurement::procurement.partials.sidebar')

    <main class="main">
        @yield('content')
    </main>
</div>

@include('procurement::procurement.partials.modals')

<div id="toast-stack"></div>

</body>
</html>


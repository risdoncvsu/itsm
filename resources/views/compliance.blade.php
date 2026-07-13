<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora | Compliance Tracking</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/nexora-icon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#1B365D] font-sans text-white">
    <div class="flex min-h-screen flex-col">
        <x-itsm-header
            :home-route="route('client.itsm.employees')"
            active="compliance"
            :nav-items="[
                ['label' => 'Employee Management', 'route' => route('client.itsm.employees'), 'key' => 'employees'],
                ['label' => 'Service Desk', 'route' => route('client.itsm.service-desk'), 'key' => 'service-desk'],
                ['label' => 'Compliance Tracking', 'route' => route('client.itsm.compliance'), 'key' => 'compliance'],
                ['label' => 'Risk Management', 'route' => route('client.itsm.risk'), 'key' => 'risk'],
            ]"
        />

        <main class="relative flex-1 overflow-hidden p-6">
            <img src="{{ asset('images/nexora-icon.png') }}" alt="" class="pointer-events-none absolute left-1/2 top-1/2 w-[64rem] -translate-x-1/2 -translate-y-1/2 opacity-10 blur-sm">

            <section class="relative z-10 space-y-6">
                <div class="rounded-[1.875rem] bg-white/90 px-10 py-8 text-slate-950">
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#346DCB]">Company admin portal</p>
                    <h1 class="mt-2 text-5xl font-bold">Compliance Tracking</h1>
                    <p class="mt-3 text-lg text-slate-600">Monitor employee completion, policy acknowledgement, and audit readiness.</p>
                </div>

                <div class="grid gap-6 xl:grid-cols-4">
                    @foreach ([
                        ['title' => 'Data Privacy', 'audience' => 'All Employees', 'status' => 'Active', 'progress' => '68%', 'color' => 'bg-[#16A34A]'],
                        ['title' => 'Workplace Safety', 'audience' => 'Operations', 'status' => 'Urgent', 'progress' => '97%', 'color' => 'bg-[#DC2626]'],
                        ['title' => 'Code of Conduct', 'audience' => 'All Employees', 'status' => 'Completed', 'progress' => '100%', 'color' => 'bg-[#16A34A]'],
                        ['title' => 'Cybersecurity Awareness', 'audience' => 'All Employees', 'status' => 'Pending Review', 'progress' => '0%', 'color' => 'bg-[#D97706]'],
                    ] as $item)
                        <article class="rounded-2xl bg-white p-6 text-slate-950">
                            <h2 class="min-h-16 text-2xl font-semibold">{{ $item['title'] }}</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-500">{{ $item['audience'] }}</p>
                            <div class="mt-8 flex items-center justify-between text-sm">
                                <span class="rounded-full {{ $item['color'] }} px-3 py-1 font-medium text-white">{{ $item['status'] }}</span>
                                <span class="font-semibold">{{ $item['progress'] }}</span>
                            </div>
                            <div class="mt-4 h-4 rounded-full bg-[#9AB3CE]">
                                <div class="h-4 rounded-full bg-[#132B52]" style="width: {{ $item['progress'] }}"></div>
                            </div>
                            <button class="mt-8 w-full rounded-md border-2 border-slate-950 py-2 font-medium transition hover:bg-slate-950 hover:text-white">View</button>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</body>
</html>

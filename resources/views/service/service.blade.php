@php
    $portal = $portal ?? 'client';
    $active = $active ?? 'service-desk';
    $title = $title ?? 'Service Desk';
    $subtitle = $subtitle ?? 'Track and manage ITSM tickets';
    $navItems = $portal === 'admin'
        ? [
            ['label' => 'Registration', 'route' => route('admin.itsm.registration'), 'key' => 'registration'],
            ['label' => 'Client Management', 'route' => route('admin.itsm.clients'), 'key' => 'clients'],
            ['label' => 'Service Desk', 'route' => route('admin.itsm.service-desk'), 'key' => 'service-desk'],
        ]
        : [
            ['label' => 'Employee Management', 'route' => route('client.itsm.employees'), 'key' => 'employees'],
            ['label' => 'Service Desk', 'route' => route('client.itsm.service-desk'), 'key' => 'service-desk'],
            ['label' => 'Compliance Tracking', 'route' => route('client.itsm.compliance'), 'key' => 'compliance'],
            ['label' => 'Risk Management', 'route' => route('client.itsm.risk'), 'key' => 'risk'],
        ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora | {{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/nexora-icon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#1B365D] font-sans text-white">
    <div class="flex min-h-screen flex-col">
        <x-itsm-header
            :home-route="$portal === 'admin' ? route('admin.itsm.registration') : route('client.itsm.employees')"
            :active="$active"
            :nav-items="$navItems"
        />

        <main class="relative flex-1 overflow-hidden p-6">
            <img src="{{ asset('images/nexora-icon.png') }}" alt="" class="pointer-events-none absolute left-1/2 top-1/2 w-[64rem] -translate-x-1/2 -translate-y-1/2 opacity-10 blur-sm">

            <section class="relative z-10 grid gap-6 lg:grid-cols-[22rem_1fr]">
                <aside class="rounded-[1.875rem] bg-white p-8 text-slate-950">
                    <nav class="space-y-6 text-xl">
                        <a href="#" class="block font-extrabold">All Tickets</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Assigned Tickets</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Knowledge Base</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Service Catalog</a>
                    </nav>
                </aside>

                <div class="space-y-6">
                    <div class="rounded-[1.875rem] bg-white/90 px-10 py-8 text-slate-950">
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#346DCB]">{{ $portal === 'admin' ? 'Nexora admin portal' : 'Company admin portal' }}</p>
                        <h1 class="mt-2 text-5xl font-bold">{{ $title }}</h1>
                        <p class="mt-3 text-lg text-slate-600">{{ $subtitle }}</p>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-4">
                        <div class="rounded-2xl bg-white p-6 text-slate-950">
                            <p class="text-sm font-semibold text-slate-500">Open Tickets</p>
                            <p class="mt-3 text-4xl font-bold">18</p>
                        </div>
                        <div class="rounded-2xl bg-white p-6 text-slate-950">
                            <p class="text-sm font-semibold text-slate-500">Assigned</p>
                            <p class="mt-3 text-4xl font-bold">7</p>
                        </div>
                        <div class="rounded-2xl bg-white p-6 text-slate-950">
                            <p class="text-sm font-semibold text-slate-500">Pending Review</p>
                            <p class="mt-3 text-4xl font-bold">5</p>
                        </div>
                        <div class="rounded-2xl bg-white p-6 text-slate-950">
                            <p class="text-sm font-semibold text-slate-500">Resolved Today</p>
                            <p class="mt-3 text-4xl font-bold">12</p>
                        </div>
                    </div>

                    <div class="rounded-[1.875rem] bg-white p-8 text-slate-950">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-bold">Recent Requests</h2>
                            <button class="rounded-full bg-[#346DCB] px-5 py-2 font-semibold text-white transition hover:bg-[#2554a3]">Create Ticket</button>
                        </div>

                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b-2 border-slate-200 text-left text-sm uppercase tracking-wide text-slate-500">
                                    <th class="py-3">Ticket</th>
                                    <th class="py-3">{{ $portal === 'admin' ? 'Client' : 'Requester' }}</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3">Priority</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr class="border-b border-slate-200">
                                    <td class="py-4 font-semibold">NX-1042</td>
                                    <td class="py-4">{{ $portal === 'admin' ? 'Acme Manufacturing' : 'Maria Santos' }}</td>
                                    <td class="py-4">ERP Access</td>
                                    <td class="py-4">High</td>
                                    <td class="py-4">Open</td>
                                </tr>
                                <tr class="border-b border-slate-200">
                                    <td class="py-4 font-semibold">NX-1041</td>
                                    <td class="py-4">{{ $portal === 'admin' ? 'Northwind Finance' : 'Daniel Cruz' }}</td>
                                    <td class="py-4">Workflow Setup</td>
                                    <td class="py-4">Medium</td>
                                    <td class="py-4">In Progress</td>
                                </tr>
                                <tr>
                                    <td class="py-4 font-semibold">NX-1040</td>
                                    <td class="py-4">{{ $portal === 'admin' ? 'Blue Harbor Retail' : 'Alyssa Tan' }}</td>
                                    <td class="py-4">Data Import</td>
                                    <td class="py-4">Low</td>
                                    <td class="py-4">Resolved</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

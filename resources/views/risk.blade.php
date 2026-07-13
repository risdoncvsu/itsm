<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora | Risk Management</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/nexora-icon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#1B365D] font-sans text-white">
    <div class="flex min-h-screen flex-col">
        <x-itsm-header
            :home-route="route('client.itsm.employees')"
            active="risk"
            :nav-items="[
                ['label' => 'Employee Management', 'route' => route('client.itsm.employees'), 'key' => 'employees'],
                ['label' => 'Service Desk', 'route' => route('client.itsm.service-desk'), 'key' => 'service-desk'],
                ['label' => 'Compliance Tracking', 'route' => route('client.itsm.compliance'), 'key' => 'compliance'],
                ['label' => 'Risk Management', 'route' => route('client.itsm.risk'), 'key' => 'risk'],
            ]"
        />

        <main class="relative flex-1 overflow-hidden p-6">
            <img src="{{ asset('images/nexora-icon.png') }}" alt="" class="pointer-events-none absolute left-1/2 top-1/2 w-[64rem] -translate-x-1/2 -translate-y-1/2 opacity-10 blur-sm">

            <section class="relative z-10 grid gap-6 lg:grid-cols-[22rem_1fr]">
                <aside class="rounded-[1.875rem] bg-white p-8 text-slate-950">
                    <nav class="space-y-6 text-xl">
                        <a href="#" class="block font-extrabold">Risk Register</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Mitigation Plans</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Incident Report</a>
                        <a href="#" class="block font-medium hover:text-[#346DCB]">Risk Analytics</a>
                    </nav>
                </aside>

                <div class="space-y-6">
                    <div class="rounded-[1.875rem] bg-white/90 px-10 py-8 text-slate-950">
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#346DCB]">Company admin portal</p>
                        <h1 class="mt-2 text-5xl font-bold">Risk Management</h1>
                        <p class="mt-3 text-lg text-slate-600">Track operational, compliance, and workforce risks for your company.</p>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-3">
                        @foreach ([
                            ['title' => 'High Employee Turnover', 'level' => 'High', 'owner' => 'HR', 'status' => 'Mitigation Open'],
                            ['title' => 'Access Review Overdue', 'level' => 'Medium', 'owner' => 'IT', 'status' => 'Pending Review'],
                            ['title' => 'Vendor SLA Breach', 'level' => 'Low', 'owner' => 'Operations', 'status' => 'Monitoring'],
                        ] as $item)
                            <article class="rounded-2xl bg-white p-6 text-slate-950">
                                <h2 class="text-2xl font-semibold">{{ $item['title'] }}</h2>
                                <dl class="mt-8 space-y-4 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="font-semibold text-slate-500">Risk Level</dt>
                                        <dd class="font-bold">{{ $item['level'] }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-semibold text-slate-500">Owner</dt>
                                        <dd>{{ $item['owner'] }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-semibold text-slate-500">Status</dt>
                                        <dd>{{ $item['status'] }}</dd>
                                    </div>
                                </dl>
                                <button class="mt-8 w-full rounded-md border-2 border-slate-950 py-2 font-medium transition hover:bg-slate-950 hover:text-white">Open</button>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

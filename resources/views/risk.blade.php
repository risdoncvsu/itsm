@php
    $risks = $risks ?? collect();
@endphp
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
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-4">
                            <h1 class="text-5xl font-bold">Risk Management</h1>
                            <button type="button" id="openRiskModal" class="rounded-full bg-[#346DCB] px-5 py-2 font-semibold text-white transition hover:bg-[#2554a3]">Create Risk</button>
                        </div>
                        <p class="mt-3 text-lg text-slate-600">Track operational, compliance, and workforce risks for your company.</p>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-md bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
                    @endif

                    @if (session('success'))
                        <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
                    @endif

                    <div class="grid gap-6 xl:grid-cols-3">
                        @forelse ($risks as $risk)
                            <article
                                class="rounded-2xl bg-white p-6 text-slate-950"
                                data-id="{{ $risk->id }}"
                                data-title="{{ e($risk->title) }}"
                                data-category="{{ e($risk->category) }}"
                                data-level="{{ e($risk->level) }}"
                                data-owner="{{ e($risk->owner) }}"
                                data-status="{{ e($risk->status) }}"
                                data-review-date="{{ $risk->review_date ? $risk->review_date->format('Y-m-d') : '' }}"
                                data-mitigation-plan="{{ e($risk->mitigation_plan) }}"
                            >
                                <h2 class="text-2xl font-semibold">{{ $risk->title }}</h2>
                                <dl class="mt-8 space-y-4 text-sm">
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-slate-500">Risk Level</dt>
                                        <dd class="font-bold">{{ $risk->level }}</dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-slate-500">Owner</dt>
                                        <dd>{{ $risk->owner ?? 'Unassigned' }}</dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-slate-500">Status</dt>
                                        <dd>{{ $risk->status }}</dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-slate-500">Review</dt>
                                        <dd>{{ $risk->review_date ? $risk->review_date->format('M j, Y') : 'Not set' }}</dd>
                                    </div>
                                </dl>
                                <button type="button" class="edit-risk mt-8 w-full rounded-md border-2 border-slate-950 py-2 font-medium transition hover:bg-slate-950 hover:text-white">Edit</button>
                            </article>
                        @empty
                            <div class="rounded-2xl bg-white p-8 text-center text-slate-500 xl:col-span-3">No risk assessments yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </main>

        <div id="riskModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-6">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-8 text-slate-950 shadow-2xl">
                <div class="mb-6 flex items-center justify-between">
                    <h2 id="riskModalTitle" class="text-2xl font-bold">Create Risk</h2>
                    <button type="button" id="closeRiskModal" class="text-2xl font-bold text-slate-500 hover:text-slate-950">&times;</button>
                </div>

                <form id="riskForm" method="POST" action="{{ route('client.itsm.risk.store') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @csrf
                    <input type="hidden" name="_method" id="riskMethod" value="POST">

                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-semibold">Risk Title</span>
                        <input type="text" name="title" id="risk_title" required class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Category</span>
                        <input type="text" name="category" id="risk_category" class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Risk Level</span>
                        <select name="level" id="risk_level" class="h-11 w-full rounded border border-slate-300 px-3">
                            <option>Low</option>
                            <option selected>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Owner</span>
                        <input type="text" name="owner" id="risk_owner" class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Status</span>
                        <select name="status" id="risk_status" class="h-11 w-full rounded border border-slate-300 px-3">
                            <option>Monitoring</option>
                            <option>Pending Review</option>
                            <option>Mitigation Open</option>
                            <option>Accepted</option>
                            <option>Closed</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Review Date</span>
                        <input type="date" name="review_date" id="risk_review_date" class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-semibold">Mitigation Plan</span>
                        <textarea name="mitigation_plan" id="risk_mitigation_plan" rows="4" class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
                    </label>

                    <div class="flex justify-end gap-3 pt-5 md:col-span-2">
                        <button type="button" id="cancelRiskModal" class="rounded-md border border-slate-300 px-5 py-2 font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
                        <button type="submit" class="rounded-md bg-[#346DCB] px-5 py-2 font-semibold text-white hover:bg-[#2554a3]">Save risk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const riskStoreRoute = @json(route('client.itsm.risk.store'));
        const riskUpdateTemplate = @json(route('client.itsm.risk.update', ['risk' => '__ID__']));
        const riskModal = document.getElementById('riskModal');
        const riskForm = document.getElementById('riskForm');
        const riskMethod = document.getElementById('riskMethod');
        const riskModalTitle = document.getElementById('riskModalTitle');

        function setRiskField(id, value) {
            const field = document.getElementById(id);
            if (field) field.value = value ?? '';
        }

        function openRiskModal(card = null) {
            riskForm.action = card ? riskUpdateTemplate.replace('__ID__', card.dataset.id) : riskStoreRoute;
            riskMethod.value = card ? 'PATCH' : 'POST';
            riskModalTitle.textContent = card ? 'Edit Risk' : 'Create Risk';
            setRiskField('risk_title', card?.dataset.title);
            setRiskField('risk_category', card?.dataset.category);
            setRiskField('risk_level', card?.dataset.level || 'Medium');
            setRiskField('risk_owner', card?.dataset.owner);
            setRiskField('risk_status', card?.dataset.status || 'Monitoring');
            setRiskField('risk_review_date', card?.dataset.reviewDate);
            setRiskField('risk_mitigation_plan', card?.dataset.mitigationPlan);
            riskModal.classList.remove('hidden');
            riskModal.classList.add('flex');
        }

        function closeRiskModal() {
            riskModal.classList.add('hidden');
            riskModal.classList.remove('flex');
        }

        document.getElementById('openRiskModal')?.addEventListener('click', () => openRiskModal());
        document.getElementById('closeRiskModal')?.addEventListener('click', closeRiskModal);
        document.getElementById('cancelRiskModal')?.addEventListener('click', closeRiskModal);
        riskModal?.addEventListener('click', (event) => {
            if (event.target === riskModal) closeRiskModal();
        });
        document.querySelectorAll('.edit-risk').forEach((button) => {
            button.addEventListener('click', () => openRiskModal(button.closest('article')));
        });
    </script>
</body>
</html>

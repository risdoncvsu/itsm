@php
    $items = $items ?? collect();
@endphp
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
                    <div class="mt-2 flex flex-wrap items-center justify-between gap-4">
                        <h1 class="text-5xl font-bold">Compliance Tracking</h1>
                        <button type="button" id="openComplianceModal" class="rounded-full bg-[#346DCB] px-5 py-2 font-semibold text-white transition hover:bg-[#2554a3]">Create Compliance</button>
                    </div>
                    <p class="mt-3 text-lg text-slate-600">Monitor employee completion, policy acknowledgement, and audit readiness.</p>
                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
                @endif

                @if (session('success'))
                    <div class="rounded-md bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
                @endif

                <div class="grid gap-6 xl:grid-cols-4">
                    @forelse ($items as $item)
                        @php
                            $statusColor = match ($item->status) {
                                'Urgent', 'Overdue' => 'bg-[#DC2626]',
                                'Pending Review' => 'bg-[#D97706]',
                                'Completed' => 'bg-[#16A34A]',
                                default => 'bg-[#346DCB]',
                            };
                        @endphp
                        <article
                            class="rounded-2xl bg-white p-6 text-slate-950"
                            data-id="{{ $item->id }}"
                            data-title="{{ e($item->title) }}"
                            data-audience="{{ e($item->audience) }}"
                            data-status="{{ e($item->status) }}"
                            data-progress="{{ e($item->progress) }}"
                            data-due-date="{{ $item->due_date ? \Illuminate\Support\Carbon::parse($item->due_date)->format('Y-m-d') : '' }}"
                            data-notes="{{ e($item->notes) }}"
                        >
                            <h2 class="min-h-16 text-2xl font-semibold">{{ $item->title }}</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-500">{{ $item->audience }}</p>
                            <div class="mt-8 flex items-center justify-between text-sm">
                                <span class="rounded-full {{ $statusColor }} px-3 py-1 font-medium text-white">{{ $item->status }}</span>
                                <span class="font-semibold">{{ $item->progress }}%</span>
                            </div>
                            <div class="mt-4 h-4 rounded-full bg-[#9AB3CE]">
                                <div class="h-4 rounded-full bg-[#132B52]" style="width: {{ $item->progress }}%"></div>
                            </div>
                            <p class="mt-4 text-sm text-slate-500">Due: {{ $item->due_date ? \Illuminate\Support\Carbon::parse($item->due_date)->format('M j, Y') : 'Not set' }}</p>
                            <button type="button" class="edit-compliance mt-8 w-full rounded-md border-2 border-slate-950 py-2 font-medium transition hover:bg-slate-950 hover:text-white">Edit</button>
                        </article>
                    @empty
                        <div class="rounded-2xl bg-white p-8 text-center text-slate-500 xl:col-span-4">No compliance items yet.</div>
                    @endforelse
                </div>
            </section>
        </main>

        <div id="complianceModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-6">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-8 text-slate-950 shadow-2xl">
                <div class="mb-6 flex items-center justify-between">
                    <h2 id="complianceModalTitle" class="text-2xl font-bold">Create Compliance Item</h2>
                    <button type="button" id="closeComplianceModal" class="text-2xl font-bold text-slate-500 hover:text-slate-950">&times;</button>
                </div>

                <form id="complianceForm" method="POST" action="{{ route('client.itsm.compliance.store') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @csrf
                    <input type="hidden" name="_method" id="complianceMethod" value="POST">

                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-semibold">Title</span>
                        <input type="text" name="title" id="compliance_title" required class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Audience</span>
                        <input type="text" name="audience" id="compliance_audience" required class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Status</span>
                        <select name="status" id="compliance_status" class="h-11 w-full rounded border border-slate-300 px-3">
                            <option>Active</option>
                            <option>Urgent</option>
                            <option>Pending Review</option>
                            <option>Completed</option>
                            <option>Overdue</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Progress</span>
                        <input type="number" name="progress" id="compliance_progress" min="0" max="100" value="0" required class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold">Due Date</span>
                        <input type="date" name="due_date" id="compliance_due_date" class="h-11 w-full rounded border border-slate-300 px-3">
                    </label>

                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-semibold">Notes</span>
                        <textarea name="notes" id="compliance_notes" rows="4" class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
                    </label>

                    <div class="flex justify-end gap-3 pt-5 md:col-span-2">
                        <button type="button" id="cancelComplianceModal" class="rounded-md border border-slate-300 px-5 py-2 font-semibold text-slate-700 hover:bg-slate-100">Cancel</button>
                        <button type="submit" class="rounded-md bg-[#346DCB] px-5 py-2 font-semibold text-white hover:bg-[#2554a3]">Save compliance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const complianceStoreRoute = @json(route('client.itsm.compliance.store'));
        const complianceUpdateTemplate = @json(route('client.itsm.compliance.update', ['compliance' => '__ID__']));
        const complianceModal = document.getElementById('complianceModal');
        const complianceForm = document.getElementById('complianceForm');
        const complianceMethod = document.getElementById('complianceMethod');
        const complianceModalTitle = document.getElementById('complianceModalTitle');

        function setComplianceField(id, value) {
            const field = document.getElementById(id);
            if (field) field.value = value ?? '';
        }

        function openComplianceModal(card = null) {
            complianceForm.action = card ? complianceUpdateTemplate.replace('__ID__', card.dataset.id) : complianceStoreRoute;
            complianceMethod.value = card ? 'PATCH' : 'POST';
            complianceModalTitle.textContent = card ? 'Edit Compliance Item' : 'Create Compliance Item';
            setComplianceField('compliance_title', card?.dataset.title);
            setComplianceField('compliance_audience', card?.dataset.audience || 'All Employees');
            setComplianceField('compliance_status', card?.dataset.status || 'Active');
            setComplianceField('compliance_progress', card?.dataset.progress || '0');
            setComplianceField('compliance_due_date', card?.dataset.dueDate);
            setComplianceField('compliance_notes', card?.dataset.notes);
            complianceModal.classList.remove('hidden');
            complianceModal.classList.add('flex');
        }

        function closeComplianceModal() {
            complianceModal.classList.add('hidden');
            complianceModal.classList.remove('flex');
        }

        document.getElementById('openComplianceModal')?.addEventListener('click', () => openComplianceModal());
        document.getElementById('closeComplianceModal')?.addEventListener('click', closeComplianceModal);
        document.getElementById('cancelComplianceModal')?.addEventListener('click', closeComplianceModal);
        complianceModal?.addEventListener('click', (event) => {
            if (event.target === complianceModal) closeComplianceModal();
        });
        document.querySelectorAll('.edit-compliance').forEach((button) => {
            button.addEventListener('click', () => openComplianceModal(button.closest('article')));
        });
    </script>
</body>
</html>

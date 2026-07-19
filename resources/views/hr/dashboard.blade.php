<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora HR | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0B1E3D] text-white">
    <main class="mx-auto flex min-h-screen max-w-5xl items-center px-6 py-12">
        <section class="w-full rounded-3xl bg-white p-10 text-slate-950 shadow-2xl">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#346DCB]">Human Resources</p>
            <h1 class="mt-3 text-4xl font-bold">Welcome, {{ $employeeName }}</h1>
            <p class="mt-4 text-lg text-slate-600">You are signed in to the Nexora HR workspace.</p>
            @if ($employeeEmail)
                <p class="mt-2 text-sm text-slate-500">{{ $employeeEmail }}</p>
            @endif
        </section>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <!-- The handful of things Tailwind utilities genuinely can't express
         (webkit autofill pseudo-state, custom select caret) stay as raw CSS -->
    <style>
        .search-box input:-webkit-autofill,
        .search-box input:-webkit-autofill:hover,
        .search-box input:-webkit-autofill:focus,
        .search-box input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #0B1E3D inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 9999s ease-in-out 0s;
            color: #fff !important;
            font-size: 11px !important;
        }

        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M3.204 5h9.592L8 10.481 3.204 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px;
        }
    </style>
</head>

<body class="font-sans bg-[#18386d] text-white m-0 p-0">

    <!-- =====================================================
            TOP NAVBAR
        ====================================================== -->
    <header class="w-full h-[150px] bg-[#132B52] flex items-center justify-between pl-[1px] pr-[5px] border-b border-white/5 shadow-[0_1px_0_rgba(255,255,255,.03)_inset] sticky top-0 z-[1000]">

        <!-- Left a-->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" class="h-[86px] w-auto object-contain block" alt="Header Logo">
        </div>

        <div class="flex items-center gap-7">
            <nav class="flex items-center gap-px">

                <div class="relative group">
                    <a href="/dashboard" class="text-white no-underline text-xl py-2.5 px-[18px] flex items-center gap-2 rounded-full transition-all duration-250 hover:text-[#66A6FF] hover:bg-[#1B3A6B] hover:-translate-y-px hover:font-bold active:scale-[.97]">
                        Dashboard
                    </a>
                </div>

                <div class="relative group">
                    <a href="#" class="text-white no-underline text-xl py-2.5 px-[18px] flex items-center gap-2 rounded-full transition-all duration-250 hover:text-[#66A6FF] hover:bg-[#1B3A6B] hover:-translate-y-px hover:font-bold active:scale-[.97]">
                        Workforce
                        <svg class="w-3.5 h-3.5 opacity-80 transition-transform duration-300 origin-center group-hover:rotate-180 group-hover:opacity-100" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <div class="absolute top-[120%] left-1/2 -translate-x-1/2 translate-y-2.5 w-[220px] bg-[#132B52] rounded-[18px] shadow-[0_20px_45px_rgba(0,0,0,.25),inset_0_1px_0_rgba(21,21,21,.7)] p-2.5 opacity-0 invisible transition-all duration-300 z-[999] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                        <a href="{{ route('employees.index') }}" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Employee List</a>
                        <a href="{{ route('departments.index') }}" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Department List</a>
                    </div>
                </div>

                <div class="relative group">
                    <a href="{{ route('onboarding.step1') }}" class="text-white no-underline text-xl py-2.5 px-[18px] flex items-center gap-2 rounded-full transition-all duration-250 hover:text-[#66A6FF] hover:bg-[#1B3A6B] hover:-translate-y-px hover:font-bold active:scale-[.97]">
                        Employee Onboarding
                    </a>
                </div>

                <div class="relative group">
                    <a href="#" class="text-white no-underline text-xl py-2.5 px-[18px] flex items-center gap-2 rounded-full transition-all duration-250 hover:text-[#66A6FF] hover:bg-[#1B3A6B] hover:-translate-y-px hover:font-bold active:scale-[.97]">
                        Reports and Analytics
                        <svg class="w-3.5 h-3.5 opacity-80 transition-transform duration-300 origin-center group-hover:rotate-180 group-hover:opacity-100" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <div class="absolute top-[120%] left-1/2 -translate-x-1/2 translate-y-2.5 w-[220px] bg-[#132B52] rounded-[18px] shadow-[0_20px_45px_rgba(0,0,0,.25),inset_0_1px_0_rgba(21,21,21,.7)] p-2.5 opacity-0 invisible transition-all duration-300 z-[999] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                        <a href="/reports-analytics/attendance-overview" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Attendance Record</a>
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Leave Record</a>
                    </div>
                </div>

                <div class="relative group">
                    <a href="#" class="text-white no-underline text-xl py-2.5 px-[18px] flex items-center gap-2 rounded-full transition-all duration-250 hover:text-[#66A6FF] hover:bg-[#1B3A6B] hover:-translate-y-px hover:font-bold active:scale-[.97]">
                        Employee Management
                        <svg class="w-3.5 h-3.5 opacity-80 transition-transform duration-300 origin-center group-hover:rotate-180 group-hover:opacity-100" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <div class="absolute top-[120%] left-1/2 -translate-x-1/2 translate-y-2.5 w-[220px] bg-[#132B52] rounded-[18px] shadow-[0_20px_45px_rgba(0,0,0,.25),inset_0_1px_0_rgba(21,21,21,.7)] p-2.5 opacity-0 invisible transition-all duration-300 z-[999] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Leave Management</a>
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Resignation Management</a>
                      
                    </div>
                </div>

            </nav>

            <!-- Profile dropdown with Logout -->
            <div class="relative group mr-[15px]">
                <div class="w-11 h-11 rounded-full grid place-items-center bg-white/[.06] shadow-[inset_0_0_0_1px_rgba(255,255,255,.06)] cursor-pointer" aria-label="Profile">
                    <svg class="w-10 h-10" viewBox="0 0 36 36" fill="none">
                        <circle cx="18" cy="18" r="17" fill="white" opacity=".97"/>
                        <circle cx="18" cy="13" r="5.2" fill="#223B63"/>
                        <path d="M8.8 28.3C10.7 23.8 14.1 21.7 18 21.7C21.9 21.7 25.3 23.8 27.2 28.3" fill="#223B63"/>
                    </svg>
                </div>

                <div class="absolute top-[120%] right-0 left-auto translate-y-2.5 w-[160px] bg-[#132B52] rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,.25),inset_0_1px_0_rgba(21,21,21,.7)] p-2 opacity-0 invisible transition-all duration-300 z-[999] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left bg-none border-none cursor-pointer">
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                               class="flex items-center gap-2 no-underline text-[#FFB4B4] py-2.5 px-3 rounded-[10px] text-[13px] font-semibold transition-all duration-200 hover:bg-[#2c1414] hover:text-[#ff6b6b]">
                                <svg class="w-[15px] h-[15px]" viewBox="0 0 24 24" fill="none">
                                    <path d="M15 17l5-5-5-5M20 12H9M13 5H7a2 2 0 00-2 2v10a2 2 0 002 2h6"
                                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Logout
                            </a>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="w-[96.82%] max-w-[1859px] mx-auto">

        <form method="GET" action="{{ route('departments.show', request()->route('slug')) }}" class="flex justify-start items-center gap-5 py-2.5">

            <div class="search-box w-[487px] h-[45px] bg-[#0B1E3D] rounded-lg flex items-center px-3 opacity-70">
                <i class="fa-solid fa-magnifying-glass text-[#9db5db] mr-2 text-[0.6875rem]"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employees by ID or name"
                    class="w-full h-full bg-transparent border-none outline-none text-white text-[0.6875rem] placeholder:text-[#93abd3]">
            </div>

            <div class="relative w-[180px] flex-none">
                <select
                    name="sort"
                    onchange="this.form.submit()"
                    class="filter-select w-[180px] h-[45px] bg-[#0B1E3D] opacity-70 text-[#93abd3] border-none outline-none rounded-lg pl-3.5 pr-8 text-[0.6875rem] cursor-pointer">

                    <option value="">Default</option>

                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                        Name (A-Z)
                    </option>

                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                        Name (Z-A)
                    </option>

                    <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>
                        Employee ID (Ascending)
                    </option>

                    <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>
                        Employee ID (Descending)
                    </option>

                    <option value="position_asc" {{ request('sort') == 'position_asc' ? 'selected' : '' }}>
                        Position (A-Z)
                    </option>

                    <option value="position_desc" {{ request('sort') == 'position_desc' ? 'selected' : '' }}>
                        Position (Z-A)
                    </option>

                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                        Newest Employee
                    </option>

                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                        Oldest Employee
                    </option>

                </select>
            </div>

        </form>

        <!-- WELCOME SECTION -->
        <div class="mt-2.5 mb-5 py-2.5 px-1">
            <h1 class="text-[22px] font-bold tracking-[2px] text-white mb-1.5">{{ strtoupper($departmentName) }}</h1>
          
        </div>

        <!-- =========================
            TABLE
        ========================== -->

        <!-- Header -->
        <div class="w-full h-[47px] mx-auto mb-3 grid grid-cols-[21.5%_21.5%_21.5%_21.5%_15%] bg-[#0B1E3D] border border-white/[0.15] rounded-[10px] overflow-hidden">

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Employee ID</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Name</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Position</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Status</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white">Action</div>

        </div>

        <!-- Data -->
        <div class="w-full max-w-[1859px] mx-auto bg-[#0B1E3D] rounded-[10px] overflow-x-hidden">

            <table class="w-full table-fixed border-collapse">

                <tbody>

                    @forelse($departments as $department)

                    <tr class="border-t border-white/[0.18] transition-colors duration-[250ms] hover:bg-[#21457f]">

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">
                            @php
                                $genderClass = match(strtolower($department->gender ?? '')) {
                                    'female' => 'text-[#ff8bd2]',
                                    'male' => 'text-[#6ea9ff]',
                                    default => 'text-white',
                                };
                            @endphp
                            <i class="fa-solid fa-circle-user text-2xl {{ $genderClass }} mr-2"></i>
                            {{ '2026' . str_pad($department->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $department->first_name }} {{ $department->last_name }}</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $department->position }}</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $department->status }}</td>

                        <td class="p-4 text-[0.84375rem] text-center font-extralight w-[15%]">
                            <a href="{{ route('employees.show', $department->id) }}" class="inline-block bg-[#132B52] text-white no-underline px-[21px] py-1.5 rounded-xl text-[0.6875rem] transition-all duration-[250ms] hover:bg-[#2e5ca3] hover:-translate-y-px">
                                View
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="p-[30px] text-center text-[#b9c8e8] text-sm">
                            No employees found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>

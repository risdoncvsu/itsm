<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Font: Inter -->
    
    <style type="text/css">@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/latin-ext/opsz/normal.woff2);unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/cyrillic/opsz/normal.woff2);unicode-range:U+0301,U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/greek-ext/opsz/normal.woff2);unicode-range:U+1F00-1FFF;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/vietnamese/opsz/normal.woff2);unicode-range:U+0102-0103,U+0110-0111,U+0128-0129,U+0168-0169,U+01A0-01A1,U+01AF-01B0,U+0300-0301,U+0303-0304,U+0308-0309,U+0323,U+0329,U+1EA0-1EF9,U+20AB;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/greek/opsz/normal.woff2);unicode-range:U+0370-0377,U+037A-037F,U+0384-038A,U+038C,U+038E-03A1,U+03A3-03FF;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/latin/opsz/normal.woff2);unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;font-display:swap;}@font-face {font-family:Inter;font-style:normal;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/cyrillic-ext/opsz/normal.woff2);unicode-range:U+0460-052F,U+1C80-1C8A,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/latin-ext/opsz/italic.woff2);unicode-range:U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/greek-ext/opsz/italic.woff2);unicode-range:U+1F00-1FFF;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/cyrillic-ext/opsz/italic.woff2);unicode-range:U+0460-052F,U+1C80-1C8A,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/latin/opsz/italic.woff2);unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/vietnamese/opsz/italic.woff2);unicode-range:U+0102-0103,U+0110-0111,U+0128-0129,U+0168-0169,U+01A0-01A1,U+01AF-01B0,U+0300-0301,U+0303-0304,U+0308-0309,U+0323,U+0329,U+1EA0-1EF9,U+20AB;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/greek/opsz/italic.woff2);unicode-range:U+0370-0377,U+037A-037F,U+0384-038A,U+038C,U+038E-03A1,U+03A3-03FF;font-display:swap;}@font-face {font-family:Inter;font-style:italic;font-weight:100 900;src:url(/cf-fonts/v/inter/5.2.8/cyrillic/opsz/italic.woff2);unicode-range:U+0301,U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;font-display:swap;}</style>

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

        <form method="GET" action="{{ route('employees.index') }}" class="flex justify-start items-center gap-5 py-2.5">

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

                    <option value="department_asc" {{ request('sort') == 'department_asc' ? 'selected' : '' }}>
                        Department (A-Z)
                    </option>

                    <option value="department_desc" {{ request('sort') == 'department_desc' ? 'selected' : '' }}>
                        Department (Z-A)
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

        <!-- =========================
            TABLE
        ========================== -->

        <!-- Header -->
       <div class="w-full h-[47px] mx-auto mb-3 grid grid-cols-[21.5%_21.5%_21.5%_21.5%_15%] bg-[#0B1E3D] border border-white/[0.15] rounded-[10px] overflow-hidden">

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Employee ID</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Name</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Department</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Position</div>

            <div class="px-[18px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white">Action</div>

        </div>

        <!-- Data -->
        <div class="w-full max-w-[1859px] mx-auto bg-[#0B1E3D] rounded-[10px] overflow-x-hidden">

            <table class="w-full table-fixed border-collapse">

                <tbody>

                    @forelse($employees as $employee)

                    <tr class="border-t border-white/[0.18] transition-colors duration-[250ms] hover:bg-[#21457f]">

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">
                            @php
                                $genderClass = match(strtolower($employee->gender ?? '')) {
                                    'female' => 'text-[#ff8bd2]',
                                    'male' => 'text-[#6ea9ff]',
                                    default => 'text-white',
                                };
                            @endphp
                            <i class="fa-solid fa-circle-user text-2xl {{ $genderClass }} mr-2"></i>
                            {{ '2026' . str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $employee->first_name }} {{ $employee->last_name }}</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $employee->department }}</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[23%]">{{ $employee->position }}</td>

                        <td class="p-4 text-[0.84375rem] text-center font-extralight w-[15%]">
                            <a href="{{ route('employees.show', $employee->id) }}" class="inline-block bg-[#132B52] text-white no-underline px-[21px] py-1.5 rounded-xl text-[0.6875rem] transition-all duration-[250ms] hover:bg-[#2e5ca3] hover:-translate-y-px">
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

        {{-- =========================
             PAGINATION (13 per page)
             $employees must come from ->paginate(13) in EmployeeController@index
        ========================== --}}
        @if ($employees instanceof \Illuminate\Contracts\Pagination\Paginator || $employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="w-full flex items-center justify-between flex-wrap gap-3 mt-5 mb-8">

            <div class="text-[11.5px] text-[#93abd3]">
                @if ($employees->total() > 0)
                    Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }} employees
                @else
                    No employees to show
                @endif
            </div>

            <nav class="flex items-center gap-1.5">

                {{-- Previous --}}
                @if ($employees->onFirstPage())
                    <span class="w-9 h-9 grid place-items-center rounded-lg bg-[#0B1E3D] text-[#4c6291] cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[11px]"></i>
                    </span>
                @else
                    <a href="{{ $employees->previousPageUrl() }}"
                       class="w-9 h-9 grid place-items-center rounded-lg bg-[#0B1E3D] text-white transition-colors duration-200 hover:bg-[#2e5ca3]">
                        <i class="fa-solid fa-chevron-left text-[11px]"></i>
                    </a>
                @endif

                {{-- Page numbers --}}
                @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                    @if ($page == $employees->currentPage())
                        <span class="w-9 h-9 grid place-items-center rounded-lg bg-[#2D7EFF] text-white text-[12px] font-semibold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 grid place-items-center rounded-lg bg-[#0B1E3D] text-[#C9DAF8] text-[12px] transition-colors duration-200 hover:bg-[#2e5ca3] hover:text-white">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($employees->hasMorePages())
                    <a href="{{ $employees->nextPageUrl() }}"
                       class="w-9 h-9 grid place-items-center rounded-lg bg-[#0B1E3D] text-white transition-colors duration-200 hover:bg-[#2e5ca3]">
                        <i class="fa-solid fa-chevron-right text-[11px]"></i>
                    </a>
                @else
                    <span class="w-9 h-9 grid place-items-center rounded-lg bg-[#0B1E3D] text-[#4c6291] cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[11px]"></i>
                    </span>
                @endif

            </nav>

        </div>
        @endif

    </div>

</body>

</html>
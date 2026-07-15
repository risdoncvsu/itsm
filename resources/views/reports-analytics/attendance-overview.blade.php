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

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 500;
            background: rgba(255,255,255,.06);
            color: #93abd3;
        }
    </style>
</head>

<body class="font-sans bg-[#18386d] text-white m-0 p-0">

    <!-- =====================================================
            TOP NAVBAR
        ====================================================== -->
    <header class="w-full h-[150px] bg-[#132B52] flex items-center justify-between pl-[1px] pr-[5px] border-b border-white/5 shadow-[0_1px_0_rgba(255,255,255,.03)_inset] sticky top-0 z-[1000]">
 
        <!-- Left -->
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
                        Leave Management
                    </a>
                    <div class="absolute top-[120%] left-1/2 -translate-x-1/2 translate-y-2.5 w-[220px] bg-[#132B52] rounded-[18px] shadow-[0_20px_45px_rgba(0,0,0,.25),inset_0_1px_0_rgba(21,21,21,.7)] p-2.5 opacity-0 invisible transition-all duration-300 z-[999] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Placeholder 1</a>
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Placeholder 2</a>
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Placeholder 3</a>
                    </div>
                </div>
 
            </nav>
 
            <div class="w-11 h-11 mr-[15px] rounded-full grid place-items-center bg-white/[.06] shadow-[inset_0_0_0_1px_rgba(255,255,255,.06)] " aria-label="Profile">
                <svg class="w-10 h-10" viewBox="0 0 36 36" fill="none">
                    <circle cx="18" cy="18" r="17" fill="white" opacity=".97"/>
                    <circle cx="18" cy="13" r="5.2" fill="#223B63"/>
                    <path d="M8.8 28.3C10.7 23.8 14.1 21.7 18 21.7C21.9 21.7 25.3 23.8 27.2 28.3" fill="#223B63"/>
                </svg>
            </div>
        </div>
    </header>

    <div class="w-[96.82%] max-w-[1859px] mx-auto">


    <p class="text-[24px] text-[#FFFFFF] fw-500 mt-1 leading-relaxed max-w-[900px]">
               Attendance Overview
            </p>

        <!-- Total Employees stat -->
         <div class="grid grid-cols-5 gap-4 mt-4 mb-4">
        <div class="mt-4 mb-1 w-[353px] h-[134px] bg-[#0B1E3D] rounded-[20px] border border-white/[0.05] px-4 flex items-center gap-3">
            <div class="w-[39px] h-[39px] rounded-xl bg-white/[.05] flex items-center justify-center flex-none">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                    <circle cx="9" cy="10" r="3" stroke="#DCEBFF" stroke-width="1.8"/>
                    <circle cx="16.3" cy="11.2" r="2.4" stroke="#DCEBFF" stroke-width="1.8"/>
                    <path d="M4.8 18.4C6 15.8 7.9 14.7 10.1 14.7C12.3 14.7 14.1 15.8 15.3 18.4" stroke="#DCEBFF" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M15.4 18.2C16 16.8 17.2 16.1 18.4 16.1C19.5 16.1 20.4 16.5 21 17.4" stroke="#DCEBFF" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <div class="text-[11.9px] text-[#E7F0FF]">Total Employees</div>
                <div class="text-[22.2px] font-bold leading-none mt-0.5 employee-counter" data-target="{{ $employeeCount ?? (method_exists($employees, 'total') ? $employees->total() : count($employees)) }}">0</div>
            </div>
        </div>

        <div class="mt-4 mb-1 w-[353px] h-[134px] bg-[#0B1E3D] rounded-[20px] border border-white/[0.05] px-4 flex items-center gap-3">
            <div class="w-[39px] h-[39px] rounded-xl bg-green-500/20 flex items-center justify-center flex-none">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" stroke="#16A34A" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 5.5V12H18" stroke="#16A34A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-[11.9px] text-[#E7F0FF]">Present Days</div>
                <div class="text-[22.2px] font-bold leading-none mt-0.5 employee-counter" data-target="{{ $employeeCount ?? (method_exists($employees, 'total') ? $employees->total() : count($employees)) }}">0</div>
            </div>
        </div>

        <div class="mt-4 mb-1 w-[353px] h-[134px] bg-[#0B1E3D] rounded-[20px] border border-white/[0.05] px-4 flex items-center gap-3">
            <div class="w-[39px] h-[39px] rounded-xl bg-[#D97706]/20 flex items-center justify-center flex-none">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" stroke="#D97706" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 5.5V12H18" stroke="#D97706" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-[11.9px] text-[#E7F0FF]">Late</div>
                <div class="text-[22.2px] font-bold leading-none mt-0.5 employee-counter" data-target="{{ $employeeCount ?? (method_exists($employees, 'total') ? $employees->total() : count($employees)) }}">0</div>
            </div>
        </div>

        <div class="mt-4 mb-1 w-[353px] h-[134px] bg-[#0B1E3D] rounded-[20px] border border-white/[0.05] px-4 flex items-center gap-3">
            <div class="w-[39px] h-[39px] rounded-xl bg-[#DC2626]/20 flex items-center justify-center flex-none">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" stroke="#DC2626" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 5.5V12H18" stroke="#DC2626" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="text-[11.9px] text-[#E7F0FF]">Absent</div>
                <div class="text-[22.2px] font-bold leading-none mt-0.5 employee-counter" data-target="{{ $employeeCount ?? (method_exists($employees, 'total') ? $employees->total() : count($employees)) }}">0</div>
            </div>
        </div>

        <div class="mt-4 mb-1 w-[353px] h-[134px] bg-[#0B1E3D] rounded-[20px] border border-white/[0.05] px-4 flex items-center gap-3">
    <div class="w-[39px] h-[39px] rounded-xl bg-[#0EA5E9]/20 flex items-center justify-center flex-none">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
            <path d="M3 10H21M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="#0EA5E9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div>
        <div class="text-[11.9px] text-[#E7F0FF]">On Leave</div>
        <div class="text-[22.2px] font-bold leading-none mt-0.5 employee-counter" data-target="{{ $employeeCount ?? (method_exists($employees, 'total') ? $employees->total() : count($employees)) }}">0</div>
    </div>
</div>
        </div>

        
        

        <div class="w-full bg-[#0B1E3D] rounded-[14px] border border-white/[0.05] px-5 py-4 mb-4 flex items-center justify-between gap-4 flex-wrap">

    <form method="GET" action="{{ route('employees.index') }}" class="flex items-center gap-3 flex-wrap" id="filterForm">
        <div class="relative w-[220px]">
            <select name="department" class="filter-select w-full h-[45px] bg-[#132B52] text-[#C9DAF8] border-none outline-none rounded-lg pl-3.5 pr-8 text-[0.6875rem] cursor-pointer">
                <option value="">All Departments</option>
                @foreach(($departments ?? collect($employees)->pluck('department')->unique()->filter()->values()) as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="toolbar-btn bg-[#132B52] text-[#C9DAF8] hover:bg-[#1B3A6B] hover:text-white">
            <i class="fa-solid fa-filter text-[0.65rem]"></i> Filter
        </button>
    </form>

    <button type="button" id="exportBtn" class="toolbar-btn bg-[#0EA5E9] text-white hover:bg-[#0284c7] hover:-translate-y-px">
        <i class="fa-solid fa-file-export text-[0.65rem]"></i> Export
    </button>
</div>

        <!-- =========================
            TABLE
        ========================== -->

        <!-- Header -->
       <div class="w-full h-[47px] mx-auto mb-3 grid grid-cols-[18%_11%_9%_9%_9%_9%_12%_11%_12%] bg-[#0B1E3D] border border-white/[0.15] rounded-[10px] overflow-hidden">

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Employee</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Department</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Present Days</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Late Days</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Absent Days</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">On Leave</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Attendance %</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white border-r border-white/[0.15]">Status</div>

            <div class="px-[10px] py-[15px] text-center text-[11.9px] font-light uppercase tracking-wide text-white">Action</div>

        </div>

        <!-- Data -->
        <div class="w-full max-w-[1859px] mx-auto bg-[#0B1E3D] rounded-[10px] overflow-x-hidden">

            <table class="w-full table-fixed border-collapse">

                <tbody>

                    @forelse($employees as $employee)

                    <tr class="border-t border-white/[0.18] transition-colors duration-[250ms] hover:bg-[#21457f]">

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[18%]">
                            @php
                                $genderClass = match(strtolower($employee->gender ?? '')) {
                                    'female' => 'text-[#ff8bd2]',
                                    'male' => 'text-[#6ea9ff]',
                                    default => 'text-white',
                                };
                            @endphp
                            <i class="fa-solid fa-circle-user text-2xl {{ $genderClass }} mr-2"></i>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                            <span class="block text-[0.65rem] text-[#93abd3] font-light mt-0.5">{{ '2026' . str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[11%]">{{ $employee->department }}</td>

                        {{-- Placeholder columns: wire these up to real attendance data once available --}}
                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[9%] text-[#93abd3]">—</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[9%] text-[#93abd3]">—</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[9%] text-[#93abd3]">—</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[9%] text-[#93abd3]">—</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[12%] text-[#93abd3]">—</td>

                        <td class="p-4 text-[0.84375rem] text-center border-r border-white/[0.12] font-extralight w-[11%]">
                            <span class="status-badge">—</span>
                        </td>

                        <td class="p-4 text-[0.84375rem] text-center font-extralight w-[12%]">
                            <a href="{{ route('reports-analytics.employee-attendance', $employee->id) }}" class="inline-block bg-[#132B52] text-white no-underline px-[21px] py-1.5 rounded-xl text-[0.6875rem] transition-all duration-[250ms] hover:bg-[#2e5ca3] hover:-translate-y-px">
                                View
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="9" class="p-[30px] text-center text-[#b9c8e8] text-sm">
                            No employees found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <script>
        const employeeCounters = document.querySelectorAll('.employee-counter');

        function animateEmployeeCounter(el) {
            const target = parseInt(el.dataset.target, 10) || 0;
            const duration = 1450;
            const start = performance.now();

            function update(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(target * eased);
                el.textContent = current.toLocaleString();
                if (progress < 1) requestAnimationFrame(update);
            }

            requestAnimationFrame(update);
        }

        employeeCounters.forEach((counter) => animateEmployeeCounter(counter));
    </script>

</body>

</html>
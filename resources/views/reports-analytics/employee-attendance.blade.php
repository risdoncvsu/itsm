<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Attendance Record</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<style>
  body { font-family: 'Inter', system-ui, sans-serif; }
</style>
</head>
<body class="min-h-screen bg-[#1B3A6B] text-slate-200">

  <!-- HEADER: full width, matches the Attendance Overview page -->
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

  <!-- PAGE CONTENT: full width, edge-to-edge like the header -->
  <div class="w-full px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="mb-1 text-xs text-slate-400">
      <a href="/reports-analytics/attendance-overview" class="hover:text-slate-200">Attendance Record</a>
      <span class="mx-1">&gt;</span>
      <span class="text-sky-400">Employee Attendance</span>
    </nav>

    <!-- Profile card + Stat cards: same row, same size -->
    <div class="mt-4 mb-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

      <!-- Employee profile card (compact, same footprint as stat cards) -->
      <div class="col-span-2 flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-4 py-4 text-center ring-1 ring-white/5 sm:col-span-1" id="profile-card">
        <!-- filled by JS -->
      </div>

      <div class="flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-5 py-4 text-center ring-1 ring-white/5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sky-500/15">
          <i class="fa-regular fa-calendar-check text-sky-300"></i>
        </div>
        <div id="stat-present" class="text-2xl font-semibold leading-tight text-white">0</div>
        <div class="truncate text-xs text-slate-400">Present Days</div>
      </div>

      <div class="flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-5 py-4 text-center ring-1 ring-white/5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-500/15">
          <i class="fa-solid fa-clock text-amber-300"></i>
        </div>
        <div id="stat-late" class="text-2xl font-semibold leading-tight text-white">0</div>
        <div class="truncate text-xs text-slate-400">Late Days</div>
      </div>

      <div class="flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-5 py-4 text-center ring-1 ring-white/5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-rose-500/15">
          <i class="fa-solid fa-clock-rotate-left text-rose-300"></i>
        </div>
        <div id="stat-absent" class="text-2xl font-semibold leading-tight text-white">0</div>
        <div class="truncate text-xs text-slate-400">Absent Days</div>
      </div>

      <div class="flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-5 py-4 text-center ring-1 ring-white/5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sky-500/15">
          <i class="fa-regular fa-calendar-xmark text-sky-300"></i>
        </div>
        <div id="stat-leave" class="text-2xl font-semibold leading-tight text-white">0</div>
        <div class="truncate text-xs text-slate-400">Leave Days</div>
      </div>

      <div class="flex h-[184px] flex-col items-center justify-center gap-2 rounded-xl bg-[#0B1E3D] px-5 py-4 text-center ring-1 ring-white/5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15">
          <i class="fa-regular fa-square-check text-emerald-300"></i>
        </div>
        <div id="stat-total" class="text-2xl font-semibold leading-tight text-white">0</div>
        <div class="truncate text-xs text-slate-400">Total Days</div>
      </div>
    </div>

    <!-- Secondary info strip: Date of Joining, Employment Type, Location, Reporting Manager -->
    <div class="mb-6 flex flex-wrap items-center gap-6 rounded-xl bg-[#0B1E3D] px-6 py-4 ring-1 ring-white/5" id="info-strip">
      <!-- filled by JS -->
    </div>

    <!-- Date filter + record count -->
    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
      <button class="flex items-center gap-2 rounded-lg border border-white/10 bg-[#0B1E3D] px-3 py-2 text-sm text-slate-300 hover:border-white/20">
        <i class="fa-regular fa-calendar text-slate-500"></i>
        01 May 2026 - 31 May 2026
        <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>
      </button>
      <span id="record-count" class="text-sm text-slate-400">Total record: 0</span>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl bg-[#0B1E3D] ring-1 ring-white/5">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead>
            <tr class="border-b border-white/5 bg-[#0B1E3D] text-xs uppercase tracking-wide text-slate-400">
              <th class="px-5 py-2 font-medium">Date</th>
              <th class="px-5 py-2 font-medium">Day</th>
              <th class="px-5 py-2 font-medium">Status</th>
              <th class="px-5 py-2 font-medium">Check In</th>
              <th class="px-5 py-2 font-medium">Check Out</th>
              <th class="px-5 py-2 font-medium">Work Hours</th>
              <th class="px-5 py-2 font-medium">Remarks</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <!-- rows injected by JS -->
          </tbody>
        </table>
      </div>
    </div>

  </div>

<script>
  // -----------------------------------------------------------------------
  // Dynamic data — replace with your API response, e.g.
  // fetch(`/api/employees/${employeeId}/attendance`).then(r => r.json())
  // -----------------------------------------------------------------------
  const EMPLOYEE = {
    name: "Benhur Abalos",
    id: "EMP00123",
    role: "Software Engineer",
    email: "Benhur.Abalos@company.com",
    department: "IT Department",
    dateOfJoining: "15 Jan 2026",
    employmentType: "Fulltime",
    location: "Head Office",
    reportingManager: "Sarah Duterte",
    avatarUrl: "", // set an image URL to override the placeholder icon
  };

  const ATTENDANCE_RECORDS = Array.from({ length: 25 }, () => ({
    date: "01 May 2026",
    day: "Wed",
    status: "Present",
    checkIn: "09:02 AM",
    checkOut: "09:02 PM",
    workHours: "8h 03m",
    remarks: "Late check - in",
  }));

  function statusPillHtml(status) {
    const map = {
      Present: "bg-emerald-500/15 text-emerald-400",
      Absent: "bg-rose-500/15 text-rose-400",
      Leave: "bg-sky-500/15 text-sky-400",
      Late: "bg-amber-500/15 text-amber-400",
    };
    const classes = map[status] || "bg-slate-500/20 text-slate-400";
    return `<span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium ${classes}">${status}</span>`;
  }

  function renderProfile() {
    const el = document.getElementById("profile-card");
    const avatar = EMPLOYEE.avatarUrl
      ? `<img src="${EMPLOYEE.avatarUrl}" alt="${EMPLOYEE.name}" class="h-14 w-14 rounded-full object-cover" />`
      : `<div class="flex h-14 w-14 items-center justify-center rounded-full bg-sky-500/20">
           <i class="fa-solid fa-user text-xl text-sky-300"></i>
         </div>`;

    el.innerHTML = `
      ${avatar}
      <div class="min-w-0">
        <div class="truncate text-sm font-semibold text-white">${EMPLOYEE.name}</div>
        <div class="truncate text-xs text-slate-400">${EMPLOYEE.id} - ${EMPLOYEE.role}</div>
      </div>
      <div class="flex items-center gap-1.5 truncate text-[11px] text-slate-400">
        <i class="fa-regular fa-envelope"></i> <span class="truncate">${EMPLOYEE.email}</span>
      </div>
      <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
        <i class="fa-regular fa-building"></i> ${EMPLOYEE.department}
      </div>
    `;
  }

  function renderInfoStrip() {
    const el = document.getElementById("info-strip");
    el.innerHTML = `
      <div>
        <div class="text-xs text-slate-400">Date of Joining</div>
        <div class="mt-1 text-sm font-semibold text-white">${EMPLOYEE.dateOfJoining}</div>
      </div>

      <div class="hidden h-10 w-px bg-white/10 sm:block"></div>

      <div>
        <div class="text-xs text-slate-400">Employment Type</div>
        <div class="mt-1 text-sm font-semibold text-white">${EMPLOYEE.employmentType}</div>
      </div>

      <div class="hidden h-10 w-px bg-white/10 sm:block"></div>

      <div>
        <div class="text-xs text-slate-400">Location</div>
        <div class="mt-1 text-sm font-semibold text-white">${EMPLOYEE.location}</div>
      </div>

      <div class="hidden h-10 w-px bg-white/10 sm:block"></div>

      <div>
        <div class="text-xs text-slate-400">Reporting Manager</div>
        <div class="mt-1 text-sm font-semibold text-white">${EMPLOYEE.reportingManager}</div>
      </div>
    `;
  }

  function renderStats() {
    const presentDays = ATTENDANCE_RECORDS.filter(r => r.status === "Present").length;
    const lateDays = ATTENDANCE_RECORDS.filter(r => r.remarks?.toLowerCase().includes("late")).length;
    const absentDays = ATTENDANCE_RECORDS.filter(r => r.status === "Absent").length;
    const leaveDays = ATTENDANCE_RECORDS.filter(r => r.status === "Leave").length;
    const totalDays = ATTENDANCE_RECORDS.length;

    document.getElementById("stat-present").textContent = presentDays;
    document.getElementById("stat-late").textContent = lateDays;
    document.getElementById("stat-absent").textContent = absentDays;
    document.getElementById("stat-leave").textContent = leaveDays;
    document.getElementById("stat-total").textContent = totalDays;
    document.getElementById("record-count").textContent = `Total record: ${totalDays}`;
  }

  function renderTable() {
    const tbody = document.getElementById("table-body");

    if (ATTENDANCE_RECORDS.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="px-5 py-10 text-center text-slate-500">
            No attendance records for the selected period.
          </td>
        </tr>`;
      return;
    }

    tbody.innerHTML = ATTENDANCE_RECORDS.map((r, i) => `
      <tr class="border-b border-white/5 last:border-none hover:bg-white/[0.03] ${i % 2 === 1 ? "bg-white/[0.015]" : ""}">
        <td class="px-5 py-2 text-slate-300">${r.date}</td>
        <td class="px-5 py-2 text-slate-300">${r.day}</td>
        <td class="px-5 py-2">${statusPillHtml(r.status)}</td>
        <td class="px-5 py-2 text-slate-300">${r.checkIn}</td>
        <td class="px-5 py-2 text-slate-300">${r.checkOut}</td>
        <td class="px-5 py-2 text-slate-300">${r.workHours}</td>
        <td class="px-5 py-2 text-slate-300">${r.remarks}</td>
      </tr>
    `).join("");
  }

  renderProfile();
  renderInfoStrip();
  renderStats();
  renderTable();    
</script>

</body>
</html>
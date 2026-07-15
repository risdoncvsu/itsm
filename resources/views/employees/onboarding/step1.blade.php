<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Employee Onboarding</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

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
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Placeholder 1</a>
                        <a href="#" class="block no-underline text-[#C9DAF8] py-[11px] px-3.5 rounded-[10px] text-[13px] font-medium transition-all duration-200 hover:bg-[#f3f6fb] hover:text-[#2D7EFF]">Placeholder 2</a>
                       
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
 
<body class="bg-[#1B3A6B] min-h-screen font-sans">

  <div class="pt-[140px]">
    <!-- Employee Onboarding Content -->

<div class="max-w-6xl mx-auto">

   <!-- Title -->
    <h1 class="text-white text-xl font-bold tracking-wide mb-8">EMPLOYEE ONBOARDING</h1>

    <!-- Step indicator -->
    <div class="flex items-center mb-10 max-w-5xl">
      <div class="flex items-center justify-center w-[100px] h-[100px] rounded-full bg-blue-500 text-white font-bold shadow-lg shadow-blue-500/40 z-10">1</div>
      <div class="w-40 border-t-2 border-dashed border-slate-500 mx-4-"></div>
      <div class="flex items-center justify-center w-[100px] h-[100px] rounded-full bg-[#0d1730] text-white font-bold z-10">2</div>
      <div class="w-40 border-t-2 border-dashed border-slate-500 mx-4"></div>
      <div class="flex items-center justify-center w-[100px] h-[100px] rounded-full bg-[#0d1730] text-white font-bold z-10">3</div>
    <div class="w-40 border-t-2 border-dashed border-slate-500 mx-4"></div>
      <div class="flex items-center justify-center w-[100px] h-[100px] rounded-full bg-[#0d1730] text-white font-bold z-10">4</div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">

      <!-- Left: form -->
      <div class="flex-1">
        <h2 class="text-white text-sm font-bold tracking-wide mb-4">PERSONAL INFORMATION</h2>

@if (session('error'))
    <div class="mb-4 rounded bg-red-500/20 border border-red-400 text-red-200 px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

       <form
    id="onboarding-step1-form"
    action="{{ route('onboarding.storeStep1') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-4 max-w-3xl">

    @csrf

          <!-- Name row -->
          <div class="flex items-start gap-4">

  <!-- First Name -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">First Name</label>
    <div class="relative">
     <input
    type="text"
    name="first_name"
    class="name-field w-[220px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 pr-8 outline-none focus:ring-1 focus:ring-blue-500"
      />
    </div>
  </div>

  <!-- Middle Name -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Middle Name</label>
    <div class="relative">
      <input
        type="text" name="middle_name"
        class="name-field w-[220px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 pr-8 outline-none focus:ring-1 focus:ring-blue-500"
      />
    </div>
  </div>

  <!-- Last Name -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Last Name</label>
    <div class="relative">
      <input
        type="text" name="last_name"
        class="name-field w-[220px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 pr-8 outline-none focus:ring-1 focus:ring-blue-500"
      />
    </div>
  </div>

  <!-- Suffix -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Suffix</label>
    <div class="relative">
      <select
        name="suffix"
        class="w-[118px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 outline-none focus:ring-1 focus:ring-blue-500 appearance-none"
      >
        <option class="hidden"></option>
        <option value="Jr.">Jr.</option>
        <option value="Sr.">Sr.</option>
        <option value="II">II</option>
        <option value="III">III</option>
        <option value="IV">IV</option>
        <option value="V">V</option>
      </select>
    </div>
  </div>

</div>

          <!-- Gender / Marital / Nationality row -->
       <div class="flex items-start gap-6">
            <div class="flex items-start gap-8">

  <!-- Gender -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Gender</label>
    <select  name="gender" class="w-[253px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 outline-none focus:ring-1 focus:ring-blue-500 appearance-none">
      <option class="hidden"></option>
    <option>Male</option>
      <option>Female</option>
      <option>Prefer not to say</option>
    </select>
  </div>

  <!-- Marital Status -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Marital Status</label>
    <select  name="marital_status" class="w-[253px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 outline-none focus:ring-1 focus:ring-blue-500 appearance-none">
      <option class="hidden"></option>
      <option>Single</option>
      <option>Married</option>
      <option>Widowed</option>
    </select>
  </div>

  <!-- Nationality -->
  <div>
    <label class="block text-slate-300 text-xs mb-1">Nationality</label>
    <div class="relative">
      <input
    type="text"
    name="nationality"
    class="name-field w-[253px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 pr-8 outline-none focus:ring-1 focus:ring-blue-500"
      />
    </div>
  </div>

</div>
</div>

          <!-- Address -->
          <div>
            <label class="block text-slate-300 text-xs mb-1">Address</label>
            <div class="relative">
              <input type="text"  name="address" class="w-[825px] bg-[#0d1730] text-white text-sm rounded px-3 py-2 pr-8 outline-none focus:ring-1 focus:ring-blue-500" />
           
            </div>
          </div>

          <!-- Email / Phone row -->
          <div class="flex items-start gap-6 pt-2">
            <div>
              <label class="block text-slate-300 text-xs mb-1">Email</label>
              <div class="relative">
                <input
                    type="email"
                    name="email"
                    id="email"
                    maxlength="254"
                    class="w-[452px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 py-2 pr-8 outline-none focus:ring-1 focus:ring-blue-500" />
               
              </div>
            </div>
            <div>
              <label class="block text-slate-300 text-xs mb-1">Phone Number</label>
              <div class="relative">
                <input
                    type="tel"
                    name="phone"
                    id="phone"
                    inputmode="numeric"
                    maxlength="11"
                    class="w-[253px] h-[28px] bg-[#0d1730] text-white text-sm rounded px-3 py-2 pr-8 outline-none focus:ring-1 focus:ring-blue-500" />
                
              </div>
            </div>
          </div>

          <!-- Next button -->
          <div class="pt-8">
           <button
    type="submit"
    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded shadow-lg shadow-blue-900/40 transition">
    NEXT

    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="9"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 8l4 4-4 4"/>
    </svg>
</button>
          </div>
        </form>
      </div>

      
   <script>
function previewImage(event) {

    const file = event.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e) {

        document.getElementById('imagePreview').src = e.target.result;
        document.getElementById('imagePreview').classList.remove('hidden');

        document.getElementById('placeholder').classList.add('hidden');
    };

    reader.readAsDataURL(file);
}

/*
  Prevent name-type fields (First Name, Middle Name, Last Name, Nationality)
  from starting with a lowercase letter. Once there is already a character
  in the field, lowercase letters are allowed as normal (e.g. "McDonald",
  "dela Cruz" style middle characters still work).
*/
document.querySelectorAll('.name-field').forEach(function (input) {

    input.addEventListener('keydown', function (e) {

        // Only intercept plain single-letter keys (ignore Ctrl/Cmd combos, arrows, etc.)
        if (e.ctrlKey || e.metaKey || e.altKey) return;

        const isLowercaseLetter = /^[a-z]$/.test(e.key);

        if (!isLowercaseLetter) return;

        const atStart = input.selectionStart === 0 && input.selectionEnd === 0;
        const fieldIsEmpty = input.value.length === 0;

        // Block a lowercase letter only when it would become the very first character
        if (atStart && fieldIsEmpty) {
            e.preventDefault();
        }

    });

    input.addEventListener('paste', function (e) {

        const pasted = (e.clipboardData || window.clipboardData).getData('text');

        if (pasted.length === 0) return;

        const atStart = input.selectionStart === 0 && input.selectionEnd === 0;
        const fieldIsEmpty = input.value.length === 0;

        if (atStart && fieldIsEmpty && /^[a-z]/.test(pasted)) {
            e.preventDefault();

            // Auto-capitalize the first letter of the pasted text instead of rejecting it outright
            const fixed = pasted.charAt(0).toUpperCase() + pasted.slice(1);
            input.value = fixed;
        }

    });

});

/*
  Phone Number field:
  - digits only (no letters, symbols, spaces)
  - hard cap of 11 digits (e.g. 09171234567)
*/
const phoneInput = document.getElementById('phone');

phoneInput.addEventListener('keydown', function (e) {

    const allowedKeys = [
        "Backspace","Delete","Tab","Escape","Enter",
        "ArrowLeft","ArrowRight","ArrowUp","ArrowDown","Home","End"
    ];

    if (allowedKeys.includes(e.key)) return;

    if (e.ctrlKey || e.metaKey) return;

    const isDigit = /^[0-9]$/.test(e.key);

    // Block non-digit keys entirely
    if (!isDigit) {
        e.preventDefault();
        return;
    }

    // Block extra digits once 11 digits are already entered
    // (unless there's a selection being replaced)
    const hasSelection = phoneInput.selectionStart !== phoneInput.selectionEnd;

    if (!hasSelection && phoneInput.value.length >= 11) {
        e.preventDefault();
    }

});

phoneInput.addEventListener('input', function () {

    // Backup sanitizer: strip non-digits and enforce 11-digit cap
    // (covers autofill, voice input, etc.)
    let cleaned = phoneInput.value.replace(/[^0-9]/g, '');

    if (cleaned.length > 11) {
        cleaned = cleaned.slice(0, 11);
    }

    if (phoneInput.value !== cleaned) {
        phoneInput.value = cleaned;
    }

});

phoneInput.addEventListener('paste', function (e) {

    e.preventDefault();

    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    const digitsOnly = pasted.replace(/[^0-9]/g, '');

    const start = phoneInput.selectionStart;
    const end = phoneInput.selectionEnd;
    const current = phoneInput.value;

    let result = current.slice(0, start) + digitsOnly + current.slice(end);

    if (result.length > 11) {
        result = result.slice(0, 11);
    }

    phoneInput.value = result;

});

/*
  Email field:
  - standard max length of 254 characters (RFC 5321 practical limit),
    enforced both via the maxlength attribute and here as a backup
    in case maxlength is ever removed or bypassed.
*/
const emailInput = document.getElementById('email');
const EMAIL_MAX_LENGTH = 254;

emailInput.addEventListener('input', function () {

    if (emailInput.value.length > EMAIL_MAX_LENGTH) {
        emailInput.value = emailInput.value.slice(0, EMAIL_MAX_LENGTH);
    }

});
</script>

</body>
</html>
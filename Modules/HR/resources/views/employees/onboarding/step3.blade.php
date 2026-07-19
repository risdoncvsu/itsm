<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Employee Onboarding</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

 @include('partials.navbar')
 
<body class="bg-[#1B3A6B] min-h-screen font-sans">

  <div class="pt-[140px]">
    <!-- Employee Onboarding Content -->

<div class="max-w-6xl mx-auto">

   <!-- Title -->
    <h1 class="text-white text-xl font-bold tracking-wide mb-8">EMPLOYEE ONBOARDING</h1>
    @include('partials.onboarding-stepper', ['currentStep' => 3])

<div class="flex flex-col lg:flex-row gap-12">

      <!-- Left: form -->
      <div class="flex-1">
        <h2 class="text-white text-sm font-bold tracking-wide mb-6">
    REQUIRED DOCUMENTS
</h2>

<form
    action="{{ route('onboarding.storeStep3') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6">

    @csrf

    <!-- Birth Certificate -->
    <div>
        <label class="block text-slate-300 text-xs mb-1">
            Birth Certificate
        </label>

        <div class="relative">
           <input type="file" name="birth_certificate"
                class="w-[1335px] h-[45px] bg-[#0D1730] text-white text-sm rounded px-3 outline-none cursor-pointer"
            />
        </div>
    </div>

    <!-- Curriculum Vitae -->
    <div>
        <label class="block text-slate-300 text-xs mb-1">
            Curriculum Vitae
        </label>

        <div class="relative">
            <input type="file" name="curriculum_vitae"
                class="w-[1335px] h-[45px] bg-[#0D1730] text-white text-sm rounded px-3 outline-none cursor-pointer"
            />
        </div>
    </div>

    <!-- Valid ID -->
    <div>
        <label class="block text-slate-300 text-xs mb-1">
            Valid ID
        </label>

        <div class="relative">
           <input type="file" name="valid_id"
                class="w-[1335px] h-[45px] bg-[#0D1730] text-white text-sm rounded px-3 outline-none cursor-pointer"
            />
        </div>
    </div>

    <!-- Navigation Buttons -->
    <!-- Back Button -->
    <a href="{{ route('onboarding.step2') }}"
   class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold px-6 py-2.5 rounded shadow transition">
    BACK
</a>

    <!-- Next Button -->
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

     

    </div>
  </div>
  


    </div>

   </div>

</body>
</html>

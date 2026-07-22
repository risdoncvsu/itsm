@php
    $sections = collect($layout['sections'] ?? [])->keyBy('id');
    $enabledSections = collect($layout['sections'] ?? [])->filter(fn (array $section): bool => (bool) ($section['enabled'] ?? false));
    $hero = $sections->get('hero', []);
    $listingsSection = $sections->get('featured_listings', []);
    $promo = $sections->get('promo', []);
    $benefits = $sections->get('benefits', []);
    $store = $company->ecommerce_slug;
    $storefrontUrl = route('ecommerce.home', ['store' => $store]);
    $logoUrl = !empty($layout['logo_path']) ? (str_starts_with($layout['logo_path'], 'Modules/') ? Vite::asset($layout['logo_path']) : asset('storage/'.$layout['logo_path'])) : ($company->logoUrl() ?: asset('ecommerce/Nexora_Logo.png'));

    $storefrontCompany = request()->attributes->get('ecommerce_company');
    $storefrontName = $storefrontCompany?->company_name ?: ($layout['brand_name'] ?? 'Nexora Store');
    $storefrontVisitKey = 'storefront_visited_'.($storefrontCompany?->ecommerce_slug ?: 'store');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <title>{{ $layout['brand_name'] ?? $storefrontName }} | {{ $layout['tagline'] ?? 'Nexora Storefront' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '{{ $layout['primary_color'] ?? '#ff6b00' }}', hover: '#e56000', glow: '{{ $layout['primary_color'] ?? '#ff6b00' }}80' },
                        accent: '{{ $layout['accent_color'] ?? '#f59e0b' }}',
                        dark: { bg: '#050505', surface: '#121212' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        };
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #050505;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Ambient Radial Light Blurs */
        .ambient-light-1 {
            position: fixed;
            top: -20%;
            left: -20%;
            width: 70vw;
            height: 70vw;
            background: radial-gradient(circle, {{ $layout['primary_color'] ?? '#ff6b00' }}59 0%, transparent 65%);
            z-index: -1;
            pointer-events: none;
            animation: floatPulse1 20s ease-in-out infinite;
        }

        .ambient-light-2 {
            position: fixed;
            top: 35%;
            right: -20%;
            width: 80vw;
            height: 80vw;
            background: radial-gradient(circle, {{ $layout['accent_color'] ?? '#990000' }}66 0%, transparent 65%);
            z-index: -1;
            pointer-events: none;
            animation: floatPulse2 25s ease-in-out infinite;
        }

        @keyframes floatPulse1 {
            0% { opacity: 0.3; transform: translate(0, 0) scale(0.8); }
            33% { opacity: 0.8; transform: translate(25vw, 15vh) scale(1.2); }
            66% { opacity: 0.4; transform: translate(-10vw, 30vh) scale(0.9); }
            100% { opacity: 0.3; transform: translate(0, 0) scale(0.8); }
        }

        @keyframes floatPulse2 {
            0% { opacity: 0.8; transform: translate(0, 0) scale(1.1); }
            33% { opacity: 0.3; transform: translate(-25vw, -15vh) scale(0.8); }
            66% { opacity: 0.7; transform: translate(15vw, -25vh) scale(1.3); }
            100% { opacity: 0.8; transform: translate(0, 0) scale(1.1); }
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--tw-color-primary); }

        @keyframes spinFastOnce { 0% { transform: rotate(0deg); } 100% { transform: rotate(720deg); } }
        .animate-spin-fast { animation: spinFastOnce 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes slideTextOut { 0% { max-width: 0; opacity: 0; padding-left: 0; } 100% { max-width: 400px; opacity: 1; padding-left: 1.5rem; } }
        .animate-slide-text { animation: slideTextOut 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; animation-delay: 0.8s; overflow: hidden; white-space: nowrap; opacity: 0; max-width: 0; }
    </style>

    @vite('Modules/E-Commerce/Techforge/resources/css/liquidglass.css')
</head>
<body class="relative antialiased selection:bg-primary selection:text-white">
    @if (isset($preview) && $preview)
        <div class="sticky top-0 z-[100] bg-amber-500/10 backdrop-blur-md border-b border-amber-500/20 px-5 py-2.5 text-center text-xs tracking-wider font-bold text-amber-400 uppercase shadow-[0_4px_30px_rgba(0,0,0,0.1)]">Preview mode — this draft is not public until you publish it.</div>
    @endif

    <div id="preloader" data-visit-key="{{ $storefrontVisitKey }}" class="fixed inset-0 bg-[#050505] z-[100] flex items-center justify-center transition-opacity duration-1000 ease-in-out">
        <script>
            if (!sessionStorage.getItem(@json($storefrontVisitKey))) {
                document.write(`
                    <div class="relative flex items-center justify-center">
                        <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full animate-pulse"></div>
                        <div class="flex items-center relative z-10">
                            <img src="{{ $logoUrl }}" alt="{{ $storefrontName }} logo" class="h-20 w-auto object-contain animate-spin-fast">
                            <span class="text-4xl md:text-5xl font-black text-white tracking-widest animate-slide-text">{{ $storefrontName }}</span>
                        </div>
                    </div>
                `);
            } else {
                document.write(`
                    <div class="w-16 h-16 border-4 border-white/10 border-t-primary rounded-full animate-spin shadow-[0_0_20px_rgba(255,107,0,0.3)]"></div>
                `);
            }
        </script>
    </div>

    <div class="ambient-light-1"></div>
    <div class="ambient-light-2"></div>

    <x-navbar :storefrontName="$storefrontName" :store="$store" :logoUrl="$logoUrl" :layout="$layout" />

    <div class="pt-[140px] lg:pt-[180px]">
    @foreach ($enabledSections as $section)
        @if ($section['id'] === 'hero')
            <main class="relative pb-0 overflow-hidden flex flex-col items-center justify-start mb-20">
                <div class="relative w-full max-w-7xl mx-auto px-6 z-20 flex flex-col lg:flex-row items-center lg:items-center justify-between gap-12 lg:gap-8 flex-grow mb-12 lg:mb-16 mt-10">
                    <div class="w-full lg:w-1/2 flex flex-col justify-center relative z-30">
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black uppercase leading-[1.1] tracking-wider text-white mb-8 relative drop-shadow-xl">
                            {{ $section['title'] }}<br>
                            <span class="text-primary drop-shadow-[0_0_15px_rgba(255,107,0,0.5)]">{{ $section['highlight'] }}</span>
                        </h1>
                        <p class="text-gray-400 text-sm sm:text-base max-w-md leading-relaxed mb-10 font-medium">
                            {{ $section['body'] }}
                        </p>
                        @if (!empty($section['button_label']))
                        <div class="flex flex-wrap items-center gap-4 mb-16">
                            <a href="{{ $section['button_url'] ?: '#products' }}" class="bg-primary text-black px-8 py-3.5 font-black hover:bg-white transition-colors uppercase tracking-widest text-xs sm:text-sm shadow-[0_0_20px_rgba(255,107,0,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)]">
                                {{ $section['button_label'] }} &rarr;
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="w-full lg:w-1/2 flex justify-center lg:justify-end mt-4 lg:mt-0 relative group z-20">
                        <div class="absolute -inset-20 bg-gradient-to-tr from-transparent via-primary/5 to-transparent transform -skew-x-12 pointer-events-none"></div>
                        <div class="flex flex-col gap-6 w-full max-w-[500px]">
                            <div class="relative w-full aspect-[4/3] lg:aspect-[4/5] xl:aspect-square">
                                <div class="absolute inset-0 w-full h-full overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] group/card">
                                    <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-primary z-20 pointer-events-none"></div>
                                    <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-primary z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-primary z-20 pointer-events-none"></div>
                                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-primary z-20 pointer-events-none"></div>

                                    @if (!empty($section['image_path']))
                                        <img src="{{ asset('storage/'.$section['image_path']) }}" class="w-full h-full object-cover transition-opacity duration-700 opacity-90 group-hover/card:opacity-100 mix-blend-lighten">
                                    @elseif (isset($customConfigs) && count($customConfigs) > 0)
                                        <img src="{{ $customConfigs[0]->image_url ?? '' }}" class="w-full h-full object-cover transition-opacity duration-700 opacity-90 group-hover/card:opacity-100 mix-blend-lighten">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-primary/30 to-accent/20 flex items-center justify-center opacity-80 group-hover/card:opacity-100"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        @elseif ($section['id'] === 'benefits')
            <div class="w-full relative z-20 mt-auto overflow-hidden py-3 liquid-glass border-y border-white/5 backdrop-blur-xl mb-24">
                <div class="w-full h-full flex" style="mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
                    <div class="flex animate-marquee items-center w-max">
                        @for($i=0; $i<4; $i++)
                        <div class="flex items-center gap-6 sm:gap-12 px-3 sm:px-6">
                            @foreach(['benefit_one', 'benefit_two', 'benefit_three'] as $benefitKey)
                            <div class="flex items-center gap-6 sm:gap-12">
                                <span class="text-[9px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-[0.3em] whitespace-nowrap">{{ $section[$benefitKey] ?? 'BENEFIT' }}</span>
                                <div class="w-1.5 h-1.5 bg-primary transform rotate-45 shadow-[0_0_5px_rgba(255,107,0,0.5)]"></div>
                            </div>
                            @endforeach
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

        @elseif ($section['id'] === 'featured_listings')
            @if(isset($storefrontListings) && $storefrontListings->isNotEmpty())
            <section id="products" class="max-w-7xl mx-auto px-6 lg:px-8 mb-24 relative z-10 pt-10">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <p class="text-primary text-xs font-black tracking-[0.3em] uppercase mb-2">Available now</p>
                        <h2 class="text-3xl md:text-4xl font-black uppercase">{{ $section['title'] }}</h2>
                    </div>
                    <span class="text-xs text-gray-400">{{ $section['body'] }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($storefrontListings as $listing)
                    <a href="{{ route('ecommerce.listings.show', ['store' => $store, 'listing' => $listing]) }}" class="rounded-2xl p-5 bg-white/5 border border-white/10 hover:border-primary/70 transition">
                        <div class="h-36 rounded-xl bg-black/40 flex items-center justify-center overflow-hidden">
                            @if($listing->image_url)
                                <img class="max-h-full object-contain" src="{{ asset('storage/'.$listing->image_url) }}" alt="{{ $listing->name }}">
                            @endif
                        </div>
                        <h3 class="font-bold mt-4">{{ $listing->name }}</h3>
                        <p class="text-primary font-black text-xl mt-2">₱{{ number_format((float) $listing->price, 2) }}</p>
                        <p class="text-xs text-emerald-400 mt-2">{{ $listing->available_quantity }} available</p>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif

        @elseif ($section['id'] === 'promo')
            <section id="about" class="max-w-7xl mx-auto px-6 lg:px-8 mb-32 relative z-10 pt-10">
                <div class="liquid-glass backdrop-blur-2xl bg-black/40 rounded-2xl border border-white/5 p-10 md:p-16 flex flex-col items-center text-center relative overflow-hidden group hover:border-primary/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(255,107,0,0.15)]">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <h2 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight mb-6 relative z-10">{{ $section['title'] }}</h2>
                    <p class="text-gray-400 text-sm sm:text-base max-w-2xl font-medium mb-10 relative z-10">{{ $section['body'] }}</p>
                    @if (!empty($section['button_label']))
                    <a href="{{ $section['button_url'] ?: '#products' }}" class="relative z-10 bg-primary text-black px-8 py-3.5 font-black hover:bg-white transition-colors uppercase tracking-widest text-xs sm:text-sm shadow-[0_0_20px_rgba(255,107,0,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)]">
                        {{ $section['button_label'] }}
                    </a>
                    @endif
                </div>
            </section>
        @endif
    @endforeach
    </div>

    <x-footer :storefrontName="$storefrontName" :store="$store" :logoUrl="$logoUrl" />

    @vite(['Modules/E-Commerce/Techforge/resources/js/Common/Preloader.js', 'Modules/E-Commerce/Techforge/resources/js/Common/AmbientEffects.js'])
    @vite('Modules/E-Commerce/Techforge/resources/js/HomePage/Homepage.js')
</body>
</html>

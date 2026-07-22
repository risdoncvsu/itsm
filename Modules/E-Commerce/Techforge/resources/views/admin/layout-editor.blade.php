@extends('ecommerce::admin.layout', ['title' => 'Edit Storefront', 'heading' => 'Edit Storefront'])

@php
    $storefrontCompany = request()->attributes->get('ecommerce_company');
    $store = $storefrontCompany?->ecommerce_slug ?: 'techforge';

    $sections = collect($layout['sections'] ?? [])->keyBy('id');
    $hero = $sections->get('hero', []);
    $listings = $sections->get('featured_listings', []);
    $promo = $sections->get('promo', []);
    $benefits = $sections->get('benefits', []);
    $order = implode(',', array_column($layout['sections'] ?? [], 'id'));
    
    $navbar = $layout['navbar'] ?? [];
    $links = $navbar['links'] ?? [];


@endphp

@section('content')
<style>
    /* Full height override */
    body { overflow: hidden; }
    .page { width: 100% !important; max-width: 100% !important; padding: 0 !important; display: flex; flex-direction: column; height: calc(100vh - 128px); }
    .page-heading { display: none; }
    .success { margin: 16px; display: none; } /* Hide default success */

    .builder-container { display: flex; height: 100%; overflow: hidden; }
    
    /* Sidebar Styling */
    .builder-sidebar { width: 420px; min-width: 420px; background: #F4F6FA; color: #0B1E3D; overflow-y: auto; border-right: 1px solid #E2E8F0; display: flex; flex-direction: column; }
    .builder-sidebar::-webkit-scrollbar { width: 8px; }
    .builder-sidebar::-webkit-scrollbar-track { background: #F4F6FA; }
    .builder-sidebar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 4px; }
    .builder-sidebar::-webkit-scrollbar-thumb:hover { background: #7BBEF0; }

    .builder-sidebar .card { background: transparent; border: none; box-shadow: none; color: #0B1E3D; padding: 24px; margin: 0; }
    .builder-sidebar label { color: #5B7A9D; margin-top: 16px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
    .builder-sidebar input, .builder-sidebar textarea { background: #FFFFFF; border: 1px solid #E2E8F0; color: #0B1E3D; border-radius: 8px; padding: 12px; margin-top: 8px; outline: none; transition: border-color 0.2s; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02); }
    .builder-sidebar input:focus, .builder-sidebar textarea:focus { border-color: #4A9EE8; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02), 0 0 0 3px rgba(74,158,232,0.1); }
    
    .builder-sidebar .section-card { background: #FFFFFF; border: 1px solid #E2E8F0; margin-top: 24px; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); }
    .builder-sidebar .section-top { margin-bottom: 16px; }
    .builder-sidebar .section-top h3 { color: #0B1E3D; font-size: 16px; font-weight: 800; text-transform: uppercase; margin: 0; }
    .builder-sidebar .toggle { color: #5B7A9D; font-weight: 600; font-size: 12px; }
    .builder-sidebar h2 { color: #0B1E3D; font-size: 20px; font-weight: 900; margin-bottom: 8px; }
    .builder-sidebar .muted { color: #5B7A9D; font-size: 13px; line-height: 1.5; margin-bottom: 24px; }
    
    .builder-sidebar .btn-save { background: #1B6FC8; color: #FFFFFF; width: 100%; padding: 14px; margin-top: 24px; border-radius: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(27,111,200,0.2); }
    .builder-sidebar .btn-save:hover { background: #4A9EE8; color: #FFFFFF; box-shadow: 0 6px 16px rgba(74,158,232,0.3); transform: translateY(-1px); }
    .builder-sidebar .btn-publish { background: #FFFFFF; color: #0B1E3D; width: 100%; padding: 14px; margin-top: 16px; border-radius: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s; border: 1px solid #E2E8F0; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .builder-sidebar .btn-publish:hover { background: #F4F6FA; border-color: #1B6FC8; color: #1B6FC8; }
    
    .builder-sidebar .order-list { margin-top: 20px; gap: 10px; }
    .builder-sidebar .order-item { background: #FFFFFF; border: 1px solid #E2E8F0; color: #0B1E3D; border-radius: 8px; padding: 12px 16px; font-size: 13px; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; }
    .builder-sidebar .order-item button { background: #F4F6FA; color: #5B7A9D; border: 1px solid #E2E8F0; border-radius: 4px; padding: 6px 10px; cursor: pointer; transition: all 0.2s; }
    .builder-sidebar .order-item button:hover { background: #1B6FC8; color: #FFFFFF; border-color: #1B6FC8; }
    
    .builder-sidebar .publish-note { background: #D6ECFC; border-left: 4px solid #1B6FC8; color: #0B1E3D; padding: 16px; margin-top: 24px; border-radius: 0 8px 8px 0; }
    .builder-sidebar .publish-note code { color: #1B6FC8; font-weight: 700; background: rgba(27,111,200,0.1); padding: 2px 4px; border-radius: 4px; }
    
    #add-nav-link-btn { background: #FFFFFF !important; color: #1B6FC8 !important; padding: 12px !important; border: 2px dashed #7BBEF0 !important; border-radius: 8px; width: 100%; cursor: pointer; text-transform: uppercase; font-size: 11px; font-weight: 800; margin-top: 12px; transition: all 0.2s; }
    #add-nav-link-btn:hover { background: #F4F6FA !important; border-color: #1B6FC8 !important; color: #1B6FC8 !important; }

    /* Iframe Styling */
    .builder-preview { flex-grow: 1; background: #FFFFFF; position: relative; display: flex; flex-direction: column; }
    .builder-preview iframe { width: 100%; flex-grow: 1; border: none; }
    
    .preview-header { background: #FFFFFF; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); z-index: 10; relative; }
    .preview-header .title { color: #0B1E3D; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .preview-header .status { font-size: 12px; font-weight: 600; color: #5B7A9D; display: flex; align-items: center; gap: 8px; }
    
    .save-indicator { opacity: 0; transform: translateY(-10px); transition: all 0.3s; background: #16A34A; color: #FFFFFF; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 11px; text-transform: uppercase; box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
    .save-indicator.show { opacity: 1; transform: translateY(0); }
</style>

<div class="builder-container">
    <div class="builder-sidebar">
        <form id="layout-form" class="card" method="post" enctype="multipart/form-data" action="{{ route('ecommerce.admin.layout.save') }}">
            @csrf @method('put')
            
            <h2>Storefront Content</h2>
            <p class="muted">Configure the blocks of your storefront. Changes made here will update the preview instantly when saved.</p>
            
            <div class="field-grid">
                <label>Store name<input name="brand_name" value="{{ old('brand_name', $layout['brand_name']) }}" required></label>
                <label>Tagline<input name="tagline" value="{{ old('tagline', $layout['tagline']) }}"></label>
                <label style="display:flex; flex-direction:column; gap:8px;">Primary color
                    <div style="display:flex; gap:12px; align-items:center;">
                        <input type="color" name="primary_color" value="{{ old('primary_color', $layout['primary_color']) }}" style="height:36px; padding:2px; width:48px;" required>
                        <span style="font-family:monospace; color:#5B7A9D">{{ old('primary_color', $layout['primary_color']) }}</span>
                    </div>
                </label>
                <label style="display:flex; flex-direction:column; gap:8px;">Accent color
                    <div style="display:flex; gap:12px; align-items:center;">
                        <input type="color" name="accent_color" value="{{ old('accent_color', $layout['accent_color']) }}" style="height:36px; padding:2px; width:48px;" required>
                        <span style="font-family:monospace; color:#5B7A9D">{{ old('accent_color', $layout['accent_color']) }}</span>
                    </div>
                </label>
            </div>
            
            <label>Store logo (Optional)
                <input type="file" name="logo" accept="image/*" style="padding-top:10px;">
            </label>

            <input id="section-order" type="hidden" name="section_order" value="{{ old('section_order', $order) }}">

            <section class="section-card" data-section="navbar">
                <div class="section-top"><h3>Navbar & Header</h3></div>
                
                <h4 style="margin-top:20px; font-size:12px; text-transform:uppercase; color:#1B6FC8;">Search Experience</h4>
                <div class="field-grid">
                    <label>Placeholder Text<input name="search_placeholder" value="{{ old('search_placeholder', $navbar['search_placeholder'] ?? '') }}" placeholder="What are we searching?"></label>
                    <label>Trending Searches<input name="trending_searches" value="{{ old('trending_searches', $navbar['trending_searches'] ?? '') }}" placeholder="Comma separated list"></label>
                </div>
                
                <h4 style="margin-top:20px; font-size:12px; text-transform:uppercase; color:#1B6FC8;">Announcement Bar</h4>
                <label class="toggle" style="margin-bottom:10px;"><input type="checkbox" name="announcement_enabled" @checked(old('announcement_enabled', $navbar['announcement_enabled'] ?? false))> Enable Announcement Bar</label>
                <div class="field-grid">
                    <label>Message<input name="announcement_text" value="{{ old('announcement_text', $navbar['announcement_text'] ?? '') }}"></label>
                    <label>Link URL (Optional)<input name="announcement_url" value="{{ old('announcement_url', $navbar['announcement_url'] ?? '') }}"></label>
                </div>

                                <h4 style="margin-top:20px; font-size:12px; text-transform:uppercase; color:#1B6FC8;">Navigation Links</h4>
                <p class="muted" style="margin-bottom:12px;">Add up to 10 custom links or mega-menus.</p>
                <div id="nav-links-container" style="display: flex; flex-direction: column; gap: 12px;"></div>
                <button type="button" id="add-nav-link-btn" style="background: #FFFFFF; color: #1B6FC8; padding: 10px; border: 2px dashed #7BBEF0; border-radius: 8px; width: 100%; cursor: pointer; text-transform: uppercase; font-size: 11px; font-weight: bold; margin-top: 10px; transition: all 0.2s;">
                    + Add Link
                </button>
            </section>


            <section class="section-card" data-section="hero">
                <div class="section-top"><h3>Hero</h3><label class="toggle"><input type="checkbox" name="hero_enabled" @checked(old('hero_enabled', $hero['enabled'] ?? false))> Enable</label></div>
                <div class="field-grid"><label>Headline<input name="hero_title" value="{{ old('hero_title', $hero['title'] ?? '') }}"></label><label>Highlighted text<input name="hero_highlight" value="{{ old('hero_highlight', $hero['highlight'] ?? '') }}"></label></div>
                <label>Description<textarea name="hero_body" rows="3">{{ old('hero_body', $hero['body'] ?? '') }}</textarea></label>
                <div class="field-grid"><label>Button label<input name="hero_button_label" value="{{ old('hero_button_label', $hero['button_label'] ?? '') }}"></label><label>Button link<input name="hero_button_url" value="{{ old('hero_button_url', $hero['button_url'] ?? '#products') }}"></label></div>
            </section>

            <section class="section-card" data-section="featured_listings">
                <div class="section-top"><h3>Featured products</h3><label class="toggle"><input type="checkbox" name="featured_listings_enabled" @checked(old('featured_listings_enabled', $listings['enabled'] ?? false))> Enable</label></div>
                <div class="field-grid"><label>Section title<input name="listings_title" value="{{ old('listings_title', $listings['title'] ?? '') }}"></label><label>Supporting text<input name="listings_body" value="{{ old('listings_body', $listings['body'] ?? '') }}"></label></div>
            </section>

            <section class="section-card" data-section="promo">
                <div class="section-top"><h3>Promotional banner</h3><label class="toggle"><input type="checkbox" name="promo_enabled" @checked(old('promo_enabled', $promo['enabled'] ?? false))> Enable</label></div>
                <label>Headline<input name="promo_title" value="{{ old('promo_title', $promo['title'] ?? '') }}"></label>
                <label>Message<textarea name="promo_body" rows="2">{{ old('promo_body', $promo['body'] ?? '') }}</textarea></label>
                <div class="field-grid"><label>Button label<input name="promo_button_label" value="{{ old('promo_button_label', $promo['button_label'] ?? '') }}"></label><label>Button link<input name="promo_button_url" value="{{ old('promo_button_url', $promo['button_url'] ?? '#products') }}"></label></div>
            </section>

            <section class="section-card" data-section="benefits">
                <div class="section-top"><h3>Benefits</h3><label class="toggle"><input type="checkbox" name="benefits_enabled" @checked(old('benefits_enabled', $benefits['enabled'] ?? false))> Enable</label></div>
                <label>Section title<input name="benefits_title" value="{{ old('benefits_title', $benefits['title'] ?? '') }}"></label>
                <div class="field-grid"><label>Benefit 1<input name="benefit_one" value="{{ old('benefit_one', $benefits['benefit_one'] ?? '') }}"></label><label>Benefit 2<input name="benefit_two" value="{{ old('benefit_two', $benefits['benefit_two'] ?? '') }}"></label></div>
                <label>Benefit 3<input name="benefit_three" value="{{ old('benefit_three', $benefits['benefit_three'] ?? '') }}"></label>
            </section>
            
            <button type="submit" class="btn-save" id="save-btn">Save Draft</button>
        </form>

        <div class="card" style="padding-top: 0;">
            <hr style="border:0; border-top: 1px solid #E2E8F0; margin: 10px 0 30px;">
            <h2>Layout Order</h2>
            <p class="muted" style="margin-bottom:10px;">Use the arrows to control the live order of the sections.</p>
            <div id="section-order-list" class="order-list">
                @foreach (collect(explode(',', $order))->filter() as $id)
                    @php($label = ['hero' => 'Hero', 'featured_listings' => 'Featured products', 'promo' => 'Promotional banner', 'benefits' => 'Benefits'][$id] ?? $id)
                    <div class="order-item" data-id="{{ $id }}">
                        <span>{{ $label }}</span>
                        <span><button type="button" data-move="up">↑</button> <button type="button" data-move="down">↓</button></span>
                    </div>
                @endforeach
            </div>

            <div class="publish-note">
                <strong>{{ $hasPublishedLayout ? 'A live layout already exists.' : 'Your current TechForge-style storefront remains live.' }}</strong><br><br>
                Publishing replaces the public homepage for<br><code>{{ $company->ecommerce_slug }}.{{ config('ecommerce.storefront_base_domain') }}</code>
            </div>
            
            <form method="post" action="{{ route('ecommerce.admin.layout.publish') }}">
                @csrf
                <button type="submit" class="btn-publish">Publish Live</button>
            </form>
        </div>
    </div>

    <div class="builder-preview">
        <div class="preview-header">
            <span class="title">Live Preview</span>
            <div class="status">
                <span class="save-indicator" id="save-indicator">Saved Successfully</span>
                <span id="sync-status">Syncing with draft...</span>
            </div>
        </div>
        <iframe id="preview-frame" src="{{ route('ecommerce.admin.layout.preview') }}"></iframe>
    </div>
</div>

<script>
    (() => {
        const list = document.getElementById('section-order-list');
        const input = document.getElementById('section-order');
        const sync = () => {
            input.value = [...list.querySelectorAll('[data-id]')].map(item => item.dataset.id).join(',');
        };
        
        list.addEventListener('click', (event) => {
            const button = event.target.closest('[data-move]');
            if (!button) return;
            const item = button.closest('[data-id]');
            if (button.dataset.move === 'up' && item.previousElementSibling) list.insertBefore(item, item.previousElementSibling);
            if (button.dataset.move === 'down' && item.nextElementSibling) list.insertBefore(item.nextElementSibling, item);
            sync();
        });

        // AJAX Form Submission
        const form = document.getElementById('layout-form');
        const saveBtn = document.getElementById('save-btn');
        const iframe = document.getElementById('preview-frame');
        const indicator = document.getElementById('save-indicator');
        const syncStatus = document.getElementById('sync-status');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalText = saveBtn.innerText;
            saveBtn.innerText = 'Saving...';
            syncStatus.innerText = 'Saving changes...';
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST', // The method is put in _method
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Reload iframe
                    syncStatus.innerText = 'Reloading preview...';
                    iframe.src = iframe.src;
                    
                    iframe.onload = () => {
                        syncStatus.innerText = 'Synced';
                        indicator.classList.add('show');
                        setTimeout(() => indicator.classList.remove('show'), 3000);
                    };
                } else {
                    const data = await response.json();
                    alert(data.message || 'Error saving layout');
                    syncStatus.innerText = 'Error';
                }
            } catch (error) {
                alert('Network error while saving');
                syncStatus.innerText = 'Error';
            } finally {
                saveBtn.innerText = originalText;
            }
        });

        // Navigation Links Builder
        const navLinksContainer = document.getElementById('nav-links-container');
        const addNavLinkBtn = document.getElementById('add-nav-link-btn');
        let navLinks = @json($links);

        function saveNavLinksState() {
            document.querySelectorAll('#nav-links-container > div').forEach((item, index) => {
                if (navLinks[index]) {
                    const labelInput = item.querySelector(`input[name="nav_links[${index}][label]"]`);
                    const urlInput = item.querySelector(`input[name="nav_links[${index}][url]"]`);
                    const typeSelect = item.querySelector(`select[name="nav_links[${index}][type]"]`);
                    
                    if (labelInput) navLinks[index].label = labelInput.value;
                    if (urlInput) navLinks[index].url = urlInput.value;
                    if (typeSelect) navLinks[index].type = typeSelect.value;
                    
                    if (navLinks[index].type === 'mega') {
                        const pt = item.querySelector(`input[name="nav_links[${index}][promo_title]"]`);
                        const ps = item.querySelector(`input[name="nav_links[${index}][promo_subtitle]"]`);
                        const pb = item.querySelector(`input[name="nav_links[${index}][promo_button]"]`);
                        const pbu = item.querySelector(`input[name="nav_links[${index}][promo_button_url]"]`);
                        if (pt) navLinks[index].promo_title = pt.value;
                        if (ps) navLinks[index].promo_subtitle = ps.value;
                        if (pb) navLinks[index].promo_button = pb.value;
                        if (pbu) navLinks[index].promo_button_url = pbu.value;
                    }
                }
            });
        }

        function renderNavLinks() {
            navLinksContainer.innerHTML = '';
            navLinks.forEach((link, index) => {
                const item = document.createElement('div');
                item.style.cssText = "background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);";
                
                const typeOptions = `
                    <option value="simple" ${link.type === 'simple' ? 'selected' : ''}>Simple Link</option>
                    <option value="mega" ${link.type === 'mega' ? 'selected' : ''}>Mega Menu</option>
                `;
                
                let megaFields = '';
                if (link.type === 'mega') {
                    megaFields = `
                        <div class="field-grid" style="margin-top:12px;">
                            <label>Promo Title<input name="nav_links[${index}][promo_title]" value="${link.promo_title || ''}" placeholder="e.g. SUMMER SALE"></label>
                            <label>Promo Subtitle<input name="nav_links[${index}][promo_subtitle]" value="${link.promo_subtitle || ''}"></label>
                        </div>
                        <div class="field-grid">
                            <label>Promo Button<input name="nav_links[${index}][promo_button]" value="${link.promo_button || ''}"></label>
                            <label>Button Link<input name="nav_links[${index}][promo_button_url]" value="${link.promo_button_url || ''}"></label>
                        </div>
                    `;
                }

                item.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span style="font-size:11px; font-weight:bold; color:#5B7A9D; text-transform:uppercase;">Link #${index + 1}</span>
                        <button type="button" class="remove-nav-link" data-index="${index}" style="background:transparent; border:none; color:#DC2626; cursor:pointer; font-size:12px; font-weight:bold;">Remove</button>
                    </div>
                    <div class="field-grid">
                        <label>Label<input name="nav_links[${index}][label]" value="${link.label || ''}" required></label>
                        <label>Type
                            <select class="nav-type-select" data-index="${index}" name="nav_links[${index}][type]" style="background: #FFFFFF; border: 1px solid #E2E8F0; color: #0B1E3D; border-radius: 8px; padding: 12px; margin-top: 8px; width: 100%; outline: none;">
                                ${typeOptions}
                            </select>
                        </label>
                    </div>
                    <label>URL / Hash<input name="nav_links[${index}][url]" value="${link.url || ''}" required></label>
                    ${megaFields}
                `;
                
                navLinksContainer.appendChild(item);
            });

            // Bind events
            document.querySelectorAll('.remove-nav-link').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    saveNavLinksState();
                    const idx = e.target.getAttribute('data-index');
                    navLinks.splice(idx, 1);
                    renderNavLinks();
                });
            });

            document.querySelectorAll('.nav-type-select').forEach(sel => {
                sel.addEventListener('change', (e) => {
                    saveNavLinksState();
                    const idx = e.target.getAttribute('data-index');
                    navLinks[idx].type = e.target.value;
                    renderNavLinks();
                });
            });
            
            // Check max 10
            if (addNavLinkBtn) addNavLinkBtn.style.display = navLinks.length >= 10 ? 'none' : 'block';
        }

        renderNavLinks();

        if (addNavLinkBtn) {
            addNavLinkBtn.addEventListener('click', () => {
                if (navLinks.length < 10) {
                    saveNavLinksState();
                    navLinks.push({ label: 'New Link', type: 'simple', url: '#' });
                    renderNavLinks();
                }
            });
        }

    })();
</script>
@endsection

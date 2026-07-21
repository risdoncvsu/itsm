<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\Models\Order;
use Modules\Ecommerce\Models\StorefrontListing;
use Modules\Ecommerce\Support\EcommerceClientContext;

class EcommerceAdminController extends Controller
{
    public function login() { return view('ecommerce::admin.login'); }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
        if (! Auth::guard('ecommerce_admin')->attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->route('ecommerce.admin.dashboard');
    }

    public function dashboard()
    {
        $clientId = (int) app(EcommerceClientContext::class)->clientId();
        return view('ecommerce::admin.dashboard', [
            'listingCount' => StorefrontListing::count(),
            'activeListingCount' => StorefrontListing::where('status', 'active')->count(),
            'orderCount' => Order::count(),
            'bomCount' => DB::connection('manufacturing')->table('product_boms')->where('client_id', $clientId)->where('status', 'active')->count(),
            'recentListings' => StorefrontListing::latest()->take(5)->get(),
        ]);
    }

    public function listings() { return view('ecommerce::admin.listings', ['listings' => StorefrontListing::latest()->get()]); }
    public function createListing() { return view('ecommerce::admin.listing-form', ['listing' => new StorefrontListing(), 'boms' => $this->boms()]); }

    public function storeListing(Request $request): RedirectResponse
    {
        $data = $this->listingData($request);
        if ($request->hasFile('image')) $data['image_url'] = $request->file('image')->store('storefront-listings', 'public');
        StorefrontListing::create($data);
        return redirect()->route('ecommerce.admin.listings')->with('success', 'Storefront listing created.');
    }

    public function editListing(StorefrontListing $listing) { return view('ecommerce::admin.listing-form', ['listing' => $listing, 'boms' => $this->boms()]); }

    public function updateListing(Request $request, StorefrontListing $listing): RedirectResponse
    {
        $data = $this->listingData($request);
        if ($request->hasFile('image')) $data['image_url'] = $request->file('image')->store('storefront-listings', 'public');
        $listing->update($data);
        return redirect()->route('ecommerce.admin.listings')->with('success', 'Storefront listing updated.');
    }

    public function destroyListing(StorefrontListing $listing): RedirectResponse
    {
        $listing->delete();
        return redirect()->route('ecommerce.admin.listings')->with('success', 'Storefront listing removed.');
    }

    public function orders() { return view('ecommerce::admin.orders', ['orders' => Order::latest()->paginate(20)]); }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('ecommerce_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('ecommerce.admin.login');
    }

    private function boms()
    {
        return DB::connection('manufacturing')->table('product_boms')->where('client_id', app(EcommerceClientContext::class)->clientId())->where('status', 'active')->orderBy('name')->get();
    }

    private function listingData(Request $request): array
    {
        return $request->validate([
            'bom_id' => ['required', 'integer'], 'sku' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:160'], 'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'], 'status' => ['required', 'in:draft,active,archived'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}

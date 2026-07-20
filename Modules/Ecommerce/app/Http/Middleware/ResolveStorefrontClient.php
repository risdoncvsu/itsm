<?php

namespace Modules\Ecommerce\Http\Middleware;

use Modules\Ecommerce\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Modules\Ecommerce\Support\EcommerceClientContext;
use Symfony\Component\HttpFoundation\Response;

class ResolveStorefrontClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $baseDomain = strtolower((string) config('ecommerce.storefront_base_domain'));
        $host = strtolower($request->getHost());

        if ($baseDomain === '' || ! str_ends_with($host, '.'.$baseDomain)) {
            abort(404);
        }

        $slug = substr($host, 0, -strlen('.'.$baseDomain));

        if ($slug === '' || str_contains($slug, '.')) {
            abort(404);
        }

        $company = Company::query()
            ->where('ecommerce_slug', $slug)
            ->where('status', 'Active')
            ->firstOrFail();

        app(EcommerceClientContext::class)->setClientId((int) $company->id);
        URL::defaults(['store' => $slug]);
        $request->attributes->set('ecommerce_company', $company);

        return $next($request);
    }
}

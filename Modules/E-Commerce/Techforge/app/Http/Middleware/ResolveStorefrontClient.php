<?php

namespace Modules\Ecommerce\Http\Middleware;

use App\Models\Company;
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
        
        $baseHost = explode(':', $baseDomain)[0];
        $requestHost = explode(':', $host)[0];

        if ($requestHost !== $baseHost) {
            if (app()->environment('local') && in_array($requestHost, ['localhost', '127.0.0.1'])) {
                // Bypass for local OAuth callbacks
            } else {
                abort(404);
            }
        }

        $company = Company::query()
            ->where('status', 'Active')
            ->first();

        if ($company) {
            app(EcommerceClientContext::class)->setClientId((int) $company->id);
            $request->attributes->set('ecommerce_company', $company);
        }

        return $next($request);
    }
}

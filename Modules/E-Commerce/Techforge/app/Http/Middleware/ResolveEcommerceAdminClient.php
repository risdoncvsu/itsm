<?php

namespace Modules\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Ecommerce\Support\EcommerceClientContext;
use Symfony\Component\HttpFoundation\Response;

class ResolveEcommerceAdminClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('ecommerce_admin')->user();

        abort_unless($admin && $admin->isEcommerceEmployee(), 403);

        app(EcommerceClientContext::class)->setClientId((int) $admin->client_id);

        return $next($request);
    }
}

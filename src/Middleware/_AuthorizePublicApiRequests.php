<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\API\API;

class AuthorizePublicApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $clientId = $request->header('client-id');
        $clientSecret = $request->header('client-secret');

        $validationStatus = API::validateClientInputs($clientId, $clientSecret);

        if (!$validationStatus) {
            return API::respond("unauthorized", 403);
        }

        return $next($request);
    }
}

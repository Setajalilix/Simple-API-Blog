<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!app('debugbar')->isEnabled()|| !app()->bound('debugbar')) {
            return $response;
        }
        if ($response instanceof JsonResponse && $request->has('_debug')) {
            $response->setData(array_merge([
                '_debugbar'=>Arr::only(app('debugbar')->getData(), 'queries')
            ],$response->getData(true)));

        }
        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $headerKey = $request->header('X-API-KEY');

        // Optionnel : nettoyer l'entrée
        $providedKey = trim($headerKey);
        // Clé attendue (ex: encodée en base64)
        $expectedKey = env('API_SECRET_KEY');

        if (!$providedKey || !hash_equals($expectedKey, $providedKey)) {
            return response()->json(['errors' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogFilamentActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar acciones POST/PUT/PATCH/DELETE en el panel de Filament
        if (Auth::check() && str_contains($request->url(), '/admin/') && 
            in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            
            Log::create([
                'user_id' => Auth::id(),
                'operation' => strtolower($request->method()),
                'ip' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'type' => 'filament_action',
                'details' => 'Acción en panel administrativo',
            ]);
        }

        return $response;
    }
}
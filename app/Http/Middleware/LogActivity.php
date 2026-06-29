<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    // Method yang dicatat (bukan GET karena terlalu banyak)
    protected array $loggedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    // Route yang dikecualikan dari logging
    protected array $excludedRoutes = [
        'login', 'logout', 'password.*',
        '*.notifications.read',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->user() &&
            in_array($request->method(), $this->loggedMethods) &&
            !$this->isExcluded($request)
        ) {
            $this->log($request);
        }

        return $response;
    }

    protected function isExcluded(Request $request): bool
    {
        foreach ($this->excludedRoutes as $pattern) {
            if ($request->routeIs($pattern)) return true;
        }
        return false;
    }

    protected function log(Request $request): void
    {
        [$module, $action] = $this->resolveModuleAction($request);

        ActivityLog::create([
            'user_id'    => $request->user()->id,
            'module'     => $module,
            'action'     => $action,
            'payload'    => [
                'url'    => $request->fullUrl(),
                'method' => $request->method(),
                'input'  => $this->sanitizeInput($request->except([
                    'password', 'password_confirmation', '_token',
                ])),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    protected function resolveModuleAction(Request $request): array
    {
        // Ambil segmen URL untuk deteksi modul
        // misal: /admin/invoices/xxx → module: invoice, action: updated
        $segments = $request->segments();
        $module   = $segments[1] ?? 'general'; // admin/crew/client → segmen ke-2
        $method   = $request->method();

        $action = match($method) {
            'POST'   => 'created',
            'PUT',
            'PATCH'  => 'updated',
            'DELETE' => 'deleted',
            default  => 'accessed',
        };

        // Singularkan nama modul (invoices → invoice)
        $module = rtrim($module, 's');

        return [$module, $action];
    }

    protected function sanitizeInput(array $input): array
    {
        // Batasi panjang value supaya payload tidak terlalu besar
        return array_map(function ($value) {
            if (is_string($value) && strlen($value) > 500) {
                return substr($value, 0, 500) . '...';
            }
            return $value;
        }, $input);
    }
}
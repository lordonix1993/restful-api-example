<?php
namespace App\Http\Middleware;
use Closure;
/**
 * Class APIVersion
 * @package App\Http\Middleware
 */
class APIVersionSet
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        config(['app.api_version' => $guard]);
        return $next($request);
    }
}

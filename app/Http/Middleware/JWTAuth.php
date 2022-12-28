<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWTAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $this->authenticate($request);
        } catch(UnauthorizedHttpException $err) {
            $json = [
                'success'   => false,
                'message'   => 'Unauthorized.',
                'error'     => $err->getMessage(),
                'data'      => []
            ];
            return response()->json($json, 401);
        }

        return $next($request);
    }
}

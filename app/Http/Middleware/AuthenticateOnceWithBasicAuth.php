<?php

namespace Skunenieki\System\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Routing\Middleware;

class AuthenticateOnceWithBasicAuth implements Middleware
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
        return Auth::onceBasic() ?: $next($request);
    }

}

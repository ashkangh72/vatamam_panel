<?php

namespace App\Http\Middleware;

use App\Models\Redirect as RedirectModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CheckIfRedirected
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $redirect = RedirectModel::where('old_url', url()->current())
            ->select('new_url', 'http_code')
            ->first();

        if (!is_null($redirect)) {
            return Redirect::to($redirect->new_url, $redirect->http_code);
        }

        return $next($request);
    }
}

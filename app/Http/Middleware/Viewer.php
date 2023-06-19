<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Viewer as ViewerModel;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class Viewer
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
        if (!application_installed()) {
            return $next($request);
        }

        if ($request->method() == 'GET') {
            $uri = strtolower(request()->getRequestUri());
            $uriParts = explode('.', $uri);

            if (is_uploaded_file($uri) || array_key_exists(1, $uriParts)) {
                return $next($request);
            }

            $options = [];
            $agent   = new Agent();

            $options['device']    = $agent->device();
            $options['platform']  = $agent->platform();
            $options['browser']   = $agent->browser();
            $options['robot']     = $agent->robot();
            $options['method']    = request()->method();
            $options['referer']   = request()->headers->get('referer');

            ViewerModel::create([
                'ip'      => request()->ip(),
                'path'    => $uri,
                'auth'    => auth()->check(),
                'user_id' => auth()->check() ? auth()->user()->id : null,
                'options' => json_encode($options)
            ]);
        }

        return $next($request);
    }
}

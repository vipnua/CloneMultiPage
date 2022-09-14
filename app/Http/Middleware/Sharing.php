<?php

namespace App\Http\Middleware;

use App\Browser;
use Auth;
use Closure;
use Illuminate\Support\Facades\Route;

class Sharing
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

        $browser = Browser::whereUuid($request->route('uuid'))->first();
        $userId = Auth::id();

        if (!$browser || !$userId) {
            return $this->fails();
        }

        $resourceSharing = $browser->sharing()->wherePivot('user_id', $userId)->first();
        if ($resourceSharing) {
            $routeCurrent = Route::currentRouteName();
            $role = $resourceSharing->sharing->role;

            switch ($routeCurrent) {
                case 'share.showBrowser':
                    if ($role == "guest" || $role == "admin") {
                        return $next($request);
                    }
                    break;
                case 'share.updateBrowser':
                    if ($role == "admin") {
                        return $next($request);
                    }
                    break;
                case 'share.closeBrowser':
                    return $next($request);
                    break;
            }

        }
        return $this->fails();

    }

    public function fails()
    {
        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('permission'),
                'content' => res_content('empty'),
            ], res_code(401)
        );
    }
}

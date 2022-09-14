<?php

namespace App\Http\Middleware;

use App\Browser;
use Auth;
use Closure;
use Illuminate\Support\Facades\Route;

class CheckRoleForBrowser
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

        //As the owner, please continue
        if ($browser->user_id == $userId) {
            return $next($request);
        }

        $resourceSharing = $browser->sharing()->wherePivot('user_id', $userId)->first();

        if ($resourceSharing) {
            $routeCurrent = Route::currentRouteName();
            $role = $resourceSharing->sharing->role;

            switch ($routeCurrent) {
                //As the person who shares admin rights, continue.
                case 'browser.duplicate':
                    if ($role == "admin") {
                        return $next($request);
                    }
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

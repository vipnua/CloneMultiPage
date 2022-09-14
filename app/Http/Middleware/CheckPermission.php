<?php

namespace App\Http\Middleware;
use App\Group;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $routeName = Route::currentRouteName(); 
        $user = Auth::user();
        if ($user->hasRole(config('custom.role'))) return $next($request);
        
        $routeName = Route::currentRouteName();
  
        $permissions = $user->getAllPermissions()->pluck('name');
        $permissions = $permissions->map(function($permission){
            return str_replace('_', '.', $permission);
        })->toArray();

        $thisRoute  = explode('.', $routeName);
        $function  = $thisRoute[0];
        
        if (in_array($function.'.view', $permissions))  array_push($permissions, $function.'.index', $function.'.show');
        if (in_array($function.'.create', $permissions))  array_push($permissions, $function.'.store');
        if (in_array($function.'.edit', $permissions))  array_push($permissions, $function.'.update');
        if (in_array($function.'.delete', $permissions))  array_push($permissions, $function.'.destroy');

        if (!in_array($routeName, $permissions)) {
            if ($routeName == $function.'.index') {
                return abort(401);
            }
            return response()->json([
                'status' => "Bạn không có quyền vào thư mục này"
            ], 401);
        }
        return $next($request);
    }
}

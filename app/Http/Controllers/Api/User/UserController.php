<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDetailsResource as DetailsResource;

class UserController extends Controller
{
    public function details()
    {  
        /* $result = User::whereId(Auth::id())->with('browser')->first(); */

        $result = User::with(['browser' => function($query) {
            $query->select('id', 'user_id', 'uuid', 'config', 'file_name', 'directory', 'can_be_running', 'created_at');
        }])->find(Auth::id());
       
        return (new DetailsResource($result))->response()->setStatusCode(res_code(200));
    }
}

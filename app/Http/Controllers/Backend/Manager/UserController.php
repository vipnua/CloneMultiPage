<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Model\Functions;
use App\Model\PaymentMethod;
use App\Model\Plan;
use App\User;
use App\Browser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        $deleted_user = User::onlyTrashed()->withCount('browser')->latest('deleted_at')->get();
        return view('backend.customer.main', compact('func', 'deleted_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        $plan = Plan::where('default', 1)->first();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'avatar' => 1,
            'status' => 3,
            'plan_id' => $plan->id,
            'uuid' => uuid(),
        ]);
        if ($user) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Added successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        switch ($id) {
            case 'get-datatable':
                $this->authorize('viewAny', User::class);
                $users = User::select('id','name', 'email','uuid','status')->withCount('browser')->latest();
                if ($request->name_search != null) {
                    $param = $request->name_search;
                    $users = $users->where(function ($query) use ($param) {
                        $query->where('name', 'like', '%' . $param . '%')
                            ->orWhere('email', 'like', '%' . $param . '%');
                    });
                }
                if ($request->status_search != null && $request->status_search != 'all') {
                    $users = $users->where('status', $request->status_search);
                }
                return datatables($users)->make(true);
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $this->authorize('update', User::class);
        $data = $request->validated();
        if ($data['password'] == null) {
            unset($data['password']);
        }
        $user = User::find($data['id'])->update($data);
        if ($user) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Updated successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Deleted successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'ID is not found',
            ]
        );
    }

    public function userProfile($uuid)
    {
        $this->authorize('viewProfile', User::class);
        $func = Functions::where('route', 'user')->first();
        $user = User::withTrashed()->where('uuid', $uuid)->first();
        $status_delete = 1;
        $status_block = 1;
        if ($user->trashed()) {
            $status_delete = 2;
        } else {
            if ($user->status == 2) {
                $status_block = 2;
            }
        }
//        $plans = Plan::where('status',1)->get();
//        $paymentMethods = PaymentMethod::where('status',1)->get();
        $plans = Plan::all();
        $paymentMethods = PaymentMethod::all();
        return view('backend.customer.profile.main', compact('func', 'user', 'status_delete', 'status_block','plans','paymentMethods'));
    }

    public function getProfileTable(Request $request, $uuid)
    {
        $this->authorize('viewProfile', User::class);
        $user_profile = User::where('uuid', $uuid)->first();
        if ($user_profile) {
            $user_profile = User::where('uuid', $uuid)->first()->browser()->select('id','config');
            if ($request->name_search != null) {
                $user_profile = $user_profile->where('config','like', '%'.$request->name_search.'%');
            }
            if ($request->os_search != null && $request->os_search != 'all') {
                $user_profile = $user_profile->where('os','like', '%'.$request->os_search.'%');
            }
            return datatables($user_profile)->make(true);
        }
        return datatables([])->make(true);
    }

    public function userStatus(Request $request)
    {
        $status = 1;
        $this->authorize('update', User::class);
        switch ($request->status) {
            case '1':
                $status = 2;
                break;
            case '2':
                $status = 1;
                break;
            case '3':
                $status = 1;
                break;
            default:
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Status is invalid',
                    ]
                );
                break;
        }
        $user = User::find($request->id)->update([
            'status' => $status,
        ]);
        if ($user) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Changed status successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Having trouble, try again later',
            ]
        );
    }

    public function userChangeStatus(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if ($user->trashed()) {
            switch ($request->status) {
                case 'restore':
                    $user->restore();
                    return redirect()->route('bin.index');
                    break;
                case 'forcedelete':
                    $user->forceDelete();
                    return redirect()->route('bin.index');
                    break;
                default:
                    return abort(404);
                    break;
            }
        } else {
            switch ($request->status) {
                case 'delete':
                    $user->delete();
                    return redirect()->route('user.index');
                    break;
                default:
                    return abort(404);
                    break;
            }
        }
    }

    public function configProfile(Request $request)
    {
        if (json_decode($request->config) != null) {
            $browser = Browser::find($request->id)->update([
                'config' => json_decode($request->config)
            ]);
            if ($browser) {
                return response()->json(
                    [
                        'type' => 'success',
                        'title' => 'Success',
                        'content' => 'Change config successfully',
                    ]
                );
            }
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => 'Having trouble, try again later',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Your text is not a JSON',
            ]
        );
    }
}

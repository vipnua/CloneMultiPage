<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Model\Admin;
use App\Model\Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Admin::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        if (Auth::user()->hasRole(config('custom.role'))) {
            $roles = Role::query()->orderBy('id','asc')->get();
        }else {
            $roles = Role::where('name','!=',config('custom.role'))->orderBy('id','asc')->get();
        }
        return view('backend.admin.main', compact('func','roles'));
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
    public function store(AdminRequest $request)
    {
        $this->authorize('create', Admin::class);
        $data = $request->validated();
        switch ($data['gender']) {
            case '1':
                $avatar = rand(1, 5);
                break;
            case '2':
                $avatar = rand(6, 10);
                break;
            default:
                $avatar = 11;
                break;
        }
        $user = Admin::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'avatar' => $avatar,
        ]);
        if ($user) {
            $user->infor()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'birthday' => $data['birthday'],
                'address' => $data['address'],
            ]);
            $user->assignRole($data['roles']);
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
            case 'get-admin-datatable':
                $this->authorize('viewAny', Admin::class);
                $users = Admin::with('infor','roles')->get()->sortBy('infor.last_name');
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
    public function update(AdminRequest $request, $id)
    {
        $this->authorize('update', Admin::class);
        $data = $request->validated();
        $user = Admin::find($data['id']);
        if ($user->infor()->first()->gender != (Int)$data['gender']) {
            switch ($data['gender']) {
                case '1':
                    $avatar = rand(1, 5);
                    break;
                case '2':
                    $avatar = rand(6, 10);
                    break;
                default:
                    $avatar = 11;
                    break;
            }
        } else {
            $avatar = $user->avatar;
        }
        $data_update = [
            'email' => $data['email'],
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'avatar' => $avatar,
        ];
        if ($data['password'] != '') {
            $data_update = 
            array_merge($data_update, ['password' => $data['password']]);
        }
        $user->update($data_update);
        if ($user) {
            $infor = Admin::find($data['id'])->infor()->updateOrCreate(
                [
                    'admin_id' => $data['id'],
                ],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'birthday' => $data['birthday'],
                    'address' => $data['address'],
                ]);
            $user->assignRole($data['roles']);
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Edited successfully',
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
        $this->authorize('delete', Admin::class);
        $user = Admin::find($id);
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

    public function profile()
    {
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        $user = Auth::user();
        return view('backend.dashboard.profile', compact('func', 'user'));
    }

    public function postProfile(UserRequest $request)
    {
        $data = $request->validated();
        $auth = Auth::user();
        $user = $auth->update([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
        ]);
        $infor = Admin::find($auth->id)->infor()->updateOrCreate(
            [
                'user_id' => $auth->id,
            ],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'birthday' => $data['birthday'],
                'address' => $data['address'],
            ]);
        if ($infor) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Edit profile successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Infor is not found',
            ]
        );
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|max:255|password:web',
            'password' => 'required|min:6|max:255|confirmed',
        ], [
            'old_password.required' => 'Current Password is required',
            'old_password.password' => 'Current Password is not correct',
            'password.required' => 'New Password is required',
            'password.min' => 'New Password must more than 6 characters',
            'password.max' => 'New Password is too long',
            'password.confirmed' => 'Confirm Password is not correct',
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'type' => 'error',
                    'title' => 'Error',
                    'content' => $validator->errors()->all(),
                ]
            );
        }
        $user = Auth::user()->update([
            'password' => $request->password,
        ]);
        if ($user) {
            return response()->json(
                [
                    'type' => 'success',
                    'title' => 'Success',
                    'content' => 'Change Password successfully',
                ]
            );
        }
        return response()->json(
            [
                'type' => 'error',
                'title' => 'Error',
                'content' => 'Infor is not found',
            ]
        );
    }
}

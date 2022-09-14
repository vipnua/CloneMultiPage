<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\RoleRequest;
use App\Model\Functions;
use App\Model\Groupermission;
use App\Model\RoleSecond;
use App\Model\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        switch ($id) {
            case 'get-roles-datatable':
                $user_assign = Admin::with('roles','infor')->get()->sortBy('infor.last_name');
                return datatables($user_assign)->make(true);
                break;
            default:
                $this->authorize('viewAssign', RoleSecond::class);
                $route_name = getRouteBaseName();
                $func = Functions::where('route', $route_name)->first();
                $role = RoleSecond::find($id);
                if ($role->name == config('custom.role')) {
                    $count_user = Admin::role(config('custom.role'))->count();
                } else {
                    $count_user = $role->users()->count() + Admin::role(config('custom.role'))->count();
                }
                $groupermission = Groupermission::with('permissions')->get();
                return view('backend.systems.role.assign.main', compact('func', 'role', 'count_user', 'groupermission'));
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
    public function update(RoleRequest $request, $id)
    {
        switch ($id) {
            case 'assign-role':
                $this->authorize('editAssign', RoleSecond::class);
                $data = $request->validated();
                $user = Admin::find($data['user_id']);
                if (Role::find($data['id'])->name != config('custom.role')) {
                    if (!$user->is_admin()) {
                        $status = 'Remove';
                        if ($data['select'] === "true") {
                            $user->assignRole($data['id']);
                            $status = 'Add';
                        } else {
                            $a = $user->removeRole($data['id']);
                        }
                        return response()->json(
                            [
                                'type' => 'success',
                                'title' => 'Success',
                                'content' => $status.' user successfully.',
                            ]
                        );
                    }
                    return response()->json(
                        [
                            'type' => 'error',
                            'title' => 'Error',
                            'content' => 'Cant edit role this user.',
                        ]
                    );
                } else {
                    if (Auth::user()->is_admin() && Auth::user()->id != $data['user_id']) {
                        $count = Admin::Role(config('custom.role'))->count();
                        if ($count > 1) {
                            $status = 'Remove';
                            if ($data['select'] === "true") {
                                $user->assignRole($data['id']);
                                $status = 'Add';
                            } else {
                                $a = $user->removeRole($data['id']);
                            }
                            return response()->json(
                                [
                                    'type' => 'success',
                                    'title' => 'Success',
                                    'content' => $status.' user successfully.',
                                ]
                            );
                        }
                        return response()->json(
                            [
                                'type' => 'error',
                                'title' => 'Error',
                                'content' => 'Cant edit yourself,  Boss!!',
                            ]
                        );
                    }
                    return response()->json(
                        [
                            'type' => 'error',
                            'title' => 'Error',
                            'content' => 'You are the last',
                        ]
                    );
                }
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Cant edit role this user.',
                    ]
                );
                break;
            default:
                $this->authorize('update', RoleSecond::class);
                $data = $request->validated();
                $role = RoleSecond::find($data['id']);
                if ($role) {
                    $role->syncPermissions($request->permission);
                    $groupermission = $role->permissions->pluck('groupermission_id')->toArray();
                    $sync = $role->groupermissions()->sync($groupermission);
                    $role->update([
                        'name' => $data['role_name'],
                        'description' => $data['description'],
                    ]);
                    return response()->json(
                        [
                            'type' => 'success',
                            'title' => 'Success',
                            'content' => 'Edited successfully.',
                            'data' => [
                                'name' => $data['role_name'],
                                'description' => $data['description'],
                            ],
                        ]
                    );
                }
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Have trouble, try again later.',
                    ]
                );
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function assignRole(Request $request)
    // {
    //     $this->authorize('editAssign', RoleSecond::class);
    //     $user = Admin::find($request->user_id);
    //     $role = Role::find($request->id);
    //     if ($role->name != config('custom.role')) {
    //         if (!$user->is_admin()) {
    //             $status = 'Remove';
    //             if ($request->select === "true") {
    //                 $user->assignRole($role);
    //                 $status = 'Add';
    //             } else {
    //                 $a = $user->removeRole($role);
    //             }
    //             return response()->json(
    //                 [
    //                     'type' => 'success',
    //                     'title' => 'Success',
    //                     'content' => $status.' user successfully.',
    //                 ]
    //             );
    //         }
    //         return response()->json(
    //             [
    //                 'type' => 'error',
    //                 'title' => 'Error',
    //                 'content' => 'Cant edit role this user.',
    //             ]
    //         );
    //     } else {
    //         if (Auth::user()->is_admin() && Auth::user()->id != $request->user_id) {
    //             $count = Admin::Role(config('custom.role'))->count();
    //             if ($count > 1) {
    //                 $status = 'Remove';
    //                 if ($data['select'] === "true") {
    //                     $user->assignRole($role);
    //                     $status = 'Add';
    //                 } else {
    //                     $a = $user->removeRole($role);
    //                 }
    //                 return response()->json(
    //                     [
    //                         'type' => 'success',
    //                         'title' => 'Success',
    //                         'content' => $status.' user successfully.',
    //                     ]
    //                 );
    //             }
    //             return response()->json(
    //                 [
    //                     'type' => 'error',
    //                     'title' => 'Error',
    //                     'content' => 'Cant edit yourself,  Boss!!',
    //                 ]
    //             );
    //         }
    //         return response()->json(
    //             [
    //                 'type' => 'error',
    //                 'title' => 'Error',
    //                 'content' => 'You are the last',
    //             ]
    //         );
    //     }
    //     return response()->json(
    //         [
    //             'type' => 'error',
    //             'title' => 'Error',
    //             'content' => 'Cant edit role this user.',
    //         ]
    //     );
    // }
}

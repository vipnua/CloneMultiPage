<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Functions;
use App\Model\RoleSecond;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Model\Groupermission;
use App\Http\Requests\System\RoleRequest;
use App\Policies\System\RolePolicy;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', RoleSecond::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        $groupermission = Groupermission::with('permissions')->get();
        return view('backend.systems.role.main', compact('func','groupermission'));
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
    public function store(RoleRequest $request)
    {
        $this->authorize('create', RoleSecond::class);
        $data = $request->validated();
        $role = RoleSecond::create([
            'name' => $data['role_name'],
            'guard_name' => 'admin',
            'description' => $data['description'],
        ]);
        if ($role) {
            if (isset($data['permission'])) {
                $role->syncPermissions($data['permission']);
                $groupermission = $role->permissions->pluck('groupermission_id')->toArray();
                $sync = $role->groupermissions()->sync($groupermission);
            }
            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Success',
                    'content' => 'Added successfully.'
                ]
            );
        }
        return response()->json(
            [
                'type'    => 'error',
                'title'   => 'Error',
                'content' => 'Have trouble, try again later.'
            ]
        );
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
                $this->authorize('viewAny', RoleSecond::class);
                $roles = RoleSecond::withCount('users');
                return datatables($roles)->make(true);
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
        $this->authorize('update', RoleSecond::class);
        $role = RoleSecond::find($id);
        $groupermission = Groupermission::with('permissions')->get();
        if ($role) {
            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Success',
                    'content' => view('backend.systems.role.modal-edit', compact(['role','groupermission']))->render(),
                ]
            );
        } else {
            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Error',
                    'content' => 'ID is not found',
                ]
            );
        }
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
        $this->authorize('update', RoleSecond::class);
        $data = $request->validated();
        $role = RoleSecond::find($data['id']);
        if ($role) {
            if ($role->name == config('custom.role') || in_array($role->name, config('custom.role_important'))) {
                if ($role->name != $data['role_name']) {
                    return response()->json(
                        [
                            'type' => 'error',
                            'title' => 'Error',
                            'content' => 'Cant edit name this role',
                        ]
                    );
                }
            }
            if (isset($data['permission'])) {
                $role->syncPermissions($data['permission']);
                $groupermission = $role->permissions->pluck('groupermission_id')->toArray();
                $sync = $role->groupermissions()->sync($groupermission);
            }
            $role->update([
                'name' => $data['role_name'],
                'description' => $data['description'],
            ]);
            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Success',
                    'content' => 'Edited successfully.'
                ]
            );
        }
        return response()->json(
            [
                'type'    => 'error',
                'title'   => 'Error',
                'content' => 'Have trouble, try again later.'
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
        $this->authorize('delete', RoleSecond::class);
        $role = RoleSecond::find($id);
        if ($role) {
            if ($role->name == config('custom.role') || in_array($role->name, config('custom.role_important'))) {
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Cant delete this role',
                    ]
                );
            }
            $role->delete();
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
}

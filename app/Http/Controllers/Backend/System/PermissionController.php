<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Functions;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Model\Groupermission;
use App\Http\Requests\System\PermissionRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        return view('backend.systems.permission.main', compact('func'));
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
    public function store(PermissionRequest $request)
    {
        $data = $request->validated();
        $group_permission = Groupermission::create([
            'name' => $data['permission_name'],
            'description' => $data['description'],
            'key' => $data['permission_key'],
        ]);
        $data_core = [];
        foreach ($data['permissions_core'] as $value) {
            $new_name = $data['permission_key'].'_'.$value;
            $data_core[] = [
                'name' => $new_name,
                'guard_name' => 'admin',
            ];
        }
        $group_permission->permissions()->createMany($data_core);
        if ($group_permission) {
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
            case 'get-permissions-datatable':
                $permission = Groupermission::with('roles','permissions');
                return datatables($permission)->make(true);
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
        $permission = Groupermission::find($id);
        if ($permission) {
            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Success',
                    'content' => view('backend.systems.permission.modal-edit', compact(['permission']))->render(),
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
    public function update(PermissionRequest $request, $id)
    {
        $data = $request->validated();
        $group_permission = Groupermission::find($data['id']);
        $group_permission->update([
            'name' => $data['permission_name'],
            'description' => $data['description'],
        ]);
        if ($data['permission_key'] != '') {
            $data_core = [
                'name' => $group_permission->key.'_'.$data['permission_key'],
                'guard_name' => 'admin',
            ];
            $group_permission->permissions()->create($data_core);
        }
        if ($group_permission) {
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
        $group_permission = Groupermission::find($id);
        if ($group_permission) {
            $group_permission->delete();
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

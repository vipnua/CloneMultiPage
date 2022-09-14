<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Functions;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Http\Requests\System\FunctionRequest;
use App\Policies\System\FunctionManager;

class FunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Functions::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        $list_parent = Functions::where('parent_id', 0)->select('id','name')->get();
        return view('backend.systems.function.main', compact('func','list_parent'));
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
    public function store(FunctionRequest $request)
    {
        $data = $request->validated();
        $function = Functions::create($data);
        if ($function) {
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
        if ($id == 'get-function-datatable') {
            return datatables($this->getData())->rawColumns(['icon'])->make(true);
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
        $function = Functions::find($id);
        $list_parent = Functions::where('parent_id', 0)->select('id','name')->get();
        if ($function) {
            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Success',
                    'content' => view('backend.systems.function.modal-edit', compact(['function','list_parent']))->render(),
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
    public function update(FunctionRequest $request, $id)
    {
        $data = $request->validated();
        $function = Functions::find($data['id'])->update($data);
        if ($function) {
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
        $function = Functions::find($id);
        if ($function) {
            $function->delete();
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

    public function getData()
    {
        $data      = [];
        $functions = Functions::where('parent_id', 0)->latest('ordering')->latest('id')->get();
        foreach ($functions as $function) {
            array_push($data, $function);
            if ($function->child) {
                foreach ($function->child as $child) {
                    array_push($data, $child);
                }
            }
        }
        return $data;
    }
}

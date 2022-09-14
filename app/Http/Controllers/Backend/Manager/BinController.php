<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Model\Functions;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserRequest;

class BinController extends Controller
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
        return view('backend.recycle-bin.main', compact('func', 'deleted_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Storage::disk('resource')->download('q8xinisJldB3g471RR4zY1WI8CWVtHzj7tSpXERP.jpg');
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
    public function show($id, Request $request)
    {
        switch ($id) {
            case 'get-customer-datatable':
                $this->authorize('forceDelete', User::class);
                $users = User::onlyTrashed()->withCount('browser')->latest('deleted_at');
                if ($request->name_search != null) {
                    $param = $request->name_search;
                    $users = $users->where(function ($query) use ($param) {
                        $query->where('name', 'like', '%' . $param . '%')
                            ->orWhere('email', 'like', '%' . $param . '%');
                    });
                }
                return datatables($users)->make(true);
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
    public function update(Request $request, $id)
    {
        switch ($id) {
            case 'user-restore':
                $user = User::onlyTrashed()->find($request->id)->restore();
                if ($user) {
                    return response()->json(
                        [
                            'type' => 'success',
                            'title' => 'Success',
                            'content' => 'Restored successfully',
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
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        switch ($id) {
            case 'user-forcedelete':
                $user = User::onlyTrashed()->find($request->id)->forceDelete();
                if ($user) {
                    return response()->json(
                        [
                            'type' => 'success',
                            'title' => 'Success',
                            'content' => 'Force Deleted successfully',
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
                break;
            case 'user-multidelete':
                if (count($request->ids) > 0) {
                    $user = User::onlyTrashed()->whereIn('id',$request->ids)->forceDelete();
                    if ($user) {
                        return response()->json(
                            [
                                'type' => 'success',
                                'title' => 'Success',
                                'content' => 'Force Deleted successfully',
                            ]
                        );
                    }
                }
                return response()->json(
                    [
                        'type' => 'error',
                        'title' => 'Error',
                        'content' => 'Having trouble, try again later',
                    ]
                );
                break;

            default:
                # code...
                break;
        }
    }
}

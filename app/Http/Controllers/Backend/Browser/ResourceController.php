<?php

namespace App\Http\Controllers\Backend\Browser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Functions;
use App\Model\Browser\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Traits\FileService;
use App\Http\Requests\Browser\ResourceRequest;

class ResourceController extends Controller
{
    public $disk = 'resource';
    use FileService;

    public function index()
    {
        $this->authorize('viewAny', Resource::class);
        $route_name = getRouteBaseName();
        $func = Functions::where('route', $route_name)->first();
        return view('backend.resource.main', compact('func'));
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
    public function store(ResourceRequest $request)
    {
        // dd(Storage::disk('resource')->delete('trinhcongson_ntdf1.zip'));
        $this->authorize('create', Resource::class);
        $data = $request->validated();
        $file_name = $data['file']->getClientOriginalName();
        $storage_file = $this->uploadFileToStorage('',$request->file,str_replace(" ","-",$file_name));
        if ($storage_file) {
            $resource = Resource::create([
                'name' => $data['name'],
                'version' => $data['version'],
                'description' => $data['description'],
                'path' => str_replace(" ","-",$file_name),
            ]);
            if ($resource) {
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
                    'content' => 'Cant save file.',
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
                $this->authorize('viewAny', Resource::class);
                $resource = Resource::latest('id');
                return datatables($resource)->make(true);
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
    public function update(ResourceRequest $request, $id)
    {
        $this->authorize('update', Resource::class);
        $data = $request->validated();
        $resource = Resource::find($id)->update([
            'name' => $data['name'],
            'version' => $data['version'],
            'description' => $data['description']
        ]);
        if ($resource) {
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
        //
    }

    public function resourceStatus(Request $request)
    {
        $this->authorize('update', Resource::class);
        $resource = Resource::find($request->id);
        if ($resource) {
            $current_active = Resource::where('status', 1);
            if ($current_active) {
                $current_active->update([
                    'status' => 2
                ]);
            }
            $resource->update([
                'status' => 1,
            ]);
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

    public function download($path)
    {
        $this->authorize('download', Resource::class);
        if ($this->fileExists($path)) {
           return $this->getLinkDowndload($path);
        }
        return abort(404);
    }
}

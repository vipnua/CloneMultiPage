<?php

namespace App\Http\Controllers\Api\Folder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FolderRequest;
use App\Http\Resources\Folder\FolderCollection as FolderCollection;
use App\Http\Resources\Folder\FolderResource as FolderResource;
use App\Http\Resources\Browser\BrowserCollection as BrowserCollection;
use App\Model\Folder;
use App\Services\Browser\BrowserService;
use App\Services\Folder\FolderService;
use Auth;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    public $folderService;
    public $browserService;

    public function __construct(FolderService $folderService, BrowserService $browserService)
    {
        $this->folderService = $folderService;
        $this->browserService = $browserService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Auth::user()->folders;
        return (new FolderCollection($result))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FolderRequest $request)
    {
        $request = $request->validated();

        $data = [
            'name' => $request['name'],
            'user_id' => Auth::user()->id,
            'uuid' => uuid(),
        ];
        $folder = $this->folderService->createNewFolder($data);
        if ($folder) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => new FolderResource($folder),
                ],
                res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => 'There was an error in processing, please try again!',
                'content' => res_content('empty'),
            ],
            res_code('500')
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FolderRequest $request, $uuid)
    {
        $request = $request->validated();
        $folder = $this->folderService->findFolderByUuid($uuid);

        if (!$folder || !$this->folderService->checkUserOwnedFolder($folder, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }
        $data = [
            'name' => $request['name'],
        ];
        $folder = $this->folderService->updateFolder($folder, $data);
        if ($folder) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Update folder success.',
                    'content' => res_content('empty'),
                ],
                res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => 'There was an error in processing, please try again!',
                'content' => res_content('empty'),
            ],
            res_code('500')
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $folder = $this->folderService->findFolderByUuid($uuid);

        if (!$folder || !$this->folderService->checkUserOwnedFolder($folder, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }

        $this->folderService->detachBrowser($folder);
        if ($this->folderService->destroyFolder($folder)) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Delete folder success.',
                    'content' => res_content('empty'),
                ],
                res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('error'),
                'content' => res_content('empty'),
            ],
            res_code('500')
        );

    }

    public function addBrowserToFolder(Request $request, $uuid)
    {
        $folder = $this->folderService->findFolderByUuid($uuid);
        $browser = $this->browserService->findBrowserByUuid($request->uuid_browser);
        if ($browser && $folder && $this->folderService->checkUserOwnedFolder($folder, Auth::user()) && $this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {

            $query = $folder->browser();

            $checkExist = (clone $query)->wherePivot('browser_id', $browser->id)->wherePivot('folder_id', $folder->id)->first();

            if ($checkExist) {
                return response()->json(
                    [
                        'type' => res_type('error'),
                        'title' => 'This browser has been added!',
                        'content' => res_content('empty'),
                    ],
                    res_code('400')
                );
            }

            $query = $query->attach($browser->id, ['uuid' => uuid()]);

            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Add a successful browser.',
                    'content' => res_content('empty'),
                ], res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('notfound_or_permission'),
                'content' => res_content('empty'),
            ],
            res_code('400')
        );
    }

    public function removeBrowserFromFolder(Request $request, $uuid)
    {
        $folder = $this->folderService->findFolderByUuid($uuid);
        $arrUuid_browser = [];

        // tìm các browser theo uuid
        // nếu không tìm thấy thì return
        //nếu tìm thấy thì push vào mảng
        foreach ($request->uuid_browser as $item) {
            $browser = $this->browserService->findBrowserByUuid($item);
            if (!$browser) {

                return response()->json(
                    [
                        'type' => res_type('error'),
                        'title' => res_title('notfound'),
                        'content' => res_content('empty'),
                    ],
                    res_code('400')
                );
            }
            array_push($arrUuid_browser, $browser->id);
        }

        if ($arrUuid_browser && $folder && $this->folderService->checkUserOwnedFolder($folder, Auth::user())) {
            $folder->browser()->detach($arrUuid_browser);
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Delete a successful browser.',
                    'content' => res_content('empty'),
                ], res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('notfound_or_permission'),
                'content' => res_content('empty'),
            ],
            res_code('400')
        );
    }

    public function getBrowserByFolder($uuid)
    {
        // hiển thị tất cả browser có trong folder
        $folder = $this->folderService->findFolderByUuid($uuid);

        if (!$folder || !$this->folderService->checkUserAndShareOwnedFolder($folder, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }
        $browsers = $folder->browser;
        return (new BrowserCollection($browsers))->response()->setStatusCode(200);
    }

}

<?php

namespace App\Http\Controllers\Api\Browser;

use App\Browser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Browser\BrowserRequest;
use App\Http\Requests\Browser\ShowBrowserRequest;
use App\Http\Resources\Browser\BrowserCollection as BrowserCollection;
use App\Http\Resources\Browser\BrowserDowloadResource;
use App\Http\Resources\Browser\BrowserResource;
use App\Services\Browser\BrowserService;
use App\Traits\FileService;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrowserController extends Controller
{
    use FileService;

    public $browserService;
    public function __construct(BrowserService $browserService)
    {
        $this->browserService = $browserService;
    }

    /**
     * Store a new browser
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrowserRequest $request)
    {
        $request = $request->validated();
        $data = [
            'uuid' => uuid(),
            'config' => json_decode($request['config'], 1),
            'user_id' => Auth::user()->id,
            'directory' => null,
            'file_name' => null,
            'can_be_running' => 1,
        ];

        $browser = $this->browserService->createNewBrowser($data);
        if ($browser) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => new BrowserResource($browser),
                ],
                res_code('200')
            );
        }

        return defaultReponseError();
    }

    /**
     * Retrieve config, filename, link dowload...
     *
     * @param \App\Http\Requests\Browser\ShowBrowserRequest $request, $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(ShowBrowserRequest $request, $uuid)
    {
        $browser = $this->browserService->findBrowserByUuid($uuid);
        /**
         *  If browser is running return response
         *  running : true => Can open the browser
         *  running : false => Can't open because someone already uses it
         */
        if (!$browser->can_be_running) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => "The browser is already being run by another user!",
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }

        $title = "Browser ready to run.";
        if ($request->run) {
            $title = 'The browser starts running...';
            $this->browserService->syncStatusCanBeRunning($browser, false);
            $browser->can_be_running = true;
        }
        return response()->json(
            [
                'type' => res_type('success'),
                'title' => $title,
                'content' => new BrowserDowloadResource($browser),
            ],
            res_code('200')
        );
    }

    /**
     * Update a browser
     *
     * @param  \Illuminate\Http\BrowserRequest  $request, $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(BrowserRequest $request, $uuid)
    {
        $request = $request->validated();
        $browser = $this->browserService->findBrowserByUuid($uuid);
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
        if (!$this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('permission'),
                    'content' => res_content('empty'),
                ],
                res_code('401')
            );
        }

        /* upload file */
        $file = $request['browser_file'];
        $directory = Carbon::now()->year . '/' . Carbon::now()->month;
        $file_name = md5(Str::uuid()) . '.zip';
        if (!$this->uploadFileToStorage($directory, $file, $file_name)) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'Upload file error!',
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }

        /* update database */
        $nameFileOld = $browser->file_name;
        $directoryFileOld = $browser->directory;
        $data = [
            'config' => json_decode($request['config'], 1),
            'directory' => $directory,
            'file_name' => $file_name,
        ];
        $browser = $this->browserService->updateBrowser($browser, $data);
        if ($browser) {
            if ($directoryFileOld && $nameFileOld && $this->fileExists($directoryFileOld . '/' . $nameFileOld)) {
                $this->destroyFile($directoryFileOld . '/' . $nameFileOld);
            }
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Update browser success.',
                    'content' => res_content('empty'),
                ],
                res_code('200')
            );
        }

        $this->destroyFile($directory . '/' . $file_name);

        return defaultReponseError();
    }

    /**
     * Remove the browser from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $browser = $this->browserService->findBrowserByUuid($uuid);
        if (!$this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }

        if ($this->browserService->destroyBrowser($browser)) {

            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => "Successfully deleted.",
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

    /**
     * Close a browser
     *
     * @param  \Illuminate\Http\BrowserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function closeBrowser(Request $request)
    {
        $browser = $this->browserService->findBrowserByUuid($request['uuid']);
        if (!$browser || !$this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }

        $this->browserService->syncStatusCanBeRunning($browser, true);
        return response()->json(
            [
                'type' => res_type('success'),
                'title' => "Successfully close browser.",
                'content' => res_content('empty'),
            ],
            res_code('200')
        );
    }

    public function getLinkDowndload($url)
    {
        $url = str_replace('-', '/', $url);
        return Storage::download($url);
    }

    public function CheckActivity(Request $request)
    {
        $user = Auth::user();
        $browserUuids = $request->browser_uuid;
        if ($browserUuids) {
            foreach ($browserUuids as $browserUuid) {
                $browser = $this->browserService->findBrowserByUuid($browserUuid);
                if ($this->browserService->checkUserAndShareOwnedBrowser($browser, (clone $user))) {
                    $browser->updated_at = Carbon::now();
                    $browser->save();
                }
            }

        }
        $personalBrowser = $this->browserService->getPersonalBrowserRunning((clone $user))->toArray();
        $sharedBrowser = $this->browserService->getSharedBrowserRunning((clone $user))->toArray();
        $data = [
            'personal' => $personalBrowser,
            'shared' => $sharedBrowser,
        ];

        return response()->json(
            [
                'type' => res_type('success'),
                'title' => res_title('success'),
                'content' => $data,
            ],
            res_code('200')
        );
    }

    /**
     * Shows all browsers that have been cleared but does not return file_name.
     *
     * @param  null
     * @return App\Http\Resources\Browser\BrowserCollection
     */
    public function recycle()
    {
        $user = Auth::user();
        $browserRecycle = $this->browserService->onlyTrashed((clone $user));
        $browserRecycle = $browserRecycle->map(function ($item, $key) {
            $item->directory = null;
            $item->file_name = null;
            return $item;
        });
        return (new BrowserCollection($browserRecycle))->response()->setStatusCode(200);
    }

    /**
     * Restore 1 or more browsers via uuid browser
     *
     * @param  BrowserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(BrowserRequest $request)
    {
        $request = $request->validated();
        $browsersUuid = $request['uuid_browser'];
        if ($browsersUuid) {
            $data = [];
            foreach ($browsersUuid as $browserUuid) {
                $browser = $this->browserService->findBrowserDeletedByUuid($browserUuid);
                if ($browser && $this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
                    if ($this->browserService->restore($browser)) {
                        array_push($data, $browserUuid);
                    }
                }
            }
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => $data,
                ],
                res_code('200')
            );

        }
        return defaultReponseError();

    }

    /**
     * duplicate browser from an existing browser
     *
     * @param  uuid
     * @return \Illuminate\Http\Response
     */
    public function duplicate($uuid)
    {
        $user = Auth::user();
        $browser = $this->browserService->findBrowserByUuid($uuid);
        if ($browser && $this->browserService->checkUserAndShareOwnedBrowser($browser, $user)) {

            $newBrowser = $browser->replicate();
            $newDirectory = Carbon::now()->year . '/' . Carbon::now()->month;
            $newFileName = null;
            $newPathFile = '';

            if ($browser->file_name) {
                $newFileName = md5(Str::uuid()) . '.zip';
                $newPathFile = $this->browserService->createFilePart($newDirectory, $newFileName);
                $currentPathFile = $this->browserService->getFilePart($browser);
                $copyResult = $this->copyFile($currentPathFile, $newPathFile);

                if (!$copyResult) {
                    return response()->json(
                        [
                            'type' => res_type('error'),
                            'title' => 'Create file error!',
                            'content' => res_content('empty'),
                        ],
                        res_code('400')
                    );
                }
            }
            if ($browser->config && isset($browser->config['name']) && $browser->config['name']) {
                $newNameBrowser = $this->browserService->getDuplicateNameBrowser($user, $browser->config['name']);

                $newConfig = $browser->config;
                $newConfig['name'] = $newNameBrowser;
                $data = [
                    'uuid' => uuid(),
                    'config' => $newConfig,
                    'user_id' => Auth::user()->id,
                    'directory' => $newDirectory,
                    'file_name' => $newFileName,
                    'can_be_running' => 1,
                ];

                $browser = $this->browserService->createNewBrowser($data);
                if ($browser) {
                    return response()->json(
                        [
                            'type' => res_type('success'),
                            'title' => res_title('success'),
                            'content' => new BrowserResource($browser),
                        ],
                        res_code('200')
                    );
                }
            }

            if($newPathFile){
                $this->destroyFile($newPathFile);
            }
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'This browser cannot be duplicated !',
                    'content' => res_content('empty'),
                ],
                res_code('400')
            );
        }
        return defaultReponseError();

        // $browsersUuid = $request['uuid_browser'];
        // if ($browsersUuid) {
        //     $data = [];
        //     foreach ($browsersUuid as $browserUuid) {
        //         $browser = $this->browserService->findBrowserDeletedByUuid($browserUuid);
        //         if ($browser && $this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
        //             if ($this->browserService->restore($browser)) {
        //                 array_push($data, $browserUuid);
        //             }
        //         }
        //     }
        //     return response()->json(
        //         [
        //             'type' => res_type('success'),
        //             'title' => res_title('success'),
        //             'content' => $data,
        //         ],
        //         res_code('200')
        //     );

        // }

    }
}

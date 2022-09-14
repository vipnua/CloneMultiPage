<?php

namespace App\Http\Controllers\Api\Share;

use App\Http\Controllers\Controller;
use App\Http\Requests\Browser\BrowserRequest;
use App\Http\Requests\Share\ShareRequest;
use App\Http\Resources\Browser\BrowserDowloadResource;
use App\Http\Resources\Share\ShareCollection as ShareCollection;
use App\Http\Resources\Share\SharedUserCollection;
use App\Services\Browser\BrowserService;
use App\Services\Share\ShareService;
use App\Services\User\UserService;
use App\Traits\FileService;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrowserShareController extends Controller
{
    use FileService;

    private $browserService;
    private $userService;
    private $shareService;

    public function __construct(BrowserService $browserService, UserService $userService, ShareService $shareService)
    {
        $this->browserService = $browserService;
        $this->userService = $userService;
        $this->shareService = $shareService;
    }

    /**
     * Retrieve all shared browser to the current user
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Auth::user()->sharing;
        return (new ShareCollection($result))->response()->setStatusCode(200);
    }

    /**
     * Share resources to users via email.
     *
     * @param  App\Http\Requests\Share\ShareRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShareRequest $request)
    {
        $request = $request->validated();

        $browser = $this->browserService->findBrowserByUuid($request['browser_uuid']);
        $recepient = $this->userService->findUserByEmail($request['recepient']);

        $query = $browser->sharing();
        $exist = $this->shareService->hasPivot($query, $recepient);
        if (!$exist) {
            $this->shareService->shareBrowserToUser($query, $recepient, $request);
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Successful browser sharing.',
                    'content' => res_content('empty'),
                ], res_code('200')
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => 'This user has been shared!',
                'content' => res_content('empty'),
            ], res_code('400')
        );
    }

    /**
     * Update role shared
     *
     * @param  App\Http\Requests\Share\ShareRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ShareRequest $request)
    {
        $request = $request->validated();

        $this->shareService->updateRoleByUuid($request);

        return response()->json(
            [
                'type' => res_type('success'),
                'title' => 'Successfully updated role',
                'content' => res_content('empty'),
            ], res_code('200')
        );
    }

    /**
     * Unshare
     *
     * @param  App\Http\Requests\Share\ShareRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShareRequest $request)
    {
        $request = $request->validated();

        if ($this->shareService->unshare($request)) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Successfully unshare.',
                    'content' => res_content('empty'),
                ], res_code('200')
            );
        }

        return defaultReponseError();
    }

    /**
     * Retrieve all shared user by browser uuid
     *
     * @param  $uuid
     * @return \App\Http\Resources\Share\SharedUserCollection;
     */
    public function getSharedUserByBrowser($uuid)
    {
        $user = Auth::user();
        $browser = $this->browserService->findBrowserByUuid($uuid);
        if ($browser && $this->browserService->checkUserOwnedBrowser($browser, $user)) {
            return (new SharedUserCollection($browser->sharing()->withPivot('uuid')->get()))->response()->setStatusCode(200);
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('notfound_or_permission'),
                'content' => res_content('empty'),
            ], res_code(401)
        );

    }

    /**
     * Retrieve config, filename, link dowload...
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showBrowser(Request $request, $uuid)
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
     * Update a browser for shared user
     *
     * @param  \Illuminate\Http\BrowserRequest  $request, $uuid
     * @return \Illuminate\Http\Response
     */
    public function updateBrowser(BrowserRequest $request, $uuid)
    {
        $request = $request->validated();
        $browser = $this->browserService->findBrowserByUuid($uuid);

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
     * Close a browser
     *
     * @param  \Illuminate\Http\BrowserRequest  $request, $uuid
     * @return \Illuminate\Http\Response
     */
    public function closeBrowser(Request $request, $uuid)
    {
        $browser = $this->browserService->findBrowserByUuid($uuid);
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
}

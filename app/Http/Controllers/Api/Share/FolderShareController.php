<?php

namespace App\Http\Controllers\Api\Share;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\ShareFolderRequest;
use App\Http\Resources\Share\FolderShareCollection;
use App\Http\Resources\Share\SharedUserCollection;
use App\Services\Folder\FolderService;
use App\Services\Share\ShareService;
use App\Services\User\UserService;
use App\User;
use Auth;
use Illuminate\Http\Request;

class FolderShareController extends Controller
{
    private $folderService;
    private $userService;
    private $shareService;

    public function __construct(FolderService $folderService, UserService $userService, ShareService $shareService)
    {
        $this->folderService = $folderService;
        $this->userService = $userService;
        $this->shareService = $shareService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sharing = Auth::user()->folder_sharing;
        return (new FolderShareCollection($sharing))->response()->setStatusCode(200);
    }

    /**
     * Share folder and browser in folder to user
     *
     * @param  \Illuminate\Http\ShareFolderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShareFolderRequest $request)
    {
        $request = $request->validated();

        $currentAuth = Auth::user();
        $recepient = $this->userService->findUserByEmail($request['recepient']);
        if (!$recepient || $recepient->email == $currentAuth->email) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'Invalid or unregistered email!',
                    'content' => res_content('empty'),
                ], res_code('400')
            );
        }

        $folder = $this->folderService->findFolderByUuid($request['folder_uuid']);
        if (!$this->folderService->checkUserOwnedFolder($folder, $currentAuth)) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound_or_permission'),
                    'content' => res_content('empty'),
                ], res_code('400')
            );
        }

        $query = $folder->sharing();
        $exist = $this->shareService->hasPivot($query, $recepient);
        if ($exist) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => 'This user has been shared!',
                    'content' => res_content('empty'),
                ], res_code('400')
            );
        }

        /* attach folder  to pivot table */
            /*  $request['type'] = config('type.sharing.folder'); */
        $result = $this->shareService->shareResourceToUser($query, $recepient, $request);

        /* attach browser in folder to pivot table */
        $idsBrowserInFolder = $folder->browser->pluck('id')->toArray();
            /* $request['type'] = config('type.sharing.browser'); */
        $request['parent'] = $result['uuid'];
        $data = $this->shareService->formatDataBeforeAttach($idsBrowserInFolder, $request);

        $updated = $this->shareService->shareMultipleResource($recepient->sharing(), $data);
        $sharedBrowserTotal = count($updated['attached']) + count($updated['updated']);

        return response()->json(
            [
                'type' => res_type('success'),
                'title' => "Share folder and $sharedBrowserTotal browsers in folder successfully.",
                'content' => res_content('empty'),
            ], res_code('200')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ShareFolderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ShareFolderRequest $request)
    {
        $request = $request->validated();

        $this->shareService->updateResourceAndAssociateRole($request);

        return response()->json(
            [
                'type' => res_type('success'),
                'title' => 'Successfully updated.',
                'content' => res_content('empty'),
            ], res_code('200')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     *  @param  \Illuminate\Http\ShareFolderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShareFolderRequest $request)
    {
        $request = $request->validated();

        $this->shareService->deleteResourceAndAssociate($request);

        return response()->json(
            [
                'type' => res_type('success'),
                'title' => 'Successfully deleted.',
                'content' => res_content('empty'),
            ], res_code('200')
        );
    }

    /**
     * Retrieve all email shared by folder uuid
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function retrieveSharedEmail($uuid)
    {
        $folder = $this->folderService->findFolderByUuid($uuid);
        $user = Auth::user();

        if (!$folder) {
            return response()->json(
                [
                    'type' => res_type('error'),
                    'title' => res_title('notfound'),
                    'content' => res_content('empty'),
                ], res_code(404)
            );
        }
        if ($this->folderService->checkUserOwnedFolder($folder, $user)) {
            $users = $folder->sharing()->withPivot('uuid')->get();
            return (new SharedUserCollection($users))->response()->setStatusCode(200);
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('permission'),
                'content' => res_content('empty'),
            ], res_code(401)
        );
    }

    public function checkRecepient($folder, $recepient)
    {
        $users = $folder->share_folder()->get();
        $recepient = $users->where('email', $recepient)->first();

        return $recepient;
    }
}

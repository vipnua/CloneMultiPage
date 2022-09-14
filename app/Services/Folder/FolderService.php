<?php

namespace App\Services\Folder;

use App\Model\Folder;

class FolderService{

    public function createNewFolder($data)
    {
        return Folder::create($data);
    }

    public function updateFolder($folder, $data)
    {
        return $folder->update($data);
    }

    public function destroyFolder($folder)
    {
        return $folder->delete();
    }

    public function detachBrowser($folder)
    {
        return $folder->browser()->detach();
    }

    public function findFolderByUuid($uuid)
    {
        return Folder::whereUuid($uuid)->first();
    }

    public function checkUserOwnedFolder($folder, $user)
    {
        if ($folder && ($folder->user_id == $user->id)) {
            return true;
        }
        return false;
    }

    public function checkUserAndShareOwnedFolder($folder, $user)
    {
        if ($folder && ($folder->user_id == $user->id)) {
            return true;
        }
        // nếu là user được share thì vẫn có quyền đóng
        if ($folder) {
            $idUserToShared = $folder->sharing()->get()->pluck('id')->toArray();
            if (in_array($user->id, $idUserToShared)) {
                return true;
            }
        }

        return false;
    }
}
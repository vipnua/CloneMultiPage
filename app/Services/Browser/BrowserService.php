<?php

namespace App\Services\Browser;

use App\Browser;
use App\Traits\FileService;

/**
 * Class BrowserService
 */

class BrowserService
{
    use FileService;

    public function createNewBrowser($data)
    {
        return Browser::create($data);
    }

    public function updateBrowser($browser, $data)
    {
        return $browser->update($data);
    }

    public function destroyBrowser($browser)
    {
        return $browser->delete();
    }

    public function checkUserOwnedBrowser($browser, $user)
    {
        if ($browser && ($browser->user_id == $user->id)) {
            return true;
        }
        return false;
    }

    public function checkUserAndShareOwnedBrowser($browser, $user)
    {
        if ($browser && ($browser->user_id == $user->id)) {
            return true;
        }
        // nếu là user được share thì vẫn có quyền đóng
        if ($browser) {
            $idUserToShared = $browser->sharing()->get()->pluck('id')->toArray();
            if (in_array($user->id, $idUserToShared)) {
                return true;
            }
        }

        return false;
    }

    public function findBrowserByUuid($uuid)
    {
        return Browser::whereUuid($uuid)->first();
    }

    public function getLinkDownloadBrowser($browser)
    {
        $data = [
            'file_name' => $browser['file_name'],
            'config' => $browser['config'],
            'can_be_running' => $browser['can_be_running'],
            'link' => config('app.url') . '/api/browser/file/' . str_replace('/', '-', $browser['directory']) . '-' . $browser['file_name'],
        ];
        return $data;
    }

    public function getFilePart($browser)
    {
        if ($browser && isset($browser['directory']) && isset($browser['file_name'])) {
            return $browser['directory'] . '/' . $browser['file_name'];
        }
        return false;
    }

    public function createFilePart($newDirectory, $newFileName)
    {
        $newPathFile = $newDirectory . '/' . $newFileName;
        return $newPathFile;
    }

    public function syncStatusCanBeRunning($browser, $status)
    {
        if ($status) {
            $browser->update(['can_be_running' => true]);
            return;
        }
        $browser->update(['can_be_running' => false]);
        return;

    }

    public function getPersonalBrowserRunning($user)
    {
        return $user->browser->where('can_be_running', 0)->pluck('uuid');
    }

    public function getSharedBrowserRunning($user)
    {
        return $user->sharing->where('can_be_running', 0)->pluck('uuid');
    }

    public function onlyTrashed($user)
    {
        return $user->browser()->onlyTrashed()->get()->makeHidden('file_name');
    }

    public function findBrowserDeletedByUuid($uuid)
    {
        return Browser::onlyTrashed()->whereUuid($uuid)->first();
    }

    public function forceDelete($browser)
    {
        return $browser->forceDelete();
    }

    public function restore($browser)
    {
        return $browser->restore();
    }

    //generate name when duplicate
    public function getDuplicateNameBrowser($user, $name)
    {
        $ownerConfigs = $user->browser->pluck('config')->all();
        $sharedConfigs = $user->sharing->pluck('config')->all();
        $configs = array_merge($ownerConfigs,$sharedConfigs); 
               
        $defaultName = $this->getNameDefault($name);

        $check = false;
        $number = 0;
        foreach ($configs as $config) {
            if ($config && isset($config['name'])) {
                $defaultNameConfig = $this->getNameDefault($config['name']);
                if (strcmp($defaultNameConfig, $defaultName) == 0) {
                    if ($this->getNumberBetweenBrackets($config['name']) > $number) {
                        $check = true;
                        $number = $this->getNumberBetweenBrackets($config['name']);
                    }
                }
            }
        }
        return $defaultName . ' [' . ($number + 1) . ']';
    }

    // /get number in name abc[1] => 1
    public function getNumberBetweenBrackets($string)
    {
        $text = explode("|", str_replace(['[', ']'], ['|', '|'], $string));
        $len = count($text);
        if ($len && isset($text[$len - 2]) && is_numeric($text[$len - 2])) {
            return (int) ($text[$len - 2]);
        }
        return 0;
    }

    //get default name abc[1] => abc
    public function getNameDefault($string)
    {
        $text = explode("|", str_replace(['[', ']'], ['|', '|'], $string));
        $len = count($text);
        if ($len && isset($text[$len - 2]) && is_numeric($text[$len - 2])) {
            $remove = ' [' . $text[$len - 2] . ']';
            return str_replace($remove, '', $string);
        }
        return $string;
    }
}

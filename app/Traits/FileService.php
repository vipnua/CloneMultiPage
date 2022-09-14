<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileService
{
    public function getDisk()
    {
        return $this->disk ?? 'local';
    }

    public function validateFileZip($file)
    {
        if (substr_count($file->getClientOriginalName(), '.') > 1) {
            return false;
        }
        return true;
    }

    public function fileExists($url)
    {
        return Storage::disk($this->getDisk())->exists($url);
    }

    public function uploadFileToStorage($directory, $file, $file_name)
    {
        $storage = Storage::disk($this->getDisk())->putFileAs($directory, $file, $file_name);
        if (!$storage) {
            return false;
        }

        return true;
    }

    public function destroyFile($path)
    {
        if (Storage::disk($this->getDisk())->exists($path)) {
            $delete = Storage::disk($this->getDisk())->delete($path);
            if ($delete) {
                return true;
            }
        }
        return false;
    }

    public function getLinkDowndload($url)
    {
        return Storage::disk($this->getDisk())->download($url);
    }

    public function copyFile($currentPathFile, $newPathFile)
    {
        if (Storage::disk($this->getDisk())->exists($currentPathFile)) {
            $copyResult = Storage::disk($this->getDisk())->copy($currentPathFile, $newPathFile);
            if ($copyResult) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getPath($file_name)
    {
        return Storage::disk($this->getDisk())->path($file_name);
    }

    public function getUrl($file_name)
    {
        return Storage::disk($this->getDisk())->url($file_name);
    }
}

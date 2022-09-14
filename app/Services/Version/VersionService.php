<?php

namespace App\Services\Version;

use App\Model\Version;

class VersionService
{

    public function createNewVersion($data)
    {
        return Version::create($data);
    }

    public function getLatestVersion()
    {
        return Version::latest('version')->first();
    }
}

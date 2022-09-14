<?php

namespace App\Http\Controllers\Api\Version;

use App\Http\Controllers\Controller;
use App\Http\Requests\Version\VersionRequest;
use App\Http\Resources\Version\VersionResouce;
use App\Model\Version;
use App\Services\Version\VersionService;
use App\Traits\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VersionController extends Controller
{
    use FileService;

    public $versionService;
    public function __construct(VersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    /**
     * Display a listing of the version app.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $version = $this->versionService->getLatestVersion();
        if ($version) {
            $version->path = config('app.url') . '/api/version/dowload/' . str_replace('/', '-', $version->path);
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => new VersionResouce($version),
                ],
                res_code('200')
            );
        }

        return defaultReponseError();
    }

    /**
     * Store a newly created version in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VersionRequest $request)
    {
        $request = $request->validated();

        /* upload file */
        $directory = "version/" . $request['version'];
        $file = $request['version_file'];
        $file_name = md5(Str::uuid()) . '.zip';
        $fullpath = $directory . "/" . $file_name;
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

        /* save to database */
        $data = [
            'uuid' => uuid(),
            'name' => $request['name'],
            'version' => $request['version'],
            'description' => $request['description'],
            'path' => $fullpath,

        ];

        if (isset($request['remove_file'])) {
            $data['remove_file'] = $request['remove_file'];
        }
        
        $version = $this->versionService->createNewVersion($data);
        if ($version) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => res_title('success'),
                    'content' => new VersionResouce($version),
                ],
                res_code('200')
            );
        }
        $this->destroyFile($fullpath);

        return defaultReponseError();
    }

    /**
     * Dowload file version
     *
     * @param  path
     * @return file
     */
    public function dowload($path)
    {
        $path = str_replace('-', '/', $path);
        if ($this->fileExists($path)) {
            return $this->getLinkDowndload($path);
        }

        return defaultReponseError();
    }
}

<?php

namespace App\Http\Resources\Version;

use Illuminate\Http\Resources\Json\JsonResource;

class VersionResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'version' => $this->version,
            'description' => $this->description,
            'path' => $this->path,
            'remove_file' => $this->remove_file,
            'created_at' => $this->created_at,
        ];
    }
}

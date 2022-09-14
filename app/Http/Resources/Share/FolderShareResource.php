<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\JsonResource;

class FolderShareResource extends JsonResource
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
            'total_browser' => $this->browser->count(),
            'share_role' => $this->folder_sharing ? $this->folder_sharing->role: "",
            'created_at' => $this->created_at
        ];
    }
}

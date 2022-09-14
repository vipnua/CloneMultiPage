<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\JsonResource;

class ShareResource extends JsonResource
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
            'config' => $this->config,
            'directory' => $this->directory ? $this->directory : "",
            'file_name' => $this->file_name ? $this->file_name : "",
            'can_be_running' => $this->can_be_running,
            'shares_role' => $this->sharing->role,
            'created_at' => $this->created_at,
        ];
    }
}

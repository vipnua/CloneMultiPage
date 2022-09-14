<?php

namespace App\Http\Resources\Browser;

use Illuminate\Http\Resources\Json\JsonResource;

class BrowserResource extends JsonResource
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
            'created_at' => $this->created_at,
        ];
    }
}

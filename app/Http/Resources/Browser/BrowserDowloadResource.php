<?php

namespace App\Http\Resources\Browser;

use Illuminate\Http\Resources\Json\JsonResource;

class BrowserDowloadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'uuid' => $this->uuid,
            'file_name' => $this->file_name ? $this->file_name : "",
            'directory' => $this->directory ? $this->directory : "",
            'config' => $this->config,
            'can_be_running' => $this->can_be_running,
            'link' => "",
            'created_at' => $this->created_at,
        ];

        if ($this->file_name && $this->directory) {
            $data['can_be_running'] = $this->can_be_running;
            $data['link'] =  config('app.url') . '/api/browser/file/' . str_replace('/', '-', $this->directory) . '-' . $this->file_name;
        }

        return $data;
    }
}

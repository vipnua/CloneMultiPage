<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Browser\BrowserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
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
            'type' => res_type('success'),
            'title' => res_title('success'),
            'content' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email,
                'setting' => $this->setting,
                'browser' => BrowserResource::collection($this->browser),
                'browser_total' => $this->browser->count(),
                'created_at' => $this->created_at,
            ],
        ];
    }
}

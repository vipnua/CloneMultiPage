<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\JsonResource;

class SharedUserResource extends JsonResource
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
            'shared_uuid' => $this->sharing->uuid ? $this->sharing->uuid : "",
            'email' => $this->email,
            'role' => $this->sharing->role ? $this->sharing->role : "",
        ];
    }
}

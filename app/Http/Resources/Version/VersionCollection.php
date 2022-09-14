<?php

namespace App\Http\Resources\Version;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VersionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => res_type('success'),
            'title' => res_title('success'),
            'content' => new VersionResouce($this->collection),
        ];
    }
}  

<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SharedUserCollection extends ResourceCollection
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
            'content' => SharedUserResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

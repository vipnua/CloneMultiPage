<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShareCollection extends ResourceCollection
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
            'title' => 'Load successfully',
            'content' => ShareResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
            ]
        ];
    }
}  

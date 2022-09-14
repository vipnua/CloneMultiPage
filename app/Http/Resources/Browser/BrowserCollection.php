<?php

namespace App\Http\Resources\Browser;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrowserCollection extends ResourceCollection
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
            'type' => 'success',
            'title' => 'Load successfully',
            'content' => BrowserResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
            ]
        ];
    }
}  

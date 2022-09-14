<?php

namespace App\Http\Resources\Share;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FolderShareCollection extends ResourceCollection
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
            'content' => FolderShareResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
            ]
        ];
    }
}

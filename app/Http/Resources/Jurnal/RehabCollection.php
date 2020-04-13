<?php

namespace App\Http\Resources\Jurnal;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RehabCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

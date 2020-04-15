<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlotRegistryApiResource extends JsonResource
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
            'cadastral_number' => $this->cadastral_number,
            'address' => $this->address,
            'price' => $this->price,
            'area' => $this->area,
            '_links' => $this->_links,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer id
 * @property string title
 * @property string en_title
 * @property string slug
 */
class WebinarResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'en_title' => $this->en_title,
            'slug' => $this->slug
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property integer id
 * @property string title
 * @property string label
 * @property string slug
 * @property string description
 * @property string content
 * @property string provider_id
 * @property string image
 * @property string banner
 * @property array links
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
            'label' => $this->label,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'provider_id' => $this->provider_id,
            'image' => Storage::disk('media')->url($this->image),
            'banner' => Storage::disk('media')->url($this->banner),
            'links' => $this->links
        ];
    }
}

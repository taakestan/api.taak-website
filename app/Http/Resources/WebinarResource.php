<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer id
 * @property string title
 * @property string label
 * @property string slug
 * @property string description
 * @property string content
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
            'provider' => new ProviderResource($this->whenLoaded('provider'))
        ];
    }
}

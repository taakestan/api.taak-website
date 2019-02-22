<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property integer id
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string biography
 * @property array profiles
 * @property string image
 */
class ProviderResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'biography' => $this->biography,
            'profiles' => $this->profiles,
            'image' => Storage::disk('media')->url($this->image)
        ];
    }
}

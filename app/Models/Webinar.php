<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string title
 * @property string en_title
 * @property string slug
 */
class Webinar extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'slug'
    ];

    #-------------------------------------##   <editor-fold desc="Booting">   ##----------------------------------------------------#

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->en_title;
        });

        static::updating(function ($product) {
            $product->slug = $product->en_title;
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from the en_title of webinar
     *
     * @param $en_title
     */
    public function setSlugAttribute($en_title)
    {
        $this->attributes['slug'] = str_slug($en_title);
    }
    # </editor-fold>
}

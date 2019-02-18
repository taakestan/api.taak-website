<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string title
 * @property string label
 * @property string slug
 * @method static|Webinar create($data)
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
            $product->slug = $product->label;
        });

        static::updating(function ($product) {
            $product->slug = $product->label;
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from the label of webinar
     *
     * @param $label
     */
    public function setSlugAttribute($label)
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($label);
    }
    # </editor-fold>
}

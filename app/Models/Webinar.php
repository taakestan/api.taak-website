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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'provider'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'slug', 'provider_id'
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

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * webinar provider
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property \Illuminate\Support\Collection $webinars
 */
class Provider extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'profiles' => 'array'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * provider  webinars
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webinars()
    {
        return $this->hasMany(Webinar::class);
    }

    # </editor-fold>
}

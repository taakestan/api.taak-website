<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @method static |Provider findOrFail($provider_id)
 * @method static |Provider create(array $data)
 * @property \Illuminate\Support\Collection $webinars
 * @property mixed username
 */
class Provider extends Model {
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
            $this->removeCache();
        });

        static::updating(function ($product) {
            $this->removeCache();
        });
    }

    public function removeCache()
    {
        $cache_path = '/etc/nginx/cache/';
        unlink($cache_path);
    }

    # </editor-fold>

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

    #-------------------------------------##   <editor-fold desc="The Method">   ##----------------------------------------------------#

    /**
     * check provider has webinar or not
     *
     * @return bool
     */
    public function hasWebinar()
    {
        return !!$this->webinars()->exists();
    }

    # </editor-fold>
}

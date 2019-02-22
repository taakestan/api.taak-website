<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property integer id
 * @property \Illuminate\Support\Collection $webinars
 * @method static|User findOrFail($provider_id)
 */
class User extends Authenticatable implements JWTSubject
{
    #-------------------------------------##   <editor-fold desc="JWT Methods">   ##----------------------------------------------------#


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    # </editor-fold>

    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

#-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * hash the password attribute
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    # </editor-fold>
}

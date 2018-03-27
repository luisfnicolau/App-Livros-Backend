<?php

namespace App;

use App\Model\Pricing;
use App\Model\BookCopy;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'token_expires',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_token',
        'facebook_id',
        'facebook_token',
        'token_expires',
        //'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        /*'password',*/
        'remember_token',
        'facebook_token',
        'google_token',
        'app_token',
        'password',
    ];

    public function pricing(){
        return $this->belongsTo(Pricing::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }
}

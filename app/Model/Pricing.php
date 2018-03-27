<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'max_books'
    ];

    const ID_FREE = 1;

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        $attr = $attributes;
        if(array_key_exists('price', $attr))
            $attr['price'] = intval($attributes['price'] * 100);
        return parent::fill($attr);
    }

    public function formatPrice(){
        return number_format($this->price / 100, 2, ',', ' ');;
    }

    public function getPrice(){
        return intval($this->price) / 100;
    }

    public static function validationRules(){
        return [
            'name' => '',
            'price' => 'min:0|numeric',
            'max_books' => 'integer|min:1',
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [];

    public function users(){
        return $this->hasMany(User::class);
    }
}

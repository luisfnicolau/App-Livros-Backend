<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Book extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'isbn', 'author_name', 'cover'
    ];


    /**
    * validation rules for a book (to update, pass @param $update to true)
    */
    public static function validationRules(bool $update = false){
        $update_rules = [
                'title' => '',
                'description' => 'min:3',
                'isbn' => '', // todo validate isbn
                'cover' => 'file|mimetypes:image/bmp,image/png,image/svg+xml,image/tiff,image/jpeg',
            ];
        if($update) return $update_rules;
        return [
            'title' => 'required',
            'description' => 'required|min:3',
            'author_name' => 'required',
            'cover' => $update_rules['cover'],
        ];
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }
}

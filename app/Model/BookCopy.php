<?php

namespace App\Model;

use App\User;
use App\Model\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class BookCopy extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

    protected $table = 'book_copies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'photo', 'price'
    ];


    /**
    * validation rules for a book (to update, pass @param $update to true)
    */
    public static function validationRules(bool $update = false){
        return [
                'message' => 'min:3',
                'price'=>'numeric|max:10000|min:0',
                'photo' => 'file|mimes:image/bmp, image/png, image/svg+xml, image/tiff, image/jpeg',
            ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function available()
    {
        return is_null($this->renter);
    }
}

<?php

namespace App\Model;

use App\User;
use App\Model\BookCopy;
use App\Model\PaymentReversalRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public static $SERVICE_PORCENTAGE_IN_ORDER = 0.1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'rating'
    ];


    /**
    * validation rules for a book (to update, pass @param $update to true)
    */
    public static function validationRules(bool $update = false){
        return [
                'total' => 'min:0|max:10000',
                'rating' => 'min:0|max:10',
            ];
    }

  public function getCreatedAtAttribute($value)
  {
    return new Carbon($value);
  }

  public function getDeliveryDateAttribute($value)
  {
    return new Carbon($value);
  }

  public function getCanceledDateAttribute($value)
  {
    return new Carbon($value);
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function renter()
  {
    return $this->belongsTo(User::class, 'renter_id');
  }

  public function copies()
  {
    return $this->belongsToMany(BookCopy::class);
  }

  public function paymentReversalRequest(){
    return $this->hasMany(PaymentReversalRequest::class, 'order_id');
  }
}

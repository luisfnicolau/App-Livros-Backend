<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentReversalRequest extends Model
{
    //
  protected $fillable = [
    'fulfilled'
  ];

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
  }
}

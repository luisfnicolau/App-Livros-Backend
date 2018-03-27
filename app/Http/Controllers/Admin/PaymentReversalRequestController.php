<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class PaymentReversalRequestController extends AdminBaseController
{
    //

  public function index(){
    $reversals = \App\Model\PaymentReversalRequest::paginate(10);

    return view('admin.reversals.index')->with(compact('reversals'));
  }
}

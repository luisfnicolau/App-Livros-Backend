<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Model\Order;

class OrderController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $orders                = Order::orderBy('created_at', 'desc')->paginate(10);
      $average_rating        = Order::avg('rating');

      $average_delivery_date = Order::all()
          ->reject(function($value){ return $value->delivery_date == null;})
          ->reduce(function($count, $order){
            return $count + $order->delivery_date->diffInDays($order->created_at);
          },0)/Order::count();

      return view('admin.order.index')->with([
                                       'orders' => $orders,
                                       'average_rating' => $average_rating,
                                       'average_delivery_date' => $average_delivery_date,
                                       ]);
    }

    public function canceled(){
      $orders = Order::where('canceled', '=', true)->orderBy('canceled_date', 'desc')->paginate(10);

      return view('admin.order.canceled')->with(compact('orders'));
    }

    public function times(){
      $orders = Order::all();
      $times = array_fill(0, 24, 0);

      foreach ($orders as $item) {
        $index = $item->created_at->hour;
        $times[$index]++;
      }

      return view('admin.order.times')->with(compact('times'));
    }

    public function places(){
      $orders = Order::all();

      $places = array();

      foreach ($orders as $order){
        $places[] = [$order->latitude, $order->longitude];
      }

      return view('admin.order.places')->with(compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

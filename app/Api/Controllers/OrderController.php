<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Model\Order;
use App\Api\Transformers\OrderTransformer;

class OrderController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Order;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new OrderTransformer;
    }
}

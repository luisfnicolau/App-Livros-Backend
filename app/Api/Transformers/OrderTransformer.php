<?php namespace App\Api\Transformers;

use App\Model\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
    public function transform(Order $item)
    {
        return [
            'id'            => (int) $item->id,
            'total'         => (int) $item->total,
            'rating'        => (int) $item->rating,
            'delivery_date' => (string) $item->delivery_date,
            'canceled_date' => (string) $item->canceled_date,
            'created_at'    => (string) $item->created_at,
            'updated_at'    => (string) $item->updated_at,
        ];
    }
}

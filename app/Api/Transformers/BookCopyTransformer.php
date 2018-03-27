<?php namespace App\Api\Transformers;

use App\Model\BookCopy;
use League\Fractal\TransformerAbstract;

class BookCopyTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
    public function transform(BookCopy $item)
    {
        return [
            'id'         => (int)$item->id,
            'created_at' => (string)$item->created_at,
            'updated_at' => (string)$item->updated_at,
        ];
    }
}

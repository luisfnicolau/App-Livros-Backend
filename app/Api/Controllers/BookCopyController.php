<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Model\BookCopy;
use App\Api\Transformers\BookCopyTransformer;

class BookCopyController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new BookCopy;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new BookCopyTransformer;
    }
}

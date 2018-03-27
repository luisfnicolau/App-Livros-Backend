<?php namespace App\Api\Controllers;

use App\Api\Controllers\Controller;
use App\Model\Pricing;
use App\Api\Transformers\PricingTransformer;

class PricingController extends Controller
{
    /**
     * Eloquent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model()
    {
        return new Pricing;
    }

    /**
     * Transformer for the current model.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new PricingTransformer;
    }
}

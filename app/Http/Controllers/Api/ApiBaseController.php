<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
        $this->middleware('apiauth');
    }
}

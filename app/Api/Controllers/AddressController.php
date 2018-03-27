<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{

    public function index(Request $request)
    {
        $message = '';
        if ($request->has('book_id'))
        {
          $addresses = $this->fetchAddressFromBook($request->get('book_id'));
          return response()->json(array('message' => $message, 'addresses' => $addresses));
        }
        $addressId = $request->get('address_id');
        if($addressId){
           if($addresses = DB::table('addresses')->where('id', $addressId)->delete())
               $message = 'success';
           else
               $message = 'error';
            return response()->json(array('message' => $message));
        }
        $userId = $request->get('user_id');
        if($addresses = DB::table('addresses')->where('owner_id', $userId)->get()){
            if($addresses->isEmpty())
                $message = 'empty';
            else
                $message = 'success';
        }
        return response()->json(array('message' => $message, 'addresses' => $addresses->toArray()));
    }

    public function store(Request $request)
    {
        $message = '';

        if($request->get('to_delete')){
            if (DB::table('addresses')->where('id', $request->get('id'))->delete())
                $message = 'deleted';
            else
                $message = 'error on delete';
            return response()->json(array('message' => $message));
        }

        if ($request->get('list'))
        {
          $addressesIds = $request->get('addressIds');
          for ($i = 0; $i < count($addressesIds); $i++)
          {
            $addresses[$i] =  DB::table('addresses')->where('owner_id', $addressesIds[$i])->get();
          }
          return response()->json(array('message' => $message, 'addresses' => $addresses[0]));
          // return array('addresses' => $addresses);
        }

        $userId = $request->get('owner_id');

        $cep = $request->get('cep');
        $complement = $request->get('complement');
        $city = $request->get('city');
        $street = $request->get('street');
        $number = $request->get('number');
        $neighborhood = $request->get('neighborhood');
        $uf = $request->get('uf');

        $address = new Address();
        $address->cep = $cep;
        $address->complement = $complement;
        $address->city = $city;
        $address->street = $street;
        $address->number = $number;
        $address->uf = $uf;
        $address->neighborhood = $neighborhood;
        $address->owner_id = $userId;

        if($address->save())
            $message = 'sucess';
        else
            $message = 'error when save';

        return response()->json(array('message' => $message, 'address' => $address->toArray()));
    }

    public function fetchAddressFromBook($book_id)
    {
      $book = Book::where('id', $book_id)->first();
      $ids = preg_split('/:/', $book->address_ids, -1, PREG_SPLIT_NO_EMPTY);
      $addressString = '';
      $addresses = null;
      for ($i = 0; $i < count($ids); $i++)
      {
        if(DB::table('addresses')->where('id', $ids[$i])->first())
        {
          $addresses[$i] = DB::table('addresses')->where('id', $ids[$i])->first();
          $addressString = $addressString.':'.$ids[$i];
        }
      }
      $addressString = substr($addressString, 1, strlen($addressString));
      $book->address_ids = $addressString;
      $book->save();
      return $addresses;
    }
}

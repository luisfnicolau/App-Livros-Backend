<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Book;
use App\Model\Address;
use Illuminate\Support\Facades\DB;
use Storage;

class BookController extends Controller
{

    public function index(Request $request)
    {
        $message = 'error';
        if ($request->has('owner_id')) {
            $ownderId = $request->get('owner_id');
            //$books = DB::table('books')->where('seller_id', $ownderId)->paginate(25);
            $books = Book::where('seller_id', $ownderId)->paginate(25);
            $message = 'found for user';
        } else{
            if ($request->has('q')) {
                $q = '%' . $request->get('q') . '%';
                $books = Book::where('title', 'like', $q)
                         ->orWhere('author', 'like', $q)
                         ->orWhere('description', 'like', $q)
                         ->orWhere('isActive', '1')
                         ->paginate(25);
                $message = 'found query';
        }
            else {
                $books = Book::orderBy(DB::raw('RAND()'))->where('isActive', '1')
                ->paginate(25);
                $message = 'found random';
            }
        }
        for($bookCounter = 0; $bookCounter < count($books); $bookCounter++)
          $this->checkBooksAddress($books[$bookCounter]);
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $message = 'error';
        if($request->has('message') && $request->get('message') == 'get'){
            $booksId = $request->get('book_ids');
            if($books = DB::table('books')->whereIn('id', $booksId)->get()){
                $message = 'success';
            }
            //Can't use Facades
            //$this->checkBooksAddress($books);
            return response()->json(array('data' => $books));
        }
        if($request->get('to_delete')){
            if (DB::table('books')->where('id', $request->get('id'))->delete())
                $message = 'success';
            else
                $message = 'error';
            return response()->json(array('message' => $message));
        }
        $title = $request->get('title');
        $description = $request->get('description');
        $isbn = $request->get('isbn');
        $authorName = $request->get('author');
        $category = $request->get('category');
        $synopsis = $request->get('synopsis');
        $ownerId = $request->get('seller_id');
        $buyQuantity = $request->get('buy_quantity');
        $rentQuantity = $request->get('rent_quantity');
        $booksAddressIds = $request->get('address_ids');
        $isActive = 1;

        if(!$buyQuantity && !$rentQuantity)
            $isActive = 0;

        $buyPrice = $request->get('buy_price');
        $rentPrice = $request->get('rent_price');
        $rentDuration = $request->get('rent_duration');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $rentRadius = $request->get('rent_radius');
        $imageUrl = $request->get('cover');

        if(Book::where('id', $request->get('id'))->exists()){
            $books = Book::where('id', $request->get('id'))->first();
        } else {
            $books = new Book();
        }
        $books->title = $title;
        $books->description = $description;
        $books->isbn = $isbn;
        $books->author = $authorName;
        $books->category = $category;
        $books->synopsis = $synopsis;
        $books->seller_id = $ownerId;
        $books->buy_quantity = $buyQuantity;
        $books->rent_quantity = $rentQuantity;
        $books->buy_price = $buyPrice;
        $books->rent_price = $rentPrice;
        $books->rent_duration = $rentDuration;
        $books->latitude = $latitude;
        $books->longitude = $longitude;
        $books->rent_radius = $rentRadius;
        $books->isActive = $isActive;
        $books->cover = $imageUrl;
        $books->address_ids = $booksAddressIds;

        if($books->save()){
            $message = 'sucess';
        }
        else
            $message = 'error';

//        return response()->json(array('message' => $message, 'transaction' => $books->toArray()));
        return response()->json($books);
    }

    public function isbn($isbn)
    {
        $book = Book::where('isbn', $isbn)->first();
        if(!$book || !$book->exists)
            return response()->json(['book_not_found'], 404);
        return response()->json($book->toArray(), 200);
    }

    public function checkBooksAddress($bookToCheck)
    {
        $ids = preg_split('/:/', $bookToCheck->address_ids, -1, PREG_SPLIT_NO_EMPTY);
        $addressString = '';
        for ($i = 0; $i < count($ids); $i++)
        {
          if($address = DB::table('addresses')->where('id', $ids[$i])->first())
          {
            $addressString = $addressString.':'.$ids[$i];
          }
        }
        $addressString = substr($addressString, 1, strlen($addressString));
        $bookToCheck->address_ids = $addressString;
        $bookToCheck->save();
    }
}

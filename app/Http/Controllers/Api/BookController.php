<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends ApiBaseController
{

    public function index(Request $request)
    {
        if ($request->has('q')) {
            $q = '%' . $request->get('q') . '%';
            $books = Book::where('title', 'like', $q)
                         ->orWhere('author_name', 'like', $q)
                         ->orWhere('description', 'like', $q)
                         ->paginate(25);
        }
        else {
            $books = Book::orderBy(DB::raw('RAND()'))->paginate(25);
        }
        return response()->json($books, 200);
    }

    public function isbn($isbn)
    {
        $book = Book::where('isbn', $isbn)->first();
        if(!$book || !$book->exists)
            return response()->json(['book_not_found'], 404);
        return response()->json($book->toArray(), 200);
    }

}

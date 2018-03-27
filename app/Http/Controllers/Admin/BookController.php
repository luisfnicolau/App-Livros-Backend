<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SaveBooksFromOpenLibrary;
use App\Model\Book;
use App\Managers\OpenLibrary;
use Illuminate\Http\Request;
use Validator;

class BookController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        if($query==""){
            $books = Book::paginate(15);
        }else{
            $books = Book::where('title', 'like', "%$query%")
                            ->orWhere('author_name', 'like', "%$query%")
                            ->paginate(15);
        }
        return view('admin.book.list')->with([
                                     'books'=> $books]);
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
        $validator = Validator::make($request->all(), Book::validationRules(true));

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $b = new Book($request->all());
        $b->save();
        return redirect(route('admin.book.list'))->back();
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
        $book = Book::findOrFail($id);
        // dd($book->getMedia());
        return view('admin.book.edit')->with('book', $book);
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
        $b = Book::findOrFail($id);
        // dd($request->file('cover'));
        $validator = Validator::make(
                                     $request->all(),
                                     Book::validationRules(true)
                                     );

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $b->fill($request->all());
        if($request->has('cover')){
            $b->clearMediaCollection('cover');
            $b->addMediaFromRequest('cover')
                ->toCollection('cover')
                ->toMediaLibrary();
        }
        $b->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $b = Book::findOrFail($id);
        $b->delete();
        return redirect()->back()->withInfo("Livro $b->title deletado.");
    }

    public function crawlApi(Request $request)
    {
        $openLibrary = new OpenLibrary();
        $message = "";
        if($request->has('isbn')) {
            $isbn = $request->get('isbn');
            $book = $openLibrary->book($isbn);
            if (Book::where('isbn', $isbn)->count() == 0) {
                $book->save();
                $message = "Livro $book->title salvo!";
            } else {
                $message = "O livro de isbn $isbn já existe no banco de dados";
            }
        } elseif ($request->has('q')) {
            $query = $request->get('q');
            $total = $openLibrary->search($query)['total'];
            if ($total > 0) {
                dispatch(new SaveBooksFromOpenLibrary($query));
                $message = "Adicionada a tarefa interna de salvar os $total"
                            . " livros encontrados na busca '$query'!";
            } else {
                $message = "Nenhum livro encontrado. Nada para fazer.";
            }
        } else {
            abort(400);
        }
        return redirect()->back()->with('info', $message);
    }
}

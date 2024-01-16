<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = Book::with(['category','author','publisher'])->orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($books)->editColumn('cover', function(Book $book) {
            return strpos($book->cover, "books/") === 0 ? route('root') ."/storage/". $book->cover : $book->cover;
        })->make(true);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' =>'List Book',
            'data'    => $datatables
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "isbn" => 'required|unique:books',
            "title"=> 'required|string|max:255',
            "year"=> 'required|numeric',
            "publisher_id"=> 'required',
            "author_id"=> 'required',
            "category_id"=> 'required',
            "qty"=> 'required|numeric',
            "price"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field form Book tidak boleh kosong!',
                'data'   => $validator->errors()
            ],400);

        } else {

            if ($request->hasFile('cover')) {
                $image_path = $request->file('cover')->store('books', 'public');
            }
            $book = Book::create([
                "isbn" => $request->input('isbn'),
                "title"=> $request->input('title'),
                "cover" => $image_path??$request->input('cover'),
                "year"=> $request->input('year'),
                "publisher_id"=> $request->input('publisher_id'),
                "author_id"=> $request->input('author_id'),
                "category_id"=> $request->input('category_id'),
                "qty"=> $request->input('qty'),
                "price"=> $request->input('price')
            ]);

            // $book = Book::create($request->all());

            if ($book) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Book Berhasil Disimpan!',
                    'data' => $book
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Book Gagal Disimpan!',
                ], 400);
            }

        }
    }

    public function show($id)
    {
        // $data = Publisher::where('id', $id)->with('detail')->get();
        // return response()->json([
        //     'success' => true,
        //     'message' =>'Detail Publisher',
        //     'data'    => $data
        // ], 200);
        $book = Book::with(['publisher', 'category', 'author'])->find($id);

        if ($book) {
            # code...
            $book->cover =  strpos($book->cover, "books/") === 0 ? route('root') ."/storage/". $book->cover : $book->cover;
        }
       

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "isbn" => 'required|unique:books,isbn,'.$id,
            "title"=> 'required|string|max:255',
            "year"=> 'required|numeric',
            "publisher_id"=> 'required',
            "author_id"=> 'required',
            "category_id"=> 'required',
            "qty"=> 'required|numeric',
            "price"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => "{$validator->errors()} !",
                'data'   => $request->all()
            ],400);

        } else {

            $book  = Book::find($id);

            if ($request->hasFile('cover')) {
                $image_path = $request->file('cover')->store('books', 'public');
            }
            
            $book->isbn = $request->input('isbn');
            $book->title = $request->input('title');
            $book->cover = $image_path??$request->input('cover');
            $book->year = $request->input('year');
            $book->publisher_id = $request->input('publisher_id');
            $book->author_id = $request->input('author_id');
            $book->category_id = $request->input('category_id');
            $book->qty = $request->input('qty');
            $book->price = $request->input('price');

            if ( $book->save()) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Book Berhasil Diupdate!',
                    'data' => $book
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Book Gagal Diupdate!',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book  = Book::find($id);
        $book->delete();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Book Berhasil Dihapus!',
        ], 200);
    }
}

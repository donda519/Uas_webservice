<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authors = Author::with('books')->orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($authors)->addIndexColumn()->editColumn('created_at', function(Author $author) {
            return date("j F Y, H:i:s", strtotime($author->created_at));
        })->make(true);

        // $data = Author::filter(compact('request'))->get();

        // if (request('page')) {
        // $currentPage = request('page', 1); // Get the current page from the request query parameters

        // $collection = new Collection($data); // Convert the data to a collection

        // $paginatedData = new LengthAwarePaginator(
        //     $collection->forPage($currentPage, self::PERPAGE),
        //     $collection->count(),
        //     self::PERPAGE,
        //     $currentPage,
        //     ['path' => url('/api/authors')]
        // );

        //     return response()->json($paginatedData)->setEncodingOptions(JSON_NUMERIC_CHECK);

            // $data = Author::paginate(15);
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' =>'List Author',
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
            "name" => 'required|max:255',
            "email"=> 'required|string|email|max:255|unique:authors',
            "phone_number"=> 'numeric|digits_between:1,13',
            "address"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            // if ($request->hasFile('image')) {
            //     $image_path = $request->file('image')->store('authors', 'public');
            // }
    
            // $author = Author::create([
            //     'name' => $request->input('name'),
            //     'description' => $request->input('description'),
            //     'image' => $image_path??$request->input('image'),
            //     'price' => $request->input('price'),
            // ]);

            $author = Author::create($request->all());


            if ($author) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Author Berhasil Disimpan!',
                    'data' => $author
                ], 201);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Author Gagal Disimpan!',
                ], 400);
            }

        }
    }

    public function show($id)
    {
        // $data = Author::where('id', $id)->with('detail')->get();
        // return response()->json([
        //     'success' => true,
        //     'message' =>'Detail Author',
        //     'data'    => $data
        // ], 200);

        return response()->json(Author::with('books')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            "name" => 'required|max:255',
            "email"=> 'required|string|email|max:255|unique:authors,email,'.$id,
            "phone_number"=> 'numeric|digits_between:1,13',
            "address"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $author  = Author::find($id);
            

            // if ($request->hasFile('image')) {
            //     $image_path = $request->file('image')->store('authors', 'public');
            // }

            

            if ( $author->update($request->all())) {

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Author Berhasil Diupdate!',
                    'data' => $author
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Author Gagal Diupdate!',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author  = Author::find($id);

        $author->books()->delete();
        $author->delete();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Author Berhasil Dihapus!',
        ], 200);
    }
}

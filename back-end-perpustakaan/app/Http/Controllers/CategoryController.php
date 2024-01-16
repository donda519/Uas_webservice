<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categorys = Category::with('books')->orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($categorys)->addIndexColumn()->editColumn('created_at', function(Category $category) {
            return date("j F Y, H:i:s", strtotime($category->created_at));
        })->make(true);

        // $data = Category::filter(compact('request'))->get();

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

            // $data = Category::paginate(15);
        return response()->json([
            'success' => true,
            'message' =>'List Category',
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
            "name" => 'required|max:255'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $category = Category::create($request->all());


            if ($category) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Category Berhasil Disimpan!',
                    'data' => $category
                ], 201);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Gagal Disimpan!',
                ], 400);
            }

        }
    }

    public function show($id)
    {
        // $data = Category::where('id', $id)->with('detail')->get();
        // return response()->json([
        //     'success' => true,
        //     'message' =>'Detail Category',
        //     'data'    => $data
        // ], 200);

        return response()->json(Category::with('books')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            "name" => 'required|max:255'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $category  = Category::find($id);

            if ( $category->update($request->all())) {

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Category Berhasil Diupdate!',
                    'data' => $category
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Gagal Diupdate!',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category  = Category::find($id);
        $category->delete();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Category Berhasil Dihapus!',
        ], 200);
    }
}

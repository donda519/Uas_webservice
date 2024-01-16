<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $publishers = Publisher::orderBy('id', 'desc')->with('books')->filter(compact('request'))->get();
        $datatables = datatables()->of($publishers)->addIndexColumn()->editColumn('created_at', function(Publisher $publisher) {
            return date("j F Y, H:i:s", strtotime($publisher->created_at));
        })->make(true);

        // $data = Publisher::filter(compact('request'))->get();

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

            // $data = Publisher::paginate(15);
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' =>'List Publisher',
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
            "email"=> 'required|string|email|max:255|unique:publishers',
            "phone_number"=> 'required|numeric|digits_between:1,13',
            "address"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $publisher = Publisher::create($request->all());


            if ($publisher) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Publisher Berhasil Disimpan!',
                    'data' => $publisher
                ], 201);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Publisher Gagal Disimpan!',
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

        return response()->json(Publisher::with('books')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            "name" => 'required|max:255',
            "email"=> 'required|string|email|max:255|unique:publishers,email,'.$id,
            "phone_number"=> 'required|numeric|digits_between:1,13',
            "address"=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $publisher  = Publisher::find($id);

            if ( $publisher->update($request->all())) {

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Publisher Berhasil Diupdate!',
                    'data' => $publisher
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Publisher Gagal Diupdate!',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publisher  = Publisher::find($id);
        $publisher->books()->delete();
        $publisher->delete();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Publisher Berhasil Dihapus!',
        ], 200);
    }
}

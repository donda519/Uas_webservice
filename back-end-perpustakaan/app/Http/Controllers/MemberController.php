<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $members = Member::orderBy('id', 'desc')->filter(compact('request'))->get();
        $datatables = datatables()->of($members)->addIndexColumn()->editColumn('created_at', function(Member $member) {
            return date("j F Y, H:i:s", strtotime($member->created_at));
        })->make(true);

        // $data = Member::filter(compact('request'))->get();

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

            // $data = Member::paginate(15);
        return response()->json([
            'success' => true,
            'message' =>'List Member',
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
            'gender' => 'required|in:L,P',
            "phone_number"=> 'required|numeric|digits_between:1,13',
            "address"=> 'required',
            "email"=> 'required|string|email|max:255|unique:members'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $member = Member::create($request->all());


            if ($member) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Member Berhasil Disimpan!',
                    'data' => $member
                ], 201);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Member Gagal Disimpan!',
                ], 400);
            }

        }
    }

    public function show($id)
    {
        // $data = Member::where('id', $id)->with('detail')->get();
        // return response()->json([
        //     'success' => true,
        //     'message' =>'Detail Member',
        //     'data'    => $data
        // ], 200);

        return response()->json(Member::with('transactions.transactionDetail')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            "name" => 'required|max:255',
            'gender' => 'required|in:L,P',
            "phone_number"=> 'required|numeric|digits_between:1,13',
            "address"=> 'required',
            "email"=> 'required|string|email|max:255|unique:members,email,'.$id
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {

            $member  = Member::find($id);

            if ( $member->update($request->all())) {

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Member Berhasil Diupdate!',
                    'data' => $member
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Member Gagal Diupdate!',
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member  = Member::find($id);
        $member->delete();
        
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Member Berhasil Dihapus!',
        ], 200);
    }
}

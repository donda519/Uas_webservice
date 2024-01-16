<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['member','transactionDetail.book'])->orderBy('id', 'desc')->filter(compact('request'))->get();
        foreach ($transactions as $key => $transaction) {
            $transaction->namaPeminjam = $transaction->member->name;

            $startDate = new DateTime($transaction->date_start);
            $endDate = new DateTime($transaction->date_end);
            $interval = $startDate->diff($endDate);
            $days = $interval->days;
            $transaction->lamaPinjam = $days;

            $transaction->totalBuku = 0;
            $transaction->totalBayar = 0;
            foreach ($transaction->transactionDetail as $k => $detail) {
                $transaction->totalBuku += $detail->qty;
                $transaction->totalBayar += ($detail->book->price * $detail->qty);
            }
        }

        $datatables = datatables()->of($transactions)->editColumn('status', function(Transaction $transaction) {
            return $transaction->status == 'true' ? 'Sudah Dikembalikan' : 'Belum Dikembalikan';
        })->editColumn('totalBayar', function(Transaction $transaction) {
            return "Rp " . number_format($transaction->totalBayar, 0, ".", ".");
        })->editColumn('created_at', function(Transaction $transaction) {
            return date("j F Y, H:i:s", strtotime($transaction->created_at));
        })->make(true);

        // $data = Transaction::filter(compact('request'))->get();

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

            // $data = Transaction::paginate(15);
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' =>'List Transaction',
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
            "anggota" => 'required',
            "date_start"=> 'required',
            "date_end"=> 'required',
            "books"=>'required|array'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],400);

        } else {

            // $transaction = Transaction::create($request->all());
            $transaction = new Transaction([
                'member_id' => $request->input('anggota'),
                'date_start' => $request->input('date_start'),
                'date_end'=> $request->input('date_end'),
                'status' => "false"
            ]);

            $transaction->save();
            
            foreach($request->input('books') as $book){

                $detail = new TransactionDetail([
                    'book_id' => $book,
                    'qty'=> 1
                ]);

                $transaction->transactionDetail()->save($detail);

                $get_book = Book::find($book);
                $get_book->qty -=  1;
                $get_book->save();
            }

            // DB::transaction(function () use ($request) {
            //     try {
            //         $data = $request->all();
    
                    
                    
            //         // Jika semuanya berhasil, commit transaksi
            //         DB::commit();
            //     } catch (\Exception $e) {
            //         // dd($e);
            //         // Jika terjadi kesalahan, rollback transaksi
            //         DB::rollback();
            //         // Handle kesalahan sesuai kebutuhan Anda
            //         return response()->json([
            //             'success' => false,
            //             'message' => 'Transaction Gagal Disimpan!',
            //             'data' => $e
            //         ], 400);
            //     }
            // });

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Transaction Berhasil Disimpan!',
                'data' => []
            ], 201);
            

        }
    }

    public function show($id)
    {
        // $data = Transaction::where('id', $id)->with('detail')->get();
        // return response()->json([
        //     'success' => true,
        //     'message' =>'Detail Transaction',
        //     'data'    => $data
        // ], 200);

        return response()->json(Transaction::with('member')->with('transactionDetail')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // var_dump($request->all());
        $validator = Validator::make($request->all(), [
            "anggota" => 'required',
            "date_start"=> 'required',
            "date_end"=> 'required',
            "books"=>'required|array'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua field wajib diisi!',
                'data'   => $validator->errors()
            ],400);

        } else {

            $transaction  = Transaction::find($id);

            DB::transaction(function () use ($request, $transaction) {
                try {
                    $transaction->load('transactionDetail');
                    $data = $request->all();
                    $transaction->member_id = $data['anggota'];
                    $transaction->date_start = $data['date_start'];
                    $transaction->date_end = $data['date_end'];
                    
    
                    $books = $data['books']??[];
    
                    foreach ($transaction->transactionDetail as $key => $value) {
                        $index = array_search(strval($value->book_id),$books);
                        if (is_int($index)) {
    
                            // mengembalikan buku
                            if ($transaction->status == 'false' && $data['status'] == 'true') {
                                $update_book = Book::find($value->book_id);
                                $update_book->qty +=  1;
                                $update_book->save();
                            }
    
                            array_splice($books,$index,1);
                        }
                        else{
                            
    
                            DB::table('transaction_details')->where('id', $value->id)->delete();
                            
                            $update_book = Book::find($value->book_id);
                            $update_book->qty +=  1;
                            $update_book->save();
    
                        }
                    }
    
                    $transaction->status = $data['status'];
                    $transaction->save();
    
                    foreach($books as $book){
    
                        $detail = new TransactionDetail([
                            'book_id' => $book,
                            'qty'=> 1
                        ]);
    
                        $transaction->transactionDetail()->save($detail);
    
                        $get_book = Book::find($book);
                        $get_book->qty -=  1;
                        $get_book->save();
                    }
                                
                    // Jika semuanya berhasil, commit transaksi
                    DB::commit();

                } catch (\Exception $e) {
                    // dd($e);
                    // Jika terjadi kesalahan, rollback transaksi
                    DB::rollback();
                    // Handle kesalahan sesuai kebutuhan Anda
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaction Gagal Diupdate!',
                    ], 400);
                }
            });

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Transaction Berhasil Diupdate!',
                'data' => []
            ], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction  = Transaction::find($id);

        DB::transaction(function () use ($transaction) {
            try {

                $transaction->load('transactionDetail.book');

                foreach ($transaction->transactionDetail as $key => $value) {
                        $update_book = Book::find($value->book_id);
                        if($transaction->status == 'false'){
                            $update_book->qty +=  1;
                        }
                        $update_book->save();
                }

                $transaction->transactionDetail()->delete();
                $transaction->delete();
            
                // Jika semuanya berhasil, commit transaksi
                DB::commit();
            } catch (\Exception $e) {
                dd($e);
                // Jika terjadi kesalahan, rollback transaksi
                DB::rollback();
                // Handle kesalahan sesuai kebutuhan Anda

                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Gagal Dihapus!',
                ], 400);
            }
        });

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Transaction Berhasil Dihapus!',
        ], 200);
        
        
    }
}

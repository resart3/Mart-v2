<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\FamilyCard;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Halaman Transaksi Iuran';
        $detail_user = FamilyCard::with('family_members')->where('nomor',session()->get('user')->family_card_id)->get();
        $rt_rw = explode("/",$detail_user[0]->rt_rw);
        $familyCard = FamilyCard::where('rt_rw','like',$rt_rw[0].'%')->get();
        $transactions = Transaction::with('familyCard')->get();
        dd($transactions);
        return view('transaction.index', compact('transactions', 'title'));
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
        //
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
        //
        $transactions = Transaction::find($id);

        if(isset($transactions) == TRUE){
            return response()->json($transactions);
        }else{
            return response()->json("Data Transaction Tidak Ditemukan!");
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $transactions = Transaction::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('transaction.index')->with('success','Data Transaksi berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\FamilyCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // dd(asset('storage'));
        $title = 'Halaman Transaksi Iuran';
        if (session()->get('user')->role == 'admin_rt') {
            $familyCard = FamilyCard::where('rt_rw',session()->get('user')->rt_rw)->get();
        }
        else if (session()->get('user')->role == 'admin_rw') {
            $familyCard = FamilyCard::where('rt_rw','like','%'.explode("/",session()->get('user')->rt_rw)[1])->get();
        }
        else{
            $familyCard = FamilyCard::get();
        }
        
        foreach ($familyCard as $data) {
            $nomor[] = $data->nomor;
        }

        $transactions = Transaction::whereIn('family_card_id',$nomor)->get();

        foreach($transactions as $data){
            $transaction_family_card[] = $data->family_card_id;
        }

        $familyHeadName = DB::table('family_members')
        ->whereIn('family_card_id', $transaction_family_card)
        ->where('isFamilyHead', 1)
        ->select('nama', 'family_card_id')        
        ->get();

        $arrDataTransaksi = [];
        foreach($transactions as $key){
            $tempData["family_card_id"] = $key->family_card_id;
            foreach($familyHeadName as $data){
                if($data->family_card_id == $key->family_card_id){
                    $tempData["nama"] = $data->nama;
                    break;
                }
            }
            $tempData["id"] = $key->id;
            $tempData["jumlah"] = $key->jumlah;
            $tempData["tahun"] = $key->tahun;
            $tempData["bulan"] = $key->bulan;
            $tempData["status"] = $key->status;
            $tempData["receipt"] = $key->receipt;
            array_push($arrDataTransaksi, $tempData);
        }
        
        // echo"<pre>";
        // print_r($arrDataTransaksi);

        // foreach($arrDataTransaksi as $data){
        //     echo"<pre>";
        //     print_r($data['tahun']);
        // }
        // die();

        return view('transaction.index', compact('arrDataTransaksi', 'title'));
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
        $updateData = $request->all();
        $transaction = Transaction::FindOrFail($id);

        $transaction->status = $updateData['status'];
        $transaction->save();

        // return response()->json($transaction);
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

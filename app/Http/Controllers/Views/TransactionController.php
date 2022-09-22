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
        $title = 'Halaman Transaksi Iuran';
        $family_card = FamilyCard::with('with_family_head')->orderBy('created_at', 'desc')->get();
        // if (session()->get('user')->role == 'admin_rt') {
        //     $familyCard = FamilyCard::where('rt_rw',session()->get('user')->rt_rw)->get();
        // }
        // else if (session()->get('user')->role == 'admin_rw') {
        //     $familyCard = FamilyCard::where('rt_rw','like','%'.explode("/",session()->get('user')->rt_rw)[1])->get();
        // }
        // else{
        //     $familyCard = FamilyCard::get();
        // }
        // //menyimpan nomor kk yang se rt atau se rw
        // foreach ($familyCard as $data) {
        //     $nomor[] = $data->nomor;
        // }
        // $transactions = Transaction::whereIn('family_card_id',$nomor)->get();
        // if (count($transactions) != 0) {
        //     foreach($transactions as $data){
        //         $transaction_family_card[] = $data->family_card_id;
        //     }
    
        //     $familyHeadName = DB::table('family_members')
        //     ->whereIn('family_card_id', $transaction_family_card)
        //     ->where('isFamilyHead', 1)
        //     ->select('nama', 'family_card_id')        
        //     ->get();
    
        //     $arrDataTransaksi = [];
        //     foreach($transactions as $key){
        //         $tempData["family_card_id"] = $key->family_card_id;
        //         foreach($familyHeadName as $data){
        //             if($data->family_card_id == $key->family_card_id){
        //                 $tempData["nama"] = $data->nama;
        //                 break;
        //             }
        //         }
        //         $tempData["id"] = $key->id;
        //         $tempData["jumlah"] = $key->jumlah;
        //         $tempData["tahun"] = $key->tahun;
        //         $tempData["bulan"] = $key->bulan;
        //         $tempData["status"] = $key->status;
        //         $tempData["receipt"] = $key->receipt;
        //         array_push($arrDataTransaksi, $tempData);
        //     }
        // }else{
        //     $arrDataTransaksi = [];
        // }

        return view('transaction.index', compact('family_card', 'title'));
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
     * @param  int  $nomor
     * * @param  string  $tahun
     * @return \Illuminate\Http\Response
     */
    public function show($nomor)
    {

    }

    public function show_transaction($nomor, $tahun)
    {
        $title = 'Detail Transaksi ('. $nomor .')';
        $array_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
                "September", "Oktober", "November", "Desember"];
        $array_data_transaksi = [];

        $get_total_iuran = DB::table('lands')
        ->join('categories', 'lands.category_id', '=', 'categories.id')
        ->where('lands.family_card_id', $nomor)
        ->select(DB::raw('SUM(categories.amount) as total_amount'))
        ->get();

        $get_created_date = DB::table('family_cards')
        ->where('nomor', $nomor)
        ->select('created_at')        
        ->get();
        $get_created_year = substr($get_created_date[0]->created_at, 0, 4);
        $get_created_month = substr($get_created_date[0]->created_at, 5, 2);

        $get_transaction = DB::table('lands')
        ->join('transactions', 'transactions.family_card_id', '=', 'lands.family_card_id')
        ->join('categories', 'lands.category_id', '=', 'categories.id')
        ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
        ->where('lands.family_card_id', $nomor)
        ->where('transactions.tahun', $tahun)
        ->select('lands.family_card_id', DB::raw('SUM(categories.amount) as total_amount'),
            'transactions.bulan', 'transactions.tahun', 'transactions.status', 'family_cards.created_at')
        ->groupBy('lands.family_card_id', 'transactions.bulan', 'transactions.tahun', 
            'family_cards.created_at', 'transactions.status')
        ->get();

        $cek_transaction = $get_transaction->first();

        if(isset($cek_transaction) == false){
            $get_land = DB::table('lands')
            ->where('family_card_id', $nomor)
            ->select('*')
            ->get();

            $cek_land = $get_land->first();
            if(isset($cek_land) == false){
                dd("Data Land Belum Terdaftar");
            }else{
                if($tahun < $get_created_year){
                    foreach($array_bulan as $bulan){
                        $tempData["jumlah"] = 0;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $bulan;
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($array_data_transaksi, $tempData);
                    }
                }elseif($tahun == $get_created_year){
                    for($i = 0; $i < (int)$get_created_month - 1; $i++){
                        $tempData["jumlah"] = 0;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $array_bulan[$i];
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($array_data_transaksi, $tempData);
                    }

                    for($i = (int)$get_created_month - 1; $i < count($array_bulan); $i++){
                        $tempData["jumlah"] = $get_total_iuran->first()->total_amount;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $array_bulan[$i];
                        $tempData["status"] = "Belum Membayar";
                        array_push($array_data_transaksi, $tempData);
                    }
                }else{
                    foreach($array_bulan as $bulan){
                        $tempData["jumlah"] = $get_total_iuran->first()->total_amount;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $bulan;
                        $tempData["status"] = "Belum Membayar";
                        array_push($array_data_transaksi, $tempData);
                    }
                }

                dd($array_data_transaksi);
            }
        }else{
            if($tahun == $get_created_year){
                for($i = 0; $i < (int)$get_created_month - 1; $i++){
                    $tempData["jumlah"] = 0;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $array_bulan[$i];
                    $tempData["status"] = "Tidak Tersedia";
                    array_push($array_data_transaksi, $tempData);
                    unset($array_bulan[$i]);
                }

                foreach($get_transaction as $transaction){
                    $tempData["jumlah"] = $transaction->total_amount;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $transaction->bulan;
                    $tempData["status"] = $transaction->status;
                    array_push($array_data_transaksi, $tempData);

                    foreach($array_bulan as $bulan){
                        if($bulan == $transaction->bulan){
                            if (($key = array_search($bulan, $array_bulan)) !== false) {
                                unset($array_bulan[$key]);
                            }
                        }
                    }
                }

                foreach($array_bulan as $bulan){
                    $tempData["jumlah"] = $get_total_iuran->first()->total_amount;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $bulan;
                    $tempData["status"] = "Belum Membayar";
                    array_push($array_data_transaksi, $tempData);
                }
            }else{
                foreach($get_transaction as $transaction){
                    $tempData["jumlah"] = $transaction->total_amount;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $transaction->bulan;
                    $tempData["status"] = $transaction->status;
                    array_push($array_data_transaksi, $tempData);

                    foreach($array_bulan as $bulan){
                        if($bulan == $transaction->bulan){
                            if (($key = array_search($bulan, $array_bulan)) !== false) {
                                unset($array_bulan[$key]);
                            }
                        }
                    }
                }

                foreach($array_bulan as $bulan){
                    $tempData["jumlah"] = $get_total_iuran->first()->total_amount;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $bulan;
                    $tempData["status"] = "Belum Membayar";
                    array_push($array_data_transaksi, $tempData);
                }
            }
        }

        $array_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
            "September", "Oktober", "November", "Desember"];
        $arrDataTransaksiSorted = [];

        foreach($array_bulan as $bulan){
            foreach($array_data_transaksi as $data){
                if($data["bulan"] == $bulan){
                    array_push($arrDataTransaksiSorted, $data);
                }
            }
        }

        return view('transaction/transaction_detail', compact('title', 'arrDataTransaksiSorted', 'nomor'));
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

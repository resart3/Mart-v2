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

        $getTransaction = DB::table('transactions')
        ->where('family_card_id', $nomor)
        ->select('*')
        ->get();
        
        $cekTransaction = $getTransaction->first();
        
        $createDate = DB::table('family_cards')
        ->where('nomor', $nomor)
        ->select('created_at')        
        ->get();
        $getCreatedYear = substr($createDate[0]->created_at, 0, 4);
        $getCreatedMonth = substr($createDate[0]->created_at, 5, 2);
        

        $dataLand = DB::table('lands')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->where('family_card_id', $nomor)
        ->select('categories.amount')        
        ->get();
        $dataLandStatus = $dataLand->first();

        if(isset($dataLandStatus) == false){
            dd("Tidak ada land data");
        }else{
            $jumlahIuran = 0;
            foreach($dataLand as $data){
                $jumlahIuran = $jumlahIuran + $data->amount;
            }

            $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
                "September", "Oktober", "November", "Desember"];
            $arrDataTransaksi = [];
            $arrDataTransaksiSorted = [];

            if(isset($cekTransaction) == false){
                if($getCreatedYear == $tahun){
                    for($i = 0; $i < (int)$getCreatedMonth - 1; $i++){
                        $tempData["jumlah"] = 0;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $arrBulan[$i];
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }

                    for($i = (int)$getCreatedMonth - 1; $i < count($arrBulan); $i++){
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $arrBulan[$i];
                        $tempData["status"] = "Belum Membayar";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }
                }elseif($getCreatedYear < $tahun){
                    foreach($arrBulan as $bulan){
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $bulan;
                        $tempData["status"] = "Belum Membayar";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }
                }else{
                    foreach($arrBulan as $bulan){
                        $tempData["jumlah"] = 0;
                        $tempData["tahun"] = $tahun;
                        $tempData["bulan"] = $bulan;
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }
                }
            }else{
                foreach($getTransaction as $data){
                    $tempData["jumlah"] = $data->jumlah;
                    $tempData["tahun"] = $data->tahun;
                    $tempData["bulan"] = $data->bulan;
                    $tempData["status"] = $data->status;
                    array_push($arrDataTransaksi, $tempData);
                }

                $counter = 0;
                foreach($arrBulan as $bulan){
                    $found = 0;
                    foreach($arrDataTransaksi as $data){
                        if($data["bulan"] == $bulan){
                            $found = 1;
                            break;
                        }
                    }

                    if($found == 0){
                        if($counter < ((int)$getCreatedMonth - 1)) {
                            $tempData["jumlah"] = 0;
                            $tempData["tahun"] = $tahun;
                            $tempData["bulan"] = $bulan;
                            $tempData["status"] = "Tidak Tersedia";
                            array_push($arrDataTransaksi, $tempData);    
                        }else{
                            $tempData["jumlah"] = $jumlahIuran;
                            $tempData["tahun"] = $tahun;
                            $tempData["bulan"] = $bulan;
                            $tempData["status"] = "Belum Membayar";
                            array_push($arrDataTransaksi, $tempData);
                        }
                    }

                    $counter++;
                }

                foreach($arrBulan as $bulan){
                    foreach($arrDataTransaksi as $data){
                        if($data["bulan"] == $bulan){
                            array_push($arrDataTransaksiSorted, $data);
                        }
                    }
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

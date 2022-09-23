<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TarifController;
use App\Models\Transaction;
use App\Models\FamilyCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;

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
        if (session()->get('user')->role == 'admin_rt') {
            $familyCard = FamilyCard::where('rt_rw',session()->get('user')->rt_rw)->get();
        }
        else if (session()->get('user')->role == 'admin_rw') {
            $familyCard = FamilyCard::where('rt_rw','like','%'.explode("/",session()->get('user')->rt_rw)[1])->get();
        }
        else{
            $familyCard = FamilyCard::get();
        }

        return view('transaction.index', compact('familyCard', 'title'));
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
        if(isset($request->receipt)){
            $transaction_model = new Transaction;
            $dataTransaction = $transaction_model->get_transaction($request->nomor_kk, $request->tahun, $request->bulan);
            $dataTransactionStatus = $dataTransaction->first();

            if(isset($dataTransactionStatus) == false){
                $name = $request->receipt->getClientOriginalName();
                $request->receipt->move(public_path('assets/images/transaction/'. $request->nomor_kk), $name);
                
                $data = [
                    'family_card_id'=>$request->nomor_kk,
                    'jumlah'=>$request->amount,
                    'tahun'=>$request->tahun,
                    'bulan'=>$request->bulan,
                    'status' => 'Lunas',
                    'receipt' => $name,
                ];
                Transaction::create($data);
                return redirect(route('detail_transaction', ['nomor' => $request->nomor_kk, 'tahun' => $request->tahun]))->with('success','Data Berhasil di Input!');
            }else{
                return redirect(route('detail_transaction', ['nomor' => $request->nomor_kk, 'tahun' => $request->tahun]))->with('failed','Data Transaksi Sudah Ada!');
            }
        }else{
            return redirect(route('detail_transaction', ['nomor' => $request->nomor_kk, 'tahun' => $request->tahun]))->with('failed','Harap Masukan Bukti Pembayaran!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($nomor)
    {
        //
    }

    public function show_receipt_image($nomor, $tahun, $bulan){
        $transaction = new Transaction();
        $get_receipt_image = $transaction->get_transaction($nomor, $tahun, $bulan);
        $cek_receipt_image = $get_receipt_image->first();

        if(isset($cek_receipt_image)){
            return response()->json($get_receipt_image->first()->receipt);
        }else{
            return redirect()->route('detail_transaction', ['nomor' => $nomor, 'tahun' => $tahun])->with('failed','Tidak Ada Bukti Bayar!');
        }
    }

    public function show_transaction(Request $request=NULL ,$nomor, $tahun=NULL)
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
                return redirect()->route('tarif.index')->with('failed','Harap Tambah Tarif Warga '. $nomor .' Terlebih Dahulu!');
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nomor)
    {
        $transaction_model = new Transaction;
        if(isset($request->receipt)){
            $name = $request->receipt->getClientOriginalName();
            $request->receipt->move(public_path('assets/images/transaction/'. $request->nomor_kk), $name);

            $transaction_model->update_transaction($nomor, $request->tahun, $request->bulan, $name);
        }else{
            $get_transaction = $transaction_model->get_transaction($nomor, $request->tahun, $request->bulan);
            $transaction_model->update_transaction($nomor, $request->tahun, $request->bulan, $get_transaction->first()->receipt);
        }

        return redirect()->route('detail_transaction', ['nomor' => $nomor, 'tahun' => $request->tahun])->with('success','Data Berhasil di Update!');
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
    }

    public function delete_transaction($nomor, $tahun, $bulan){
        $transaction_model = new Transaction;
        $transaction_model->delete_transaction($nomor, $tahun, $bulan);

        return redirect()->route('detail_transaction', ['nomor' => $nomor, 'tahun' => $tahun])->with('success','Data Berhasil di Hapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use File;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private $code, $response;

    public $tempDataTransaksi = [];

    public function __construct(){
        $this->code = 200;
        $this->response = [];
    }

    public function index()
    {
        try {
            $response = Transaction::where('family_card_id', (new Transaction)->get_family_card());        
            if(request()->has('tahun')){
                $response = $response->where('tahun', request()->query('tahun'));
            }
            $this->response = Api::pagination($response);            

            $dataLand = DB::table('lands')
            ->join('categories', 'categories.id', '=', 'lands.category_id')
            ->where('family_card_id', (new Transaction)->get_family_card())
            ->select('categories.amount')        
            ->get();

            $jumlahIuran = 0;
            foreach($dataLand as $data){
                $jumlahIuran = $jumlahIuran + $data->amount;
            }
            
            $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
                "September", "Oktober", "November", "Desember"];
            $arrDataBulan = [];
            $arrDataBulanKonfirm = [];
            $arrDataTransaksi = [];

            foreach($this->response as $data){
                if($data->status == "Menunggu Konfirmasi"){
                    array_push($arrDataBulanKonfirm, $data->bulan);
                }else{
                    array_push($arrDataBulan, $data->bulan);
                }
                $tahun = $data->tahun;
            }
            
            foreach($arrDataBulan as $dataBulan){                
                foreach($arrBulan as $bulan){                    
                    if($dataBulan == $bulan){
                        if (($key = array_search($bulan, $arrBulan)) !== false) {
                            unset($arrBulan[$key]);
                        }
                    }                    
                }
            }

            foreach($arrDataBulanKonfirm as $dataBulan){                
                foreach($arrBulan as $bulan){                    
                    if($dataBulan == $bulan){
                        if (($key = array_search($bulan, $arrBulan)) !== false) {
                            unset($arrBulan[$key]);
                        }
                    }                    
                }
            }

            if(isset($tahun)){                
                foreach($arrBulan as $data){
                    $tempData["family_card_id"] = (new Transaction)->get_family_card();
                    $tempData["jumlah"] = $jumlahIuran;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $data;
                    $tempData["status"] = "Belum Membayar";
                    array_push($arrDataTransaksi, $tempData);
                }
    
                foreach($arrDataBulan as $data){
                    $tempData["family_card_id"] = (new Transaction)->get_family_card();
                    $tempData["jumlah"] = $jumlahIuran;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $data;
                    $tempData["status"] = "Lunas";
                    array_push($arrDataTransaksi, $tempData);
                }

                foreach($arrDataBulanKonfirm as $data){
                    $tempData["family_card_id"] = (new Transaction)->get_family_card();
                    $tempData["jumlah"] = $jumlahIuran;
                    $tempData["tahun"] = $tahun;
                    $tempData["bulan"] = $data;
                    $tempData["status"] = "Menunggu Konfirmasi";
                    array_push($arrDataTransaksi, $tempData);
                }
            }else{
                $arrDataTransaksi = [];
            }

        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        $tempDataTransaksi = $arrDataTransaksi;
        return Api::apiRespond($this->code, $arrDataTransaksi);
    }

    public function show($id)
    {
        try {
            $this->response = Transaction::findOrFail($id);
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
    }

    public function add_receipt(Request $request)
    {        
        try {
            $this->code = 400;
            $this->response = "Resi Harus Di Upload";

            if(isset($request->receipt)){
                $name = $request->receipt->getClientOriginalName();
                $request->receipt->move(public_path('assets/images/transaction/'. (new Transaction)->get_family_card()), $name);

                $data = [
                    'family_card_id'=>$request->input('family_card_id'),
                    'jumlah'=>$request->input('jumlah'),
                    'tahun'=>$request->input('tahun'),
                    'bulan'=>$request->input('bulan'),
                    'status' => "Menunggu Konfirmasi",
                    'receipt' => $name,
                ];
                Transaction::create($data);

                $this->response = null;
                $this->code = 200;
            }
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
    }
}

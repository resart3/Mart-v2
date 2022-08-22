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
                $inputTahun = request()->query('tahun');
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
            $arrDataBulanLunas = [];
            $arrDataBulanTidakValid = [];
            $arrDataBulanTidakTersedia = [];

            $arrDataTransaksi = [];
            $arrDataTransaksiSorted = [];

            foreach($this->response as $data){
                if($data->status == "Menunggu Konfirmasi" && $data->tahun == $inputTahun){
                    array_push($arrDataBulanKonfirm, $data->bulan);
                }elseif($data->status == "Lunas" && $data->tahun == $inputTahun){
                    array_push($arrDataBulanLunas, $data->bulan);
                }elseif($data->status == "Tidak Valid" && $data->tahun == $inputTahun){
                    array_push($arrDataBulanTidakValid, $data->bulan);
                }
                elseif($data->status == "Tidak Tersedia" && $data->tahun == $inputTahun){
                    array_push($arrDataBulanTidakTersedia, $data->bulan);
                }
            }

            if(count($arrDataBulanKonfirm) == 0 && count($arrDataBulanLunas) == 0 && 
                count($arrDataBulanTidakValid) == 0){
                                
                $createDate = DB::table('family_cards')
                ->where('nomor', (new Transaction)->get_family_card())
                ->select('created_at')        
                ->get();

                $getCreatedYear = substr($createDate[0]->created_at, 0, 4);
                $getCreatedMonth = substr($createDate[0]->created_at, 5, 2);
                $getCurrentYear = date('Y');
                if($getCreatedYear == $inputTahun){
                    $monthSplitted = str_split($getCreatedMonth);
                    if($monthSplitted[0] == 0){
                        $index = $monthSplitted[1] - 1;
                        $monthName = $arrBulan[$index];
                    }else{
                        $index = $month - 1;
                        $monthName = $arrBulan[$index];
                    }

                    for($i = 0; $i < $index; $i++){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $arrBulan[$i];
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }

                    for($i = $index; $i < 12; $i++){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $arrBulan[$i];
                        $tempData["status"] = "Belum Membayar";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }

                }elseif($getCreatedYear < $inputTahun){
                    foreach($arrBulan as $bulan){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $bulan;
                        $tempData["status"] = "Belum Membayar";
                        array_push($arrDataTransaksiSorted, $tempData);
                    }
                }

            }else{

                // Proses pop bulan dari data 12 bulan default
                foreach($arrDataBulanKonfirm as $dataBulan){                
                    foreach($arrBulan as $bulan){                    
                        if($dataBulan == $bulan){
                            if (($key = array_search($bulan, $arrBulan)) !== false) {
                                unset($arrBulan[$key]);
                            }
                        }                    
                    }
                }

                foreach($arrDataBulanLunas as $dataBulan){                
                    foreach($arrBulan as $bulan){                    
                        if($dataBulan == $bulan){
                            if (($key = array_search($bulan, $arrBulan)) !== false) {
                                unset($arrBulan[$key]);
                            }
                        }                    
                    }
                }

                foreach($arrDataBulanTidakValid as $dataBulan){                
                    foreach($arrBulan as $bulan){                    
                        if($dataBulan == $bulan){
                            if (($key = array_search($bulan, $arrBulan)) !== false) {
                                unset($arrBulan[$key]);
                            }
                        }                    
                    }
                }

                foreach($arrDataBulanTidakTersedia as $dataBulan){                
                    foreach($arrBulan as $bulan){                    
                        if($dataBulan == $bulan){
                            if (($key = array_search($bulan, $arrBulan)) !== false) {
                                unset($arrBulan[$key]);
                            }
                        }                    
                    }
                }
    
                if(isset($inputTahun)){                
                    foreach($arrBulan as $data){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $data;
                        $tempData["status"] = "Belum Membayar";
                        array_push($arrDataTransaksi, $tempData);
                    }
        
                    foreach($arrDataBulanLunas as $data){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $data;
                        $tempData["status"] = "Lunas";
                        array_push($arrDataTransaksi, $tempData);
                    }
    
                    foreach($arrDataBulanKonfirm as $data){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $data;
                        $tempData["status"] = "Menunggu Konfirmasi";
                        array_push($arrDataTransaksi, $tempData);
                    }

                    foreach($arrDataBulanTidakValid as $data){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $data;
                        $tempData["status"] = "Tidak Valid";
                        array_push($arrDataTransaksi, $tempData);
                    }

                    foreach($arrDataBulanTidakTersedia as $data){
                        $tempData["family_card_id"] = (new Transaction)->get_family_card();
                        $tempData["jumlah"] = $jumlahIuran;
                        $tempData["tahun"] = $inputTahun;
                        $tempData["bulan"] = $data;
                        $tempData["status"] = "Tidak Tersedia";
                        array_push($arrDataTransaksi, $tempData);
                    }

                }else{
                    $arrDataTransaksi = [];
                }
    
                $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
                    "September", "Oktober", "November", "Desember"];
    
                $count = 0;
                foreach($arrBulan as $bulan){
                    foreach($arrDataTransaksi as $data){
                        if($data["bulan"] == $bulan){
                            array_push($arrDataTransaksiSorted, $data);
                        }
                    }
                }
            }

        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }
        
        return Api::apiRespond($this->code, $arrDataTransaksiSorted);
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

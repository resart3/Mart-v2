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
            $familyCard = (new Transaction)->get_family_card();

            $response = Transaction::where('family_card_id', $familyCard);
            if(request()->has('tahun')){
                $inputTahun = request()->query('tahun');
                $response = $response->where('tahun', request()->query('tahun'));
            }
            $this->response = Api::pagination($response);
            $responseStatus = $this->response->first();

            $createDate = DB::table('family_cards')
            ->where('nomor', (new Transaction)->get_family_card())
            ->select('created_at')        
            ->get();
            $getCreatedYear = substr($createDate[0]->created_at, 0, 4);
            $getCreatedMonth = substr($createDate[0]->created_at, 5, 2);

            $dataLand = DB::table('lands')
            ->join('categories', 'categories.id', '=', 'lands.category_id')
            ->where('family_card_id', $familyCard)
            ->select('categories.amount')        
            ->get();
            $dataLandStatus = $dataLand->first();

            if(isset($dataLandStatus) == false){
                $this->code = 500;
                return Api::apiRespond($this->code, "Data Land Tidak Ditemukan");

            }else{
                $jumlahIuran = 0;
                foreach($dataLand as $data){
                    $jumlahIuran = $jumlahIuran + $data->amount;
                }
    
                $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
                    "September", "Oktober", "November", "Desember"];
                $arrDataTransaksi = [];
                $arrDataTransaksiSorted = [];
    
                if(isset($responseStatus) == false){
                    if($getCreatedYear == $inputTahun){
                        for($i = 0; $i < (int)$getCreatedMonth - 1; $i++){
                            $tempData["family_card_id"] = $familyCard;
                            $tempData["jumlah"] = 0;
                            $tempData["tahun"] = $inputTahun;
                            $tempData["bulan"] = $arrBulan[$i];
                            $tempData["status"] = "Tidak Tersedia";
                            array_push($arrDataTransaksiSorted, $tempData);
                        }
    
                        for($i = (int)$getCreatedMonth - 1; $i < count($arrBulan); $i++){
                            $tempData["family_card_id"] = $familyCard;
                            $tempData["jumlah"] = $jumlahIuran;
                            $tempData["tahun"] = $inputTahun;
                            $tempData["bulan"] = $arrBulan[$i];
                            $tempData["status"] = "Belum Membayar";
                            array_push($arrDataTransaksiSorted, $tempData);
                        }
                    }elseif($getCreatedYear < $inputTahun){
                        foreach($arrBulan as $bulan){
                            $tempData["family_card_id"] = $familyCard;
                            $tempData["jumlah"] = $jumlahIuran;
                            $tempData["tahun"] = $inputTahun;
                            $tempData["bulan"] = $bulan;
                            $tempData["status"] = "Belum Membayar";
                            array_push($arrDataTransaksiSorted, $tempData);
                        }
                    }else{
                        foreach($arrBulan as $bulan){
                            $tempData["family_card_id"] = $familyCard;
                            $tempData["jumlah"] = 0;
                            $tempData["tahun"] = $inputTahun;
                            $tempData["bulan"] = $bulan;
                            $tempData["status"] = "Tidak Tersedia";
                            array_push($arrDataTransaksiSorted, $tempData);
                        }
                    }
                }else{
                    foreach($this->response as $data){
                        $tempData["family_card_id"] = $data->family_card_id;
                        $tempData["jumlah"] = $data->jumlah;
                        $tempData["tahun"] = $data->tahun;
                        $tempData["bulan"] = $data->bulan;
                        $tempData["status"] = $data->status;
                        array_push($arrDataTransaksi, $tempData);
                    }

                    foreach($arrBulan as $bulan){
                        $found = 0;
                        foreach($arrDataTransaksi as $data){
                            if($data["bulan"] == $bulan){
                                $found = 1;
                                break;
                            }
                        }

                        if($found == 0){
                            $tempData["family_card_id"] = $familyCard;
                            $tempData["jumlah"] = $jumlahIuran;
                            $tempData["tahun"] = $inputTahun;
                            $tempData["bulan"] = $bulan;
                            $tempData["status"] = "Belum Membayar";
                            array_push($arrDataTransaksi, $tempData);
                        }
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

        }catch (Exception $e){
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
                $dataTransaction = DB::table('transactions')
                ->where('family_card_id', $request->input('family_card_id'))
                ->where('tahun', $request->input('tahun'))
                ->where('bulan', $request->input('bulan'))
                ->select('id')        
                ->get();
                $dataTransactionStatus = $dataTransaction->first();
                
                if(isset($dataTransactionStatus) == false){
                    $name = $request->receipt->getClientOriginalName();
                    $request->receipt->move(public_path('assets/images/transaction/'. $request->input('family_card_id')), $name);
                    
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
                }else{
                    $this->code = 500;
                    return Api::apiRespond($this->code, "Data Transaksi Sudah Ada Sebelumnya");
                }

            }
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
    }
}

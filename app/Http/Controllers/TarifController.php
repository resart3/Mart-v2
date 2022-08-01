<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use File;

class TarifController extends Controller
{
    //
    private $code, $response;

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
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
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

    public function add_receipt(Request $request, $id)
    {
        try {
            $this->code = 400;
            $this->response = "Resi Harus Di Upload";

            if(isset($request->receipt)){
                $name = $request->receipt->getClientOriginalName();

                $request->receipt->move(public_path('assets/images/transaction/'. $id), $name);

                Transaction::where('id', $id)->update([
                    'receipt' => $name,
                    'status'  => 'Menunggu Konfirmasi'
                ]);

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

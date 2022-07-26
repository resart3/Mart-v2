<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Api;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $response, $code;

    public function __construct()
    {
        $this->code = 200;
        $this->response = [];
    }
    
    public function index()
    {
        try {
            $response = Category::query();            
            $this->response = Api::pagination($response);
        } catch (Exception $e){            
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
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
     * @param CategoryRequest $request
     * @return Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();            
            $this->response = Category::create($data);
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
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
    }

    public function getUpdated(){
                        
        try {
            $response = Category::query();            
            $this->response = Api::pagination($response);
            $arrMonth = [];            

            foreach($this->response as $data){
                $updated_at = $data->updated_at;
                $update_time = strtotime($updated_at);
                $date = date("d-m-Y", $update_time);
                $dataUpdated["tanggal"] = date("d",strtotime($date));
                $month = date("m",strtotime($date));
                array_push($arrMonth, $month);
                $dataUpdated["tahun"] = date("Y",strtotime($date));
            }

            $updated_month = (int)max($arrMonth);            
            switch ($updated_month) {
                case 1:
                    $dataUpdated["bulan"] = "Januari";
                    break;
                case 2:
                    $dataUpdated["bulan"] = "Februari";
                    break;
                case 3:
                    $dataUpdated["bulan"] = "Maret";
                    break;
                case 4:
                    $dataUpdated["bulan"] = "April";
                    break;
                case 5:
                    $dataUpdated["bulan"] = "Mei";
                    break;
                case 6:
                    $dataUpdated["bulan"] = "Juni";
                    break;
                case 7:
                    $dataUpdated["bulan"] = "Juli";
                    break;
                case 8:
                    $dataUpdated["bulan"] = "Agustus";
                    break;
                case 9:
                    $dataUpdated["bulan"] = "September";
                    break;
                case 10:
                    $dataUpdated["bulan"] = "Oktober";
                    break;
                case 11:
                    $dataUpdated["bulan"] = "November";
                    break;
                case 12:
                    $dataUpdated["bulan"] = "Desember";
                    break;
            }            

        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $dataUpdated);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Api;
use \DateTime;

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
                $date = date("Y-m-d H:i:s", $update_time);
                $month = date("m",strtotime($date));
                array_push($arrMonth, $month);
            }

            $updated_month = (int)max($arrMonth);            
            switch ($updated_month) {
                case 1:
                    $monthName = "Januari";
                    break;
                case 2:
                    $monthName = "Februari";
                    break;
                case 3:
                    $monthName = "Maret";
                    break;
                case 4:
                    $monthName = "April";
                    break;
                case 5:
                    $monthName = "Mei";
                    break;
                case 6:
                    $monthName = "Juni";
                    break;
                case 7:
                    $monthName = "Juli";
                    break;
                case 8:
                    $monthName = "Agustus";
                    break;
                case 9:
                    $monthName = "September";
                    break;
                case 10:
                    $monthName = "Oktober";
                    break;
                case 11:
                    $monthName = "November";
                    break;
                case 12:
                    $monthName = "Desember";
                    break;
            }                        

        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $monthName);
    }
}

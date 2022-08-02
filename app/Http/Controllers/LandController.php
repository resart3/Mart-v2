<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LandRequest;
use App\Models\Land;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Api;

class LandController extends Controller
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
            $response = Land::query();
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
     * @param LandRequest $request
     * @return Response
     */
    public function store(LandRequest $request)
    {
        try {
            $data = $request->validated();            
            $this->response = Land::create($data);
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
        try {
            // $this->response = Land::with('relation');
            $this->response = Land::with(['family_cards','categories'])->where('family_card_id', $id)->get();
            // // kalo hitungan dari backend
            // $amounts = 0;
            // foreach ($this->response as $response) {
            //     $rt_rw = $response->family_cards->rt_rw;
            //     $amounts += $response->categories->amount;
            // }
        } catch (Exception $e){
            if ($e instanceof ModelNotFoundException){
                $this->code = 404;
            } else {
                $this->code = 500;
            }

            $this->response = $e->getMessage();
        }
        return Api::apiRespond($this->code, $this->response);
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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyCardRequest;
use App\Models\FamilyCard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;
use Api;

class FamilyCardController extends Controller
{
    private $response, $code;

    public function __construct()
    {
        $this->code = 200;
        $this->response = [];
    }

    public function index()
    {
        try {
            $response = FamilyCard::with('family_head');
            $this->response = Api::pagination($response);
        } catch (Exception $e){
            $this->code = 500;
            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FamilyCardRequest $request
     * @return Response
     */
    public function store(FamilyCardRequest $request)
    {
        try {
            $data = $request->validated();
            $this->response = FamilyCard::create($data);
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
     * @return Response
     */
    public function show($id)
    {
        try {
            $this->response = FamilyCard::with('family_members')->findOrFail($id);
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            FamilyCard::findOrFail($id)->delete();
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
}

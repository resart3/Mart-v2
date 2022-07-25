<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyMemberRequest;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;
use Api;

class FamilyMemberController extends Controller
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
            $response = FamilyMember::query();            
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
     * @param FamilyMemberRequest $request
     * @return Response
     */
    public function store(FamilyMemberRequest $request)
    {
        try {
            $data = $request->validated();
            $this->response = FamilyMember::create($data);
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
            $this->response = FamilyMember::with('family_cards')->findOrFail($id);
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
            FamilyMember::findOrFail($id)->delete();
        } catch (Exception $e){
            $this->code = 500;

            if ($e instanceof ModelNotFoundException){
                $this->code = 404;
            }

            $this->response = $e->getMessage();
        }

        return Api::apiRespond($this->code, $this->response);
    }
}

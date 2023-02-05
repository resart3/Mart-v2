<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyMember;

class CalonPemilihController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Halaman Calon Pemilih';
        $familyMember_model = new FamilyMember;
        $rt = $familyMember_model->get_rt(explode("/",session()->get('user')->rt_rw)[1]);
        if (session()->get('user')->role == 'admin_rt') {
            $calon_data = $familyMember_model->getDataCalonPemilih(session()->get('user')->rt_rw); 
        }else{
            $calon_data = $familyMember_model->getDataCalonPemilih('001'.'/'.explode("/",session()->get('user')->rt_rw)[1]); 
        }
        return view('calonPemilih.index', compact('calon_data','rt', 'title'));
    }

    public function filter($rt)
    {
        $familyMember_model = new FamilyMember;
        $calon_data = $familyMember_model->getDataCalonPemilih($rt."/".explode("/",session()->get('user')->rt_rw)[1]);
        return response()->json($calon_data);
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
        //
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
}

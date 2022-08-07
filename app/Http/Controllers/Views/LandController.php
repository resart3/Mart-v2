<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Http\Requests\LandRequest;
use App\Models\Land;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    
    public function index()
    {
        //
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
        $data = [
            'family_card_id'=>$request->input('family_card_id'),
            'category_id'=>$request->input('category_id'),
            'area'=>$request->input('area'),
            'house_number'=>$request->input('house_number')
        ];

        Land::create($data);
        return redirect()->route('tarif.index')->with('success','Tarif K3 Warga berhasil ditambahkan!');
    }

    /**
     * Ajax handler untuk get nama warga berdasarkan nomor KK
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxGetName(Request $request){        
        $input = $request->all();
        $nomor_kk = $input['nomorKk'];
        
        $nama = DB::table('family_members')
        ->select('family_members.nama')
        ->where('family_card_id', $nomor_kk)        
        ->where('isFamilyHead', 1)      
        ->get();
        
        return response()->json($nama);
    }

    public function ajaxGetAmount(Request $request){
        $input = $request->all();
        $category_id = $input['category_id'];

        $amount = Category::select('amount')     
        ->where('id', $category_id)      
        ->get();

        return response()->json($amount);
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
        $tarif = Land::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('tarif.index')->with('success','Data Tarif K3 Warga berhasil dihapus!');
    }
}

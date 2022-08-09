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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $land = DB::table('lands')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('family_members', 'family_members.family_card_id', '=', 'family_cards.nomor')
        ->where('isFamilyHead', 1)
        ->select('family_cards.nomor', 'family_members.nama', 'lands.*', 'categories.amount')
        ->where('lands.id', $id)
        ->get();

        return response()->json($land);
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
        $input = $request->all();
        $family_card_id = $request["family_card_id"];
        $category_id = $request["category_id"];
        $area = $request["area"];
        $house_number = $request["house_number"];

        $land = Land::find($id);
        $land->family_card_id = $family_card_id;
        $land->category_id = $category_id;
        $land->area = $area;
        $land->house_number = $house_number;
        $land->save();

        return response()->json("Berhasil Dirubah!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tarif = Land::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('tarif.index')->with('success','Data Tarif K3 Warga berhasil dihapus!');
    }
}

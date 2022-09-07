<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\Category;
use App\Http\Requests\LandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Halaman Tarif K3';        
        $tarif = Category::get();             
        $land = DB::table('lands')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('family_members', 'family_members.family_card_id', '=', 'family_cards.nomor')
        ->where('isFamilyHead', 1)
        ->select('family_cards.nomor', 'family_members.nama', 'lands.*', 'categories.amount')
        ->get();

        return view('tarif', compact('tarif', 'title', 'land'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TarifRequest $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [
            'category_name'=>$request->input('category_name'),
            'amount'=>$request->input('amount')
        ];
        Category::create($data);
        return redirect()->route('tarif.index')->with('success','Tarif K3 berhasil ditambahkan!');
    }

    /**
     * Store ke data tabel land
     *
     * @param LandRequest $request
     * @return Response
     */
    // public function storeLand(Request $request){
    //     $data = [
    //         'family_card_id'=>$request->input('nomor_kk'),
    //         'category_id'=>$request->input('kategoriWarga'),
    //         'area'=>$request->input('luasTanah'),
    //         'house_number'=>$request->input('nomorRumah')
    //     ];

    //     Land::create($data);
    //     return redirect()->route('tarif.index')->with('success','Tarif K3 Warga berhasil ditambahkan!');
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tarif = Category::find($id);

        if(isset($tarif) == TRUE){
            return response()->json($tarif);
        }else{
            return response()->json("Data Tarif Not Found");
        }
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
     * Update the specified resource in storage.     
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $kategori = $request["kategori"];
        $amount = $request["amount"];

        $category = Category::find($id);
        $category->category_name = $kategori;
        $category->amount = $amount;
        $category->save();

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
        //
        $tarif = Category::find($id);
        $tarif->lands()->delete();
        $tarif->delete();
        // redirect ke parentView
        return redirect()->route('tarif.index')->with('success','Data Tarif K3 berhasil dihapus!');
    }

    
}

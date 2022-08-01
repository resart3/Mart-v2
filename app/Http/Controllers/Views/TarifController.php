<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
// use App\Models\Tarif;
use App\Models\Land;
use App\Models\Category;
use Illuminate\Http\Request;

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
        // $tarif = Tarif::get();
        $tarif = Category::get();
        $land = Land::with('tarif')->get();;
        dd($land);
        return view('tarif', compact('tarif','land', 'title'));
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
            'kategori'=>$request->input('kategori'),
            'detail'=>$request->input('detail'),
            'nominal'=>$request->input('nominal')
        ];
        Tarif::create($data);
        return redirect()->route('tarif.index')->with('success','Tarif K3 berhasil ditambahkan!');
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
        $tarif = Tarif::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('tarif.index')->with('success','Data Tarif K3 berhasil dihapus!');
    }
}

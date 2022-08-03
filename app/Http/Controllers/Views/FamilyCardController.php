<?php

namespace App\Http\Controllers\Views;

use App\Models\FamilyMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyCard;


class FamilyCardController extends Controller
{
    public function index()
    {

        $title = 'Halaman Data Warga';
        $family_card = FamilyCard::with('family_head')->get();

        return view('family_card', compact('family_card', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FamilyCardRequest $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [
            'nomor'=>$request->input('nomor'),
            'alamat'=>$request->input('alamat'),
            'rt_rw'=>$request->input('rt_rw'),
            'kode_pos'=>$request->input('kode_pos'),
            'kecamatan'=>$request->input('kecamatan'),
            'desa_kelurahan'=>$request->input('desa_kelurahan'),
            'kabupaten_kota'=>$request->input('kabupaten_kota'),
            'provinsi'=>$request->input('provinsi'),
        ];
        FamilyCard::create($data);
        return redirect()->route('data.index')
        ->with('success','Family created successfully.');
    }

    public function showForm()
    {
        return view('form_family_card');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {

        $title = 'Detail Anggota Keluarga ('. $id .')';
        $family_member = FamilyMember::where('family_card_id', $id)->get();

        return view('family_detail', compact('family_member', 'title','id'));
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
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $family_card = FamilyCard::find($id);
        $family_card->nomor = $request->input('nomor'); 
        $family_card->alamat = $request->input('alamat');
        $family_card->rt_rw = $request->input('rt_rw');
        $family_card->kode_pos = $request->input('kode_pos');
        $family_card->desa_kelurahan = $request->input('desa_kelurahan');
        $family_card->kecamatan = $request->input('kecamatan');
        $family_card->kabupaten_kota = $request->input('kabupaten_kota');
        $family_card->provinsi = $request->input('provinsi');
        $family_card->save();
        return redirect()->route('data.index')
        ->with('success','Family Card Update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = FamilyMember::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('data.index')->with('success','Data User berhasil dihapus!');
    }
}
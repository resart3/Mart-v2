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
        // $rt_rw = explode("/",session()->get('user')->rt_rw);
        if (session()->get('user')->role=='admin-rt') {
            $family_card = FamilyCard::with('family_head')->where('rt_rw',session()->get('user')->rt_rw)->get();
        }
        else{
            $family_card = FamilyCard::with('family_head')->where('rt_rw','like','%'.explode("/",session()->get('user')->rt_rw)[1])->get();
        }

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
    public function edit($nomor)
    {
        //
        $data = FamilyCard::find($nomor);

        if(isset($data)){
            return response()->json($data);
        }else{
            return response()->json("Data Family Card Tidak Ditemukan!");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     */
    public function update(Request $request, $nomor)
    {
        $updateData = $request->all();
        $family_card = FamilyCard::FindOrFail($nomor);

        $family_card->nomor = $nomor;
        $family_card->alamat = $updateData['alamat'];
        $family_card->rt_rw = $updateData['rt_rw'];
        $family_card->kode_pos = $updateData['kode_pos'];
        $family_card->desa_kelurahan = $updateData['desa_kelurahan'];
        $family_card->kecamatan = $updateData['kecamatan'];
        $family_card->kabupaten_kota = $updateData['kabupaten_kota'];
        $family_card->provinsi = $updateData['provinsi'];
        $family_card->save();

        return response()->json("Data Berhasil Teganti");
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
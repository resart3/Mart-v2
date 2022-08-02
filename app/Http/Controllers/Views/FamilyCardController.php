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
        // $data = $request->except("_token");
        // FamilyCard::create($data);

        $data2 = [
            'nama'=>$request->input('nama'),
            'nik'=>$request->input('nik'),
            'tempat_lahir'=>$request->input('tempat_lahir'),
            'tanggal_lahir'=>$request->input('tanggal_lahir'),
            'jenis_kelamin'=>$request->input('jenis_kelamin'),
            'agama'=>$request->input('agama'),
            'pendidikan'=>$request->input('pendidikan'),
            'pekerjaan'=>$request->input('pekerjaan'),
            'golongan_darah'=>$request->input('golongan_darah'),
            'isFamilyHead'=>$request->input('isFamilyHead'),
            'family_card_id'=>$request->input('nomor'),
        ];
        dd($data2);
        // $data = $request->except("_token");
        FamilyMember::create($data);
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

        return view('family_detail', compact('family_member', 'title'));
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

    }
}

<?php

namespace App\Http\Controllers\Views;

use App\Models\FamilyMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    public function index()
    {        
        $title = 'Detail Anggota Keluarga ('. $id .')';
        $family_member = FamilyMember::where('family_card_id', $id)->get();

        return view('family_detail', compact('family_member', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FamilyMemberRequest $request
     * @return Response
     */
    public function store(FamilyMemberRequest $request)
    {
        $data = [
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
        $data = $request->except("_token");
        FamilyMember::create($data);

        return redirect()->route('detail.index')
        ->with('success','Family created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
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
        //
        $user = FamilyMember::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('data.index')->with('success','Data Anggota berhasil dihapus!');
    }
}

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
    public function store(Request $request)
    {
        $data = [
            'family_card_id'=>$request->input('nomor'),
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
        ];
        FamilyMember::create($data);
        return redirect()->route('data.show',['data'=>$request->input('nomor')])
        ->with('success','Family Member created successfully.');
        // return redirect()->route('detail.index')
        // ->with('success','Family created successfully.');
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
        $family_member = FamilyMember::find($id);
        $family_member->nama = $request->input('nama');
        $family_member->nik = $request->input('nik');
        $family_member->tempat_lahir = $request->input('tempat_lahir');
        $family_member->tanggal_lahir = $request->input('tanggal_lahir');
        $family_member->jenis_kelamin = $request->input('jenis_kelamin');
        $family_member->agama = $request->input('agama');
        $family_member->pendidikan = $request->input('pendidikan');
        $family_member->pekerjaan = $request->input('pekerjaan');
        $family_member->golongan_darah = $request->input('golongan_darah');
        $family_member->isFamilyHead = $request->input('isFamilyHead');
        $family_member->save();
        return redirect()->route('data.show',['data'=>$request->input('nomor')])
        ->with('success','Family created successfully.');
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
        $detail = FamilyMember::find($id);

        if(isset($detail) == TRUE){
            return response()->json($detail);
        }else{
            return response()->json("Data Family Member Tidak Ditemukan!");
        }
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
        return redirect()->route('data.show')->with('success','Data Anggota berhasil dihapus!');
    }
}

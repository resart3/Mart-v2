<?php

namespace App\Http\Controllers\Views;

use App\Models\FamilyMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        $get_nik = DB::table('family_members')
        ->select('nik')
        ->where('nik', $request->input('nik'))
        ->get();
        $cek_nik = $get_nik->first();

        if(isset($cek_nik) == true){
            return redirect()->route('data.show',['data'=>$request->input('nomor')])
            ->with('failed','NIK sudah terdaftar!');
        }else{
            $nik = preg_replace('/\s+/', '', $request->input('nik'));
            $data = [
                'family_card_id'=>$request->input('nomor'),
                'nama'=>$request->input('nama'),
                'nik'=>$nik,
                'tempat_lahir'=>$request->input('tempat_lahir'),
                'tanggal_lahir'=>$request->input('tanggal_lahir'),
                'jenis_kelamin'=>$request->input('jenis_kelamin'),
                'agama'=>$request->input('agama'),
                'pendidikan'=>$request->input('pendidikan'),
                'pekerjaan'=>$request->input('pekerjaan'),
                'golongan_darah'=>$request->input('golongan_darah'),
                'isFamilyHead'=>0
            ];
            FamilyMember::create($data);
            return redirect()->route('data.show',['data'=>$request->input('nomor')])
            ->with('success','Data Family Member berhasil dibuat!.');
        }

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
        $updateData = $request->all();
        $family_member = FamilyMember::FindOrFail($id);

        if($family_member->nik != $updateData['nik']){
            $get_nik = DB::table('family_members')
            ->select('nik')
            ->where('nik', $request->input('nik'))
            ->get();
            $cek_nik = $get_nik->first();

            if(isset($cek_nik) == true){
                return response()->json("Failed");
            }else{
                $nik = preg_replace('/\s+/', '', $updateData['nik']);
                $family_member->nik = $nik;
            }
        }

        $family_member->nama = $updateData['nama'];
        $family_member->tempat_lahir = $updateData['tempat_lahir'];
        $family_member->jenis_kelamin = $updateData['jenis_kelamin'];
        $family_member->agama = $updateData['agama'];
        $family_member->pendidikan = $updateData['pendidikan'];
        $family_member->pekerjaan = $updateData['pekerjaan'];
        $family_member->golongan_darah = $updateData['golongan_darah'];
        $family_member->isFamilyHead = $updateData['isFamilyHead'];

        $family_member->save();
        return response()->json("Success");
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
    public function destroy(Request $request, $id)
    {
        // $updateData = $request->all();
        //
        $user = FamilyMember::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('data.index')->with('success','Data Anggota berhasil dihapus!');
    }
}

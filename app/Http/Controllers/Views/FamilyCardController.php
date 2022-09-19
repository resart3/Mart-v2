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
        if (session()->get('user')->role=='admin_rt') {
            $family_card = FamilyCard::with('with_family_head')->where('rt_rw',session()->get('user')->rt_rw)->orderBy('created_at', 'desc')->get();
        }
        else if (session()->get('user')->role=='admin_rw') {
            $family_card =  FamilyCard::with('with_family_head')->where('rt_rw','like','%'.explode("/",session()->get('user')->rt_rw)[1])->orderBy('created_at', 'desc')->get();
        }
        else{
            $family_card = FamilyCard::with('with_family_head')->orderBy('created_at', 'desc')->get();
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
        $rt = $request->input('rt');
        $rw = $request->input('rw');
        $rt_rw = $rt.'/'.$rw;

        $nomor = preg_replace('/\s+/', '', $request->input('nomor'));
        $nik = preg_replace('/\s+/', '', $request->input('nik'));

        $dataKk = [
            'nomor'=>$nomor,
            'alamat'=>$request->input('alamat'),
            'rt_rw'=>$rt_rw,
            'kode_pos'=>$request->input('kode_pos'),
            'kecamatan'=>$request->input('kecamatan'),
            'desa_kelurahan'=>$request->input('desa_kelurahan'),
            'kabupaten_kota'=>$request->input('kabupaten_kota'),
            'provinsi'=>$request->input('provinsi'),
        ];

        $dataMember = [
            'family_card_id'=>$nomor,
            'nama'=>$request->input('nama'),
            'nik'=>$nik,
            'tempat_lahir'=>$request->input('tempat_lahir'),
            'tanggal_lahir'=>$request->input('tanggal_lahir'),
            'jenis_kelamin'=>$request->input('jenis_kelamin'),
            'agama'=>$request->input('agama'),
            'pendidikan'=>$request->input('pendidikan'),
            'pekerjaan'=>$request->input('pekerjaan'),
            'golongan_darah'=>$request->input('golongan_darah'),
            'isFamilyHead'=>1
        ];

        $no_kk = FamilyCard::find($dataKk['nomor']);
        if(isset($no_kk)){
            return redirect()->route('data.index')->with('failed','Nomor KK sudah terdaftar!');
        }else{
            FamilyCard::create($dataKk);
            FamilyMember::create($dataMember);
            return redirect()->route('data.index')->with('success','Data kartu keluarga berhasil dibuat!');
        }
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
    public function update(Request $request, $no_kk)
    {
        $updateData = $request->all();
        $family_card = FamilyCard::FindOrFail($no_kk);

        $family_card->rt_rw = $updateData['rt_rw'];
        $family_card->alamat = $updateData['alamat'];
        $family_card->kode_pos = $updateData['kode_pos'];
        $family_card->desa_kelurahan = $updateData['desa_kelurahan'];
        $family_card->kecamatan = $updateData['kecamatan'];
        $family_card->kabupaten_kota = $updateData['kabupaten_kota'];
        $family_card->provinsi = $updateData['provinsi'];

        $family_card->save();

        return response()->json("Success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($nomor)
    {
        $user = FamilyCard::find($nomor);
        $user->family_members()->delete();
        $user->lands()->delete();
        $user->delete();
        // redirect ke parentView
        return redirect()->route('data.index')->with('success','Data Family Card berhasil dihapus!');
    }
}
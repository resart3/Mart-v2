<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FamilyMember extends Model
{
    use HasFactory;

    public $fillable = [
        'family_card_id', 'nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'agama', 'pendidikan', 'pekerjaan', 'golongan_darah', 'isFamilyHead'
    ];

    public function family_cards() {
        return $this->belongsTo(FamilyCard::class, 'family_card_id','nomor');
    }

    public function get_rt($rw)
    {
        $quer = DB::table('family_cards')
        ->groupBy('family_cards.rt_rw')
        ->where ('family_cards.rt_rw', 'like', '%'.$rw)
        ->selectRaw('family_cards.rt_rw')
        ->get();

        return $quer;
    }

    public function getDataCalonPemilih($rt)
    {
        $quer = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF("2023-03-01",family_members.tanggal_lahir)), "%Y") + 0) > 16')
        ->where ('family_cards.rt_rw', '=', $rt)
        ->selectRaw('family_members.nama, family_cards.alamat, family_cards.rt_rw, family_cards.kode_pos')
        ->get();

        return $quer;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Statistic extends Model
{
    use HasFactory;

    public function countKRT($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->where('family_members.isFamilyHead','=','1')
        ->first();

        return $data;
    }

    public function countMale($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->where('family_members.jenis_kelamin','=','Laki-Laki')
        ->first();

        return $data;
    }

    public function countFemale($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->where('family_members.jenis_kelamin','=','Perempuan')
        ->first();

        return $data;
    }

    public function countLansia($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) > 60')
        ->first();

        return $data;
    }

    public function pasangSubur($var_rt)
    {
        # code...
    }

    public function wanitaSubur($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) >= 12')
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) < 45')
        ->where('family_members.jenis_kelamin','=','Perempuan')
        ->first();

        return $data;
    }

    public function usia12_18($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) >= 12')
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) < 18')
        ->first();

        return $data;
    }

    public function usia6_12($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) >= 6')
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) < 12')
        ->first();

        return $data;
    }

    public function usia0_6($var_rt)
    {
        $data = DB::table('family_members')
        ->join('family_cards', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->selectRaw('COUNT(id) as jumlah')
        ->where('family_cards.rt_rw',"like","%".$var_rt)
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) >= 0')
        ->whereRaw('(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),family_members.tanggal_lahir)), "%Y") + 0) < 6')
        ->first();

        return $data;
    }
}

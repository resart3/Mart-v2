<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;
    public $fillable = ['family_card_id', 'jumlah', 'tahun', 'bulan', 'status', 'receipt'];

    public function get_family_card(){
        return FamilyMember::where('nik', auth()->guard('api')->user()->nik)->first()->family_card_id;
    }

    public function familyCard(){
        return $this->belongsTo(FamilyCard::class, 'category_id');
    }

    

    public function get_transaction($nomor, $tahun, $bulan){
        return DB::table('transactions')
        ->where('family_card_id', $nomor)
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->select('*')
        ->get();
    }

    public function update_transaction($nomor, $tahun, $bulan, $status, $receipt){
        return DB::table('transactions')
        ->where('family_card_id', $nomor)
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->update(['status' => $status, 'receipt' => $receipt]);
    }
}

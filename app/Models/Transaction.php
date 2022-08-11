<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

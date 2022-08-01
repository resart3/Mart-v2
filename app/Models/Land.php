<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Land extends Model
{
    use HasFactory;

    // protected $table = "land";

    protected $fillable = [
        "family_card_id",
        "category_id",
        "area",
        "house_number"
    ];

    public function family_cards(){
        return $this->belongsTo(FamilyCard::class, 'family_card_id');
    }    
    public function categories(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function tarif(){
        // dd('masuk');
        return DB::table('lands')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('family_members', 'family_members.family_card_id', '=', 'family_cards.nomor')
        ->join('categories', 'categories.id', '=', 'lands.category_id')->get();
        // ->select('users.*', 'contacts.phone', 'orders.price');
    }
}

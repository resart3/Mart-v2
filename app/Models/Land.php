<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

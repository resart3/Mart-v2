<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'amount'
    ];

    public function family_cards()
    {
        // return $this->belongsToMany(FamilyCard::class, 'land')->withPivot('active', 'created_by');;
        return $this->belongsToMany(Category::class, 'land');
    }

    public function lands() {
        return $this->hasMany(Land::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyCard extends Model
{
    use HasFactory;

    protected $primaryKey = 'nomor';
    public $incrementing = false;

    public $fillable = [
        'nomor', 'alamat', 'rt_rw', 'kode_pos', 'kecamatan',
        'desa_kelurahan', 'kabupaten_kota', 'provinsi'
    ];

    public function family_members(){
        return $this->hasMany(FamilyMember::class, 'family_card_id', 'nomor');
    }

    public function family_head(){
        return $this->hasOne(FamilyMember::class, 'family_card_id', 'nomor')->where('isFamilyHead', 1)
            ->select('family_card_id', 'nik', 'nama');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'land');
        // "role_user" is table name
        // OR if we have model RoleUser, then we can use class
        // instead of table name role_user
        //return $this->belongsToMany(Role::class, RoleUser::class);
    }
}

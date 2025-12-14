<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CariGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function cariHesaplar()
    {
        return $this->hasMany(CariHesap::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValAsset extends Model
{
    protected $fillable = [
        'id_asset',
        'tanggal_verifikasi',
        'berkas_lengkap',
        'kondisi_asset',
        'lokasi',
    ];

    // If you are using timestamps, make sure to set this
    public $timestamps = true;

    public function newAsset()
    {
        return $this->belongsTo(NewAsset::class);
    }
}

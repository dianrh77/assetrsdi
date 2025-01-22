<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $connection = 'sqlsrv';
    protected $guarded = [];

    public function stokAset()
    {
        return $this->hasMany(AdjustStok::class, 'id_asset', 'id');
    }
	
	// Relasi one-to-many atau one-to-one, tergantung pada struktur relasi Anda
    public function tabelSo()
    {
        return $this->hasMany(TabelSo::class, 'id_master'); // Jika hubungan one-to-many
        // return $this->hasOne(TabelSo::class, 'id_asset'); // Jika hubungan one-to-one
    }
}

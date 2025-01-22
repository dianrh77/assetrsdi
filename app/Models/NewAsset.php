<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewAsset extends Model
{
    protected $connection = 'sqlsrv';
    protected $guarded = [];

    public function validasiAsset()
    {
        return $this->hasOne(ValAsset::class, 'id_asset', 'id');
    }
}

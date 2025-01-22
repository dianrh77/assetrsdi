<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelSo extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'tabel_so';
    protected $guarded = [];

    public function kelolaStok()
    {
        return $this->belongsTo(KelolaStokAset::class);
    }

}

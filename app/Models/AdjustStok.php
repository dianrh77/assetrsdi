<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjustStok extends Model
{
    protected $guarded = [];

    public function kelolaStok()
    {
        return $this->belongsTo(KelolaStokAset::class);
    }

    protected static function booted()
    {
        static::saving(function ($record) {
            if ($record->tipe === 'Kurang') {
                $record->qty = -abs($record->qty);
            } else {
                $record->qty = abs($record->qty);
            }
        });
    }
}

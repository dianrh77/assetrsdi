<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelolaMaster extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'assets';
    protected $guarded = [];
}

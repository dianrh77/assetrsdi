<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $connection = 'sqlsrv2';
    protected $table = 'perkiraan_new';

    protected $primaryKey = 'kd_perk'; // Nama kolom primary key

    protected $guarded = [];
}

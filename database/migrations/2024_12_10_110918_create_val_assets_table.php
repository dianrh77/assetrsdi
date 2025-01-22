<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('val_assets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_asset');
            $table->dateTime('tanggal_verifikasi');
            $table->boolean('berkas_lengkap');
            $table->boolean('kondisi_asset');
            $table->string('lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('val_assets');
    }
};

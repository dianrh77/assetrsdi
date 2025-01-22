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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->string('merk');
            $table->string('tipe')->nullable();
            $table->string('no_seri');
            $table->dateTime('tanggal_invoice');
            $table->string('tahun');
            $table->string('nama_vendor');
            $table->boolean('perlu_kalibrasi');
            $table->dateTime('tanggal_kalibrasi')->nullable();
            $table->dateTime('tanggal_penerimaan');
            $table->string('kategori');
            $table->boolean('is_aset');
            $table->string('lokasi_alat');
            $table->integer('jumlah');
            $table->float('harga');
            $table->string('no_invent');
            $table->string('kondisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

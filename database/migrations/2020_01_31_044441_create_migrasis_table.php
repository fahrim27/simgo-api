<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigrasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migrasis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uraian_jurnal', 50)->nullable();
            $table->string('bdp', 10)->nullable();
            $table->decimal('tahun_bdp', 4, 0)->nullable();
            $table->decimal('tahun_jurnal', 4, 0)->nullable();
            $table->string('nomor_lokasi', 50)->nullable();
            $table->string('pos_entri', 100)->nullable();
            $table->string('no_jurnal')->nullable();
            $table->string('no_register',100)->nullable();
            $table->string('jenis_aset', 5)->nullable();
            $table->string('kode_64', 200)->nullable();
            $table->string('nama_barang', 200)->nullable();
            $table->string('merk_alamat', 200)->nullable();
            $table->string('tipe', 100)->nullable()->nullable();
            $table->string('kode_ruangan', 50)->nullable();
            $table->decimal('saldo_barang', 5, 0)->nullable();
            $table->decimal('saldo_gudang', 5, 0)->nullable();
            $table->string('satuan', 2)->nullable();
            $table->decimal('harga_total_plus_pajak_saldo', 15, 2)->nullable();
            $table->decimal('tahun_pengadaan', 4, 0)->nullable();
            $table->string('cara_perolehan', 50)->nullable();
            $table->string('rencana_alokasi', 50)->nullable();
            $table->string('penggunaan', 200)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('kode_kepemilikan', 5)->nullable();
            $table->decimal('baik', 5, 0)->nullable();
            $table->decimal('kb', 5, 0)->nullable();
            $table->decimal('rb', 5, 0)->nullable();
            $table->date('tgl_update')->nullable();
            $table->string('operator', 100)->nullable();
            $table->string('bidang_barang', 4)->nullable();
            $table->string('ukuran', 200)->nullable();
            $table->string('no_rangka_seri', 100)->nullable();
            $table->string('no_bpkb', 100)->nullable();
            $table->string('nopol', 50)->nullable();
            $table->string('warna', 50)->nullable();
            $table->string('cc', 50)->nullable();
            $table->string('no_mesin', 200)->nullable();
            $table->string('nama_ruas', 200)->nullable();
            $table->decimal('luas_lantai', 15, 2)->nullable();
            $table->string('konstruksi', 100)->nullable();
            $table->string('bahan', 100)->nullable();
            $table->string('no_sertifikat', 200)->nullable();
            $table->string('luas_tanah', 200)->nullable();
            $table->string('status_tanah', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migrasis');
    }
}

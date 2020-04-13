<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKibsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kibs', function (Blueprint $table) {
            $table->string('kode_jurnal', 30);
            $table->string('no_key', 100);
            $table->string('pos_entri', 20)->nullable();
            $table->string('id_aset', 75)->primary();
            $table->string('no_ba_penerimaan', 50);
            $table->string('no_ba_penerimaan_asal', 50)->nullable();
            $table->string('nomor_lokasi', 30);
            $table->string('id_transaksi', 20);
            $table->date('tgl_catat')->nullable();
            $table->string('jenis_bukti', 2)->nullable();
            $table->date('tgl_perolehan')->nullable();
            $table->string('no_bukti_perolehan')->nullable();
            $table->string('no_penetapan')->nullable();
            $table->string('pinjam_pakai', 1);
            $table->string('kode_wilayah')->nullable();
            $table->string('lunas', 1)->nullable();
            $table->integer('id_entri_asal')->nullable();
            $table->string('jenis_aset', 1)->nullable();
            $table->string('no_register', 75)->nullable();
            $table->integer('no_induk')->nullable();
            $table->string('no_label', 30)->nullable();
            $table->string('no_register_unit', 50)->nullable();
            $table->string('kode_spm', 35)->nullable();
            $table->string('kode_spesifikasi', 15)->nullable();
            $table->string('kode_sub_sub_kelompok', 12)->nullable();
            $table->string('kode_sub_kel_at', 11)->nullable();
            $table->string('kode_64', 20)->nullable();
            $table->string('kode_108', 30)->nullable();
            $table->string('nama_barang', 150)->nullable();
            $table->string('merk_alamat', 200)->nullable();
            $table->string('tipe', 150)->nullable();
            $table->string('kode_ruangan', 25)->nullable();
            $table->string('nomor_ruangan', 32)->nullable();
            $table->integer('jumlah_barang')->nullable();
            $table->integer('saldo_barang')->nullable();
            $table->integer('saldo_gudang')->nullable();
            $table->string('satuan', 10)->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('harga_total', 15, 2)->nullable();
            $table->decimal('harga_total_plus_pajak', 15, 2)->nullable();
            $table->decimal('harga_total_plus_pajak_saldo', 15, 2)->nullable();
            $table->string('pajak', 1);
            $table->decimal('nilai_lunas', 15, 2)->nullable();
            $table->decimal('prosen_susut', 4, 2)->nullable();
            $table->decimal('ak_penyusutan', 15, 2)->nullable();
            $table->string('waktu_susut', 1)->nullable();
            $table->decimal('tahun_buku', 4, 0)->nullable();
            $table->decimal('nilai_buku', 15, 2)->nullable();
            $table->string('dasar_penilaian', 2)->nullable();
            $table->decimal('tahun_pengadaan', 4, 0)->nullable();
            $table->decimal('tahun_perolehan', 4, 0)->nullable();
            $table->string('cara_perolehan', 20)->nullable();
            $table->string('sumber_dana', 10)->nullable();
            $table->string('perolehan_pemda', 10)->nullable();
            $table->string('rencana_alokasi', 200)->nullable();
            $table->string('penggunaan', 150)->nullable();
            $table->string('keterangan', 150)->nullable();
            $table->string('kode_kepemilikan', 2)->nullable();
            $table->integer('baik')->nullable();
            $table->integer('kb')->nullable();
            $table->integer('rb')->nullable();
            $table->integer('sendiri')->nullable();
            $table->integer('pihak_3')->nullable();
            $table->integer('sengketa')->nullable();
            $table->tinyInteger('reklas')->nullable();
            $table->string('kode_asal')->nullable();
            $table->tinyInteger('aset_rehab')->nullable();
            $table->string('id_aset_induk')->nullable();
            $table->date('tgl_update')->nullable();
            $table->string('operator', 20)->nullable();
            $table->string('bidang_barang', 2);
            $table->string('no_sertifikat', 50)->nullable();
            $table->date('tgl_sertifkat')->nullable();
            $table->string('atas_nama_sertifikat', 50)->nullable();
            $table->string('status_tanah', 25)->nullable();
            $table->string('jenis_dokumen_tanah', 50)->nullable();
            $table->decimal('panjang_tanah', 9, 2)->nullable();
            $table->decimal('lebar_tanah', 15, 2)->nullable();
            $table->decimal('luas_tanah')->nullable();
            $table->string('no_imb', 50)->nullable();
            $table->date('tgl_imb')->nullable();
            $table->string('atas_nama_dokumen_gedung', 50)->nullable();
            $table->string('jenis_dok_gedung', 50)->nullable();
            $table->string('konstruksi', 25)->nullable();
            $table->string('bahan', 25)->nullable();
            $table->decimal('jumlah_lantai', 3, 0)->nullable();
            $table->decimal('luas_lantai', 15, 2)->nullable();
            $table->decimal('luas_bangunan', 15, 2)->nullable();
            $table->decimal('panjang', 9, 2)->nullable();
            $table->decimal('lebar', 9, 2)->nullable();
            $table->string('no_regs_induk_tanah', 15)->nullable();
            $table->string('pos_entri_tanah', 4)->nullable();
            $table->integer('id_transaksi_tanah')->nullable();
            $table->smallInteger('umur_teknis')->nullable();
            $table->decimal('nilai_residu', 15, 2)->nullable();
            $table->string('nama_ruas', 100)->nullable();
            $table->string('pangkal', 100)->nullable();
            $table->string('ujung', 100)->nullable();
            $table->string('no_bpkb', 50)->nullable();
            $table->date('tgl_bpkb')->nullable();
            $table->string('no_stnk', 50)->nullable();
            $table->date('tgl_stnk')->nullable();
            $table->string('no_rangka_seri', 50)->nullable();
            $table->string('nopol', 20)->nullable();
            $table->string('warna', 20)->nullable();
            $table->string('no_mesin', 50)->nullable();
            $table->string('bahan_bakar', 20)->nullable();
            $table->string('cc', 5)->nullable();
            $table->decimal('tahun_pembuatan', 4, 0)->nullable();
            $table->decimal('tahun_perakitan', 4, 0)->nullable();
            $table->string('no_faktur', 50)->nullable();
            $table->date('tgl_faktur')->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->string('wajib_kalibrasi', 1)->nullable();
            $table->integer('periode_kalibrasi')->nullable();
            $table->string('waktu_kalibrasi', 50)->nullable();
            $table->date('tgl_kalibrasi')->nullable();
            $table->string('pengkalibrasi', 100)->nullable();
            $table->decimal('biaya_kalibrasi', 15, 2)->nullable();
            $table->string('terkunci', 1);
            $table->string('usul_hapus', 1)->nullable();
            $table->date('tgl_usul_hapus')->nullable();
            $table->string('alasan_usul_hapus', 50)->nullable();
            $table->decimal('tahun_spj', 4, 0);
            $table->string('kunci_tmp', 1)->nullable();
            $table->decimal('tahun_verifikasi', 4, 0)->nullable();
            $table->string('tutup_buku', 1)->nullable();
            $table->string('komtabel', 1)->nullable();
            $table->integer('tdk_ditemukan')->nullable();
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
        Schema::dropIfExists('kibs');
    }
}

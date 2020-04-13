<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Kib extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_jurnal', 'no_key', 'pos_entri', 'id_aset', 'no_ba_penerimaan', 'no_ba_penerimaan_asal', 'nomor_lokasi', 'id_transaksi', 'tgl_catat', 'jenis_bukti', 'tgl_perolehan', 'no_bukti_perolehan', 'no_penetapan', 'pinjam_pakai', 'kode_wilayah', 'lunas', 'id_entri_asal', 'jenis_aset', 'no_register', 'no_induk', 'no_label', 'no_register_unit', 'kode_spm', 'kode_spesifikasi', 'kode_sub_sub_kelompok', 'kode_sub_kel_at', 'kode_64', 'kode_108', 'nama_barang', 'merk_alamat', 'tipe', 'kode_ruangan', 'nomor_ruangan', 'jumlah_barang', 'saldo_barang', 'saldo_gudang', 'satuan', 'harga_satuan', 'harga_total', 'harga_total_plus_pajak', 'harga_total_plus_pajak_saldo', 'pajak', 'nilai_lunas', 'prosen_susut', 'ak_penyusutan', 'waktu_susut', 'tahun_buku', 'nilai_buku', 'dasar_penilaian', 'tahun_pengadaan', 'tahun_perolehan', 'cara_perolehan', 'sumber_dana', 'perolehan_pemda', 'rencana_alokasi', 'penggunaan', 'keterangan', 'kode_kepemilikan', 'baik', 'kb', 'rb', 'sendiri', 'pihak_3', 'sengketa', 'reklas', 'kode_asal', 'aset_rehab', 'id_aset_induk', 'tgl_update', 'operator', 'bidang_barang', 'no_sertifikat', 'tgl_sertifkat', 'atas_nama_sertifikat', 'status_tanah', 'jenis_dokumen_tanah', 'panjang_tanah', 'lebar_tanah', 'luas_tanah', 'no_imb', 'tgl_imb', 'atas_nama_dokumen_gedung', 'jenis_dok_gedung', 'konstruksi', 'bahan', 'jumlah_lantai', 'luas_lantai', 'luas_bangunan', 'panjang', 'lebar', 'no_regs_induk_tanah', 'pos_entri_tanah', 'id_transaksi_tanah', 'umur_teknis', 'nilai_residu', 'nama_ruas', 'pangkal', 'ujung', 'no_bpkb', 'tgl_bpkb', 'no_stnk', 'tgl_stnk', 'no_rangka_seri', 'nopol', 'warna', 'no_mesin', 'bahan_bakar', 'cc', 'tahun_pembuatan', 'tahun_perakitan', 'no_faktur', 'tgl_faktur', 'ukuran', 'wajib_kalibrasi', 'periode_kalibrasi', 'waktu_kalibrasi', 'tgl_kalibrasi', 'pengkalibrasi', 'biaya_kalibrasi', 'terkunci', 'usul_hapus', 'tgl_usul_hapus', 'alasan_usul_hapus', 'tahun_spj', 'kunci_tmp', 'tahun_verifikasi', 'tutup_buku', 'komtabel', 'tdk_ditemukan'
    ];
    
    protected $primaryKey = 'id_aset';
    public $incrementing = false;
}

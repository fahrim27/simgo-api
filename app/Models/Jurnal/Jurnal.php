<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_key', 'terkunci', 'kode_jurnal', 'kode_pencatat', 'nomor_lokasi', 'dari_lokasi', 'ke_lokasi', 'kode_kegiatan', 'nama_kegiatan', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nilai_spk', 'uraian_spk', 'tahun_spm', 'id_kontrak', 'otomatis', 'no_ba_penerimaan', 'tgl_ba_penerimaan', 'instansi_yg_menyerahkan', 'alamat', 'instansi_tujuan', 'tgl_update', 'operator', 'tahun_spj', 'keterangan', 'nomor', 'no_bap', 'tgl_bap', 'no_ba_st', 'tgl_ba_st', 'nip_pengeluar', 'nama_pengeluar', 'jabatan_pengeluar', 'pangkat_gol_pengeluar', 'instansi_penerima', 'nip_penerima', 'nama_penerima', 'jabatan_penerima', 'pangkat_gol_penerima', 'atasan_pengeluar', 'nip_atasan_pengeluar', 'pangkat_gol_atasan', 'jabatan_atasan_pengeluar', 'jenis_dokumen', 'no_dokumen', 'tgl_dokumen', 'tutup_buku', 'cek_pilih', 'diterima'
    ];
    
    protected $primaryKey = 'no_key';
    public $incrementing = false;
}

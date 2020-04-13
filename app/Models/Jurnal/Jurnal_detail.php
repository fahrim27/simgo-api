<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Jurnal_detail extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_urut', 'id_jurnal_detail', 'no_key', 'kode_jurnal', 'pos_entri', 'bidang_barang', 'id_aset', 'no_ba_penerimaan', 'no_register', 'no_label', 'kode_kepemilikan', 'jumlah_barang', 'nilai_masuk_keluar', 'jumlah_kembali', 'keterangan', 'huruf', 'kondisi', 'penguasaan', 'posting', 'baik', 'kb', 'rb', 'sendiri', 'sengketa', 'pihak_3', 'kode_koreksi', 'sebelum_koreksi', 'sesudah_koreksi', 'kondisi_lama', 'new_baik', 'new_kb', 'new_rb', 'tahun_koreksi', 'kd_sebab', 'id_aset_baru'
    ];
    
    protected $primaryKey = 'id_jurnal_detail';
    public $incrementing = false;
}

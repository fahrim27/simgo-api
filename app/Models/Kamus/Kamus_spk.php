<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_spk extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_kontrak', 'id_kegiatan', 'nomor_sub_unit', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'deskripsi_spk_dokumen', 'nilai_spk', 'tahun_spj', 'rekanan', 'alamat_rekanan', 'termin', 'estimasi_termin', 'addendum', 'no_add', 'tgl_add', 'uraian_add', 'nilai_add', 'tahun_add', 'jml_termin_add'
    ];
    
    protected $primaryKey = 'id_kontrak';
    public $incrementing = false;
}

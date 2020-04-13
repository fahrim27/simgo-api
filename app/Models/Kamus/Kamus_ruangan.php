<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_ruangan extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_lokasi', 'nomor_ruangan', 'nama_ruangan', 'nip_peng_ruangan'
    ];

    protected $primaryKey = 'nomor_ruangan';
    public $incrementing = false;
}

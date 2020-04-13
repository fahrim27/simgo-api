<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_bidang_unit extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_kab', 'nomor_bidang_unit', 'nama_bidang_unit'
    ];

    protected $primaryKey = 'nomor_bidang_unit';
    public $incrementing = false;
}

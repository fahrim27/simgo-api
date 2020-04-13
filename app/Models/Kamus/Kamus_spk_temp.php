<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_spk_temp extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_kontrak', 'tgl_kontrak', 'nilai'
    ];
    
    protected $primaryKey = 'no_kontrak';
    public $incrementing = false;
}

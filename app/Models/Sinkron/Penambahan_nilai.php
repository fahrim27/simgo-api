<?php

namespace App\Models\Sinkron;
use Illuminate\Database\Eloquent\Model;

class Penambahan_nilai extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pnid', 'namapn', 'tahunpn', 'nilaipn', 'lokasipn', 'subunitpn', 'asetindukid', 'kodepn', 'bidangpn', 'namainduk', 'tahuninduk', 'kodeinduk', 'bidanginduk', 'tambah_masa_manfaat'
    ];
    
    protected $primaryKey = 'pnid';
    public $incrementing = false;
}

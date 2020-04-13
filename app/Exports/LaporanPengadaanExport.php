<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanPengadaanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Kib::where('kode_jurnal', '101');
    }
}

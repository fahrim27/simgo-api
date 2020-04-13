<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use Maatwebsite\Excel\Concerns\FromCollection;

class KibsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Kib::all();
    }
}

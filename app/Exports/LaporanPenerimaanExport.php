<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_lokasi;
use App\Models\Jurnal\Rincian_keluar;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LaporanPenerimaanExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

	public $nomor_lokasi;
	public $kode_kepemilikan;

	function __construct($args){
		$this->kode_jurnal = $args['kode_jurnal'];
		$this->nomor_lokasi = $args['nomor_lokasi'];
		$this->kode_kepemilikan = $args['kode_kepemilikan'];
		$this->nama_lokasi = $args['nama_lokasi'];
		$this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun = date('Y') - 1;

        $this->total_jumlah_barang = 0;
        $this->total_harga_total = 0;
        $this->total_baik = 0;
        $this->total_kurang_baik = 0;
        $this->total_rusak_berat = 0;
	}

    public function collection()
    {
        if($this->kode_jurnal == '101') {
            if($this->nomor_lokasi == '12.01.35.16.111.00002') {
                $data = Kib::join('kamus_lokasis', 'kibs.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('kibs.nomor_lokasi', 'kamus_lokasis.nama_lokasi', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                    ->where('kibs.kode_jurnal', $this->kode_jurnal)
                    ->where('kibs.nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                    ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                    ->where('kibs.tahun_pengadaan', $this->tahun)
                    ->get()
                    ->toArray();
            } else if($this->nomor_lokasi == '12.01.35.16.111.00001' || $this->nomor_lokasi == '12.01.35.16.131.00001.00001') {
                    $data = Kib::leftJoin('kamus_spks', 'kibs.no_bukti_perolehan', '=', 'kamus_spks.no_spk_sp_dokumen')
                        ->select('kibs.no_bukti_perolehan', 'kamus_spks.deskripsi_spk_dokumen', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                        ->where('kibs.kode_jurnal', $this->kode_jurnal)
                        ->where('kibs.nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                        ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                        ->where('kibs.tahun_pengadaan', $this->tahun)
                        ->orderBy('kibs.no_bukti_perolehan', 'asc')
                        ->get()
                        ->toArray();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','saldo_barang','harga_total_plus_pajak_saldo','baik','kb','rb','keterangan')
                    ->where('kode_jurnal', $this->kode_jurnal)
                    ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                    ->where('kode_kepemilikan', $this->kode_kepemilikan)
                    ->where('tahun_pengadaan', $this->tahun)
                    ->get()
                    ->toArray();
            }

            $asets = array();
            $total_jumlah_barang= 0;
            $total_harga_total = 0;
            $total_baik = 0;
            $total_kurang_baik = 0;
            $total_rusak_berat = 0;
            foreach($data as $value) {
                $aset = $value;
                $mutasi = Rincian_keluar::where('id_aset', $value['no_register'])->first();

                if(!is_null($mutasi)) {
                    $harga_total = $mutasi->nilai_keluar;
                    $jumlah_keluar = $mutasi->jumlah_barang;

                    $aset['saldo_barang'] += $jumlah_keluar;
                    $aset['harga_total_plus_pajak_saldo'] += $harga_total;
                }
                // jumlah barang
                $total_jumlah_barang_tmp = $aset['saldo_barang'];
                $total_jumlah_barang +=$total_jumlah_barang_tmp;
                $this->total_jumlah_barang = $total_jumlah_barang;
                // harga barang
                $total_harga_total_tmp = $aset['harga_total_plus_pajak_saldo'];
                $total_harga_total +=$total_harga_total_tmp;
                $this->total_harga_total = $total_harga_total;
                // baik
                $total_baik_tmp = $aset['baik'];
                $total_baik +=$total_baik_tmp;
                $this->total_baik = $total_baik;
                // kurang baik
                $total_kurang_baik_tmp = $aset['kb'];
                $total_kurang_baik +=$total_kurang_baik_tmp;
                $this->total_kurang_baik = $total_kurang_baik;
                // rusak berat
                $total_rusak_berat_tmp = $aset['rb'];
                $total_rusak_berat +=$total_rusak_berat_tmp;
                $this->total_rusak_berat = $total_rusak_berat;

                array_push($asets, $aset);
            }

            $data = collect($asets);
        } else {
            if($this->kode_jurnal == '103' && $this->nomor_lokasi == '12.01.35.16.111.00001') {
                $data = Kib::join('kamus_lokasis', 'kibs.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('kibs.nomor_lokasi', 'kamus_lokasis.nama_lokasi', 'kibs.kode_108', 'kibs.no_register', 'kibs.nama_barang', 'kibs.merk_alamat', 'kibs.saldo_barang', 'kibs.harga_total_plus_pajak_saldo', 'kibs.baik', 'kibs.kb', 'kibs.rb', 'kibs.keterangan')
                    ->where('kibs.kode_jurnal', $this->kode_jurnal)
                    ->where('kibs.nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                    ->where('kibs.kode_kepemilikan', $this->kode_kepemilikan)
                    ->where('kibs.tahun_spj', $this->tahun)
                    ->get();
            } else {
                $data = Kib::select('kode_108','no_register','nama_barang','merk_alamat','saldo_barang','harga_total_plus_pajak_saldo','baik','kb','rb','keterangan')
                ->where('kode_jurnal', $this->kode_jurnal)
                ->where('nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                ->where('kode_kepemilikan', $this->kode_kepemilikan)
                ->where('tahun_spj', $this->tahun)
                ->get();
            }

            $asets = array();
            $total_jumlah_barang= 0;
            $total_harga_total = 0;
            $total_baik = 0;
            $total_kurang_baik = 0;
            $total_rusak_berat = 0;
            foreach ($data as $value) {
                $aset = $value;

                // jumlah barang
                $total_jumlah_barang_tmp = $aset['saldo_barang'];
                $total_jumlah_barang +=$total_jumlah_barang_tmp;
                $this->total_jumlah_barang = $total_jumlah_barang;
                // harga barang
                $total_harga_total_tmp = $aset['harga_total_plus_pajak_saldo'];
                $total_harga_total +=$total_harga_total_tmp;
                $this->total_harga_total = $total_harga_total;
                // baik
                $total_baik_tmp = $aset['baik'];
                $total_baik +=$total_baik_tmp;
                $this->total_baik = $total_baik;
                // kurang baik
                $total_kurang_baik_tmp = $aset['kb'];
                $total_kurang_baik +=$total_kurang_baik_tmp;
                $this->total_kurang_baik = $total_kurang_baik;
                // rusak berat
                $total_rusak_berat_tmp = $aset['rb'];
                $total_rusak_berat +=$total_rusak_berat_tmp;
                $this->total_rusak_berat = $total_rusak_berat;

                array_push($asets, $aset);
            }

            $data = collect($asets);
        }

        return $data;
    }

    public function startCell(): string
    {
        return 'B3';
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function headings(): array
    {
        if($this->kode_jurnal == '101') {
            if($this->nomor_lokasi == '12.01.35.16.111.00002') {
                $headings = [
                    ["Nomor Lokasi", "Nama Lokasi", "Kode Rekening Barang", "No. Register", "Nama Barang", "Merk/Alamat", "Jumlah Barang", "Harga Total", "Baik", "Kurang Baik", "Rusak Berat", "Keterangan"],
                    [
                        2,3,4,5,6,7,8,9,10,11,12,13
                    ]
                ];
            } else if ($this->nomor_lokasi == '12.01.35.16.111.00001' || $this->nomor_lokasi == '12.01.35.16.131.00001.00001') {
                $headings = [
                    ["Nomor SPK", "Uraian SPK", "Kode Rekening Barang", "No. Register", "Nama Barang", "Merk/Alamat", "Jumlah Barang", "Harga Total", "Baik", "Kurang Baik", "Rusak Berat", "Keterangan"],
                    [
                        2,3,4,5,6,7,8,9,10,11,12,13
                    ]
                ];
            } else {
                $headings = [
                    ["Kode Rekening Barang", "No. Register", "Nama Barang", "Merk/Alamat", "Jumlah Barang", "Harga Total", "Baik", "Kurang Baik", "Rusak Berat", "Keterangan"],
                    [
                        2,3,4,5,6,7,8,9,10,11
                    ]
                ];
            }
        } else {
            if($this->kode_jurnal == '103' && $this->nomor_lokasi == '12.01.35.16.111.00001') {
                $headings = [
                    ["Nomor Lokasi", "Nama Lokasi", "Kode Rekening Barang", "No. Register", "Nama Barang", "Merk/Alamat", "Jumlah Barang", "Harga Total", "Baik", "Kurang Baik", "Rusak Berat", "Keterangan"],
                    [
                        2,3,4,5,6,7,8,9,10,11,12,13
                    ]
                ];
            } else {
                $headings = [
                    ["Kode Rekening Barang", "No. Register", "Nama Barang", "Merk/Alamat", "Jumlah Barang", "Harga Total", "Baik", "Kurang Baik", "Rusak Berat", "Keterangan"],
                    [
                        2,3,4,5,6,7,8,9,10,11
                    ]
                ];
            }
        }

        return $headings;
    }

    public function registerEvents(): array
    {
        if($this->kode_jurnal == '101') {
            if($this->nomor_lokasi == '12.01.35.16.111.00001' || $this->nomor_lokasi == '12.01.35.16.111.00002' || $this->nomor_lokasi == '12.01.35.16.131.00001.00001') {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $max = $event->sheet->getDelegate()->getHighestRow();
                        /////////border heading
                        $event->sheet->getStyle('A3:M3')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('A4:M4')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end border heading
                        // border
                        $event->sheet->getStyle('A5:M'.$max)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // end border

                        /////////////setting page excel
                        $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                        $event->sheet->getPageSetup()->setFitToWidth(1);
                        $event->sheet->getPageSetup()->setFitToHeight(0);
                        $event->sheet->getPageSetup()->setFitToPage(true);
                        $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                        $event->sheet->setShowGridlines(false);
                        $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                        // format text
                        $event->sheet->getStyle('I4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                        $event->sheet->freezePane('N5');

                        // footer
                        $event->sheet->getHeaderFooter()
                            ->setOddFooter('&L&B'.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '. $this->tahun .' / '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                        // end footer
                        ///////////// end setting excel

                        //////////numbering
                        $event->sheet->getDelegate()->setCellValue("A3", "No.");
                        $event->sheet->getStyle('A3:A'.$max)->applyFromArray([
                            'alignment' =>[
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('A')->setAutoSize(true);
                        $nomor = 1;
                        for($i=4;$i<=$max;$i++){
                            $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                            $nomor++;
                        }
                        ///////////////end numbering

                        //all alignment
                        $event->sheet->getStyle('A4:M'.$max)->applyFromArray([
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end all alignment

                        /////////////width column
                        // B nomor lokasi
                        $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                        // C nama lokasi
                        $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                        // D kode rekening barang
                        $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                        // E no register
                        $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25);
                        $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                        // F nama barang
                        $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(15);
                        $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                        // G merk / alamat
                        $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(15);
                        $event->sheet->getStyle('G3:G'.$max)->getAlignment()->setWrapText(true);
                        // H jumlah barang
                        $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(10);
                        $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('H4:H'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        // i harga total
                        $event->sheet->getColumnDimension('I')->setAutoSize(true);
                        // j baik
                        $event->sheet->getStyle('J4:J'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                        // k kurang baik
                        $event->sheet->getStyle('K4:K'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('K3:K'.$max)->getAlignment()->setWrapText(true);
                        // L rusak berat
                        $event->sheet->getStyle('L4:L'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('L3:L'.$max)->getAlignment()->setWrapText(true);
                        // M keterangan
                        $event->sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(30);
                        $event->sheet->getStyle('M3:M'.$max)->getAlignment()->setWrapText(true);

                        ///////////end column

                        ////////header
                        $event->sheet->getDelegate()->mergeCells('A1:M1');
                        $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->nama_lokasi ." ".$this->tahun);
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                                'size' => 18
                            ]
                        ]);
                        // end header

                        $date = date('d/m/Y');
                        $f1 = $max+1;
                        $f2 = $max+3;

                        ///////////////border total
                        $event->sheet->getStyle('A'.$f1.':M'.$f1)->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // total
                        $event->sheet->getDelegate()->mergeCells('F'.$f1.':H'.$f1);
                        $event->sheet->getDelegate()->setCellValue('F'.$f1, "Total");
                        $event->sheet->getStyle('F'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);
                        // i total harga total
                        $event->sheet->getDelegate()->setCellValue('I'.$f1 , $this->total_harga_total);
                        $event->sheet->getStyle('I'.$f1)->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                        $event->sheet->getStyle('I'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);

                        for($i = 0; $i<5; $i++) {
                            $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                            $event->sheet->getDelegate()->mergeCells('G'.$f2.':M'.$f2);
                            $event->sheet->getStyle('A'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);
                            $event->sheet->getStyle('G'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);

                            if($i == 0) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "Mojokerto, ".$date);
                            }

                            if($i == 4) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "NIP");
                            }

                            $f2++;
                        }
                    },
                ];
            } else {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $max = $event->sheet->getDelegate()->getHighestRow();
                        /////////border heading
                        $event->sheet->getStyle('A3:K3')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('A4:K4')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end border heading
                        // border
                        $event->sheet->getStyle('A5:K'.$max)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // end border

                        /////////////setting page excel
                        $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                        $event->sheet->getPageSetup()->setFitToWidth(1);
                        $event->sheet->getPageSetup()->setFitToHeight(0);
                        $event->sheet->getPageSetup()->setFitToPage(true);
                        $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                        $event->sheet->setShowGridlines(false);
                        $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                        // format text
                        $event->sheet->getStyle('G4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                        $event->sheet->freezePane('L5');

                        // footer
                        $event->sheet->getHeaderFooter()
                            ->setOddFooter('&L&B'.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '. $this->tahun .' / '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                        // end footer
                        ///////////// end setting excel

                        //////////numbering
                        $event->sheet->getDelegate()->setCellValue("A3", "No.");
                        $event->sheet->getStyle('A3:A'.$max)->applyFromArray([
                            'alignment' =>[
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('A')->setAutoSize(true);
                        $nomor = 1;
                        for($i=4;$i<=$max;$i++){
                            $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                            $nomor++;
                        }
                        ///////////////end numbering

                        //all alignment
                        $event->sheet->getStyle('A4:K'.$max)->applyFromArray([
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end all alignment

                        /////////////width column
                        // B kode rekening barang
                        $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                        // C no. register
                        $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                        // D nama barang
                        $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                        // E merk / alamat
                        $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25);
                        $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                        // F jumlah barang
                        $event->sheet->getStyle('F4:F'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(10);
                        $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                        // G harga total
                        $event->sheet->getColumnDimension('G')->setAutoSize(true);
                        // H baik
                        $event->sheet->getStyle('H4:H'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                        // i kurang baik
                        $event->sheet->getStyle('I4:I'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('I3:I'.$max)->getAlignment()->setWrapText(true);
                        // j rusak berat
                        $event->sheet->getStyle('J4:J'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                        // k Keterangan
                        $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(30);
                        $event->sheet->getStyle('K3:K'.$max)->getAlignment()->setWrapText(true);


                        ///////////end column

                        ////////header
                        $event->sheet->getDelegate()->mergeCells('A1:K1');
                        $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->nama_lokasi ." ".$this->tahun);
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                                'size' => 18
                            ]
                        ]);
                        // end header

                        $date = date('d/m/Y');
                        $f1 = $max+1;
                        $f2 = $max+3;

                        ///////////////border total
                        $event->sheet->getStyle('A'.$f1.':K'.$f1)->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // total
                        $event->sheet->getDelegate()->mergeCells('E:'.$f1.':F'.$f1);
                        $event->sheet->getDelegate()->setCellValue('E'.$f1, "Total");
                        $event->sheet->getStyle('E'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);
                        // G total harga total
                        $event->sheet->getDelegate()->setCellValue('G'.$f1 , $this->total_harga_total);
                        $event->sheet->getStyle('G'.$f1)->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                        $event->sheet->getStyle('G'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);

                        for($i = 0; $i<5; $i++) {
                            $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                            $event->sheet->getDelegate()->mergeCells('G'.$f2.':K'.$f2);
                            $event->sheet->getStyle('A'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);
                            $event->sheet->getStyle('G'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);

                            if($i == 0) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "Mojokerto, ".$date);
                            }

                            if($i == 4) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "NIP");
                            }

                            $f2++;
                        }
                    },
                ];
            }
        } else {
            if($this->kode_jurnal == '103' && $this->nomor_lokasi == '12.01.35.16.111.00001') {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $max = $event->sheet->getDelegate()->getHighestRow();
                        /////////border heading
                        $event->sheet->getStyle('A3:M3')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('A4:M4')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end border heading
                        // border
                        $event->sheet->getStyle('A5:M'.$max)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // end border

                        /////////////setting page excel
                        $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                        $event->sheet->getPageSetup()->setFitToWidth(1);
                        $event->sheet->getPageSetup()->setFitToHeight(0);
                        $event->sheet->getPageSetup()->setFitToPage(true);
                        $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                        $event->sheet->setShowGridlines(false);
                        $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                        // format text
                        $event->sheet->getStyle('I4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                        $event->sheet->freezePane('N5');

                        // footer
                        $event->sheet->getHeaderFooter()
                            ->setOddFooter('&L&B'.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '. $this->tahun .' / '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                        // end footer
                        ///////////// end setting excel

                        //////////numbering
                        $event->sheet->getDelegate()->setCellValue("A3", "No.");
                        $event->sheet->getStyle('A3:A'.$max)->applyFromArray([
                            'alignment' =>[
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('A')->setAutoSize(true);
                        $nomor = 1;
                        for($i=4;$i<=$max;$i++){
                            $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                            $nomor++;
                        }
                        ///////////////end numbering

                        //all alignment
                        $event->sheet->getStyle('A4:M'.$max)->applyFromArray([
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end all alignment

                        /////////////width column
                        // B nomor lokasi
                        $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                        // C nama lokasi
                        $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                        // D kode rekening barang
                        $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                        // E no register
                        $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25);
                        $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                        // F nama barang
                        $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(15);
                        $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                        // G merk / alamat
                        $event->sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(15);
                        $event->sheet->getStyle('G3:G'.$max)->getAlignment()->setWrapText(true);
                        // H jumlah barang
                        $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(10);
                        $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('H4:H'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        // i harga total
                        $event->sheet->getColumnDimension('I')->setAutoSize(true);
                        // j baik
                        $event->sheet->getStyle('J4:J'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                        // k kurang baik
                        $event->sheet->getStyle('K4:K'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('K3:K'.$max)->getAlignment()->setWrapText(true);
                        // L rusak berat
                        $event->sheet->getStyle('L4:L'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('L3:L'.$max)->getAlignment()->setWrapText(true);
                        // M keterangan
                        $event->sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(30);
                        $event->sheet->getStyle('M3:M'.$max)->getAlignment()->setWrapText(true);

                        ///////////end column

                        ////////header
                        $event->sheet->getDelegate()->mergeCells('A1:M1');
                        $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->nama_lokasi ." ".$this->tahun);
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                                'size' => 18
                            ]
                        ]);
                        // end header

                        $date = date('d/m/Y');
                        $f1 = $max+1;
                        $f2 = $max+3;

                        ///////////////border total
                        $event->sheet->getStyle('A'.$f1.':M'.$f1)->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // total
                        $event->sheet->getDelegate()->mergeCells('F'.$f1.':H'.$f1);
                        $event->sheet->getDelegate()->setCellValue('F'.$f1, "Total");
                        $event->sheet->getStyle('F'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);
                        // i total harga total
                        $event->sheet->getDelegate()->setCellValue('I'.$f1 , $this->total_harga_total);
                        $event->sheet->getStyle('I'.$f1)->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                        $event->sheet->getStyle('I'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);

                        for($i = 0; $i<5; $i++) {
                            $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                            $event->sheet->getDelegate()->mergeCells('G'.$f2.':M'.$f2);
                            $event->sheet->getStyle('A'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);
                            $event->sheet->getStyle('G'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);

                            if($i == 0) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "Mojokerto, ".$date);
                            }

                            if($i == 4) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "NIP");
                            }

                            $f2++;
                        }
                    },
                ];
            } else {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $max = $event->sheet->getDelegate()->getHighestRow();
                        /////////border heading
                        $event->sheet->getStyle('A3:K3')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getStyle('A4:K4')->applyFromArray([
                            'font' => [
                                'bold' => true
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end border heading
                        // border
                        $event->sheet->getStyle('A5:K'.$max)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // end border

                        /////////////setting page excel
                        $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                        $event->sheet->getPageSetup()->setFitToWidth(1);
                        $event->sheet->getPageSetup()->setFitToHeight(0);
                        $event->sheet->getPageSetup()->setFitToPage(true);
                        $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                        $event->sheet->setShowGridlines(false);
                        $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                        // format text
                        $event->sheet->getStyle('G4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                        $event->sheet->freezePane('L5');

                        // footer
                        $event->sheet->getHeaderFooter()
                            ->setOddFooter('&L&B'.$this->nama_jurnal.' / '. $this->nama_lokasi.' / '. $this->tahun .' / '.$this->kode_kepemilikan.' - Pemerintah Kab /kota' . '&R &P / &N');
                        // end footer
                        ///////////// end setting excel

                        //////////numbering
                        $event->sheet->getDelegate()->setCellValue("A3", "No.");
                        $event->sheet->getStyle('A3:A'.$max)->applyFromArray([
                            'alignment' =>[
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('A')->setAutoSize(true);
                        $nomor = 1;
                        for($i=4;$i<=$max;$i++){
                            $event->sheet->getDelegate()->setCellValue("A".$i, $nomor);
                            $nomor++;
                        }
                        ///////////////end numbering

                        //all alignment
                        $event->sheet->getStyle('A4:K'.$max)->applyFromArray([
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        // end all alignment

                        /////////////width column
                        // B kode rekening barang
                        $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                        // C no. register
                        $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                        // D nama barang
                        $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20);
                        $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                        // E merk / alamat
                        $event->sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25);
                        $event->sheet->getStyle('E3:E'.$max)->getAlignment()->setWrapText(true);
                        // F jumlah barang
                        $event->sheet->getStyle('F4:F'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(10);
                        $event->sheet->getStyle('F3:F'.$max)->getAlignment()->setWrapText(true);
                        // G harga total
                        $event->sheet->getColumnDimension('G')->setAutoSize(true);
                        // H baik
                        $event->sheet->getStyle('H4:H'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('H3:H'.$max)->getAlignment()->setWrapText(true);
                        // i kurang baik
                        $event->sheet->getStyle('I4:I'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('I3:I'.$max)->getAlignment()->setWrapText(true);
                        // j rusak berat
                        $event->sheet->getStyle('J4:J'.$max)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                        $event->sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(7);
                        $event->sheet->getStyle('J3:J'.$max)->getAlignment()->setWrapText(true);
                        // k Keterangan
                        $event->sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(30);
                        $event->sheet->getStyle('K3:K'.$max)->getAlignment()->setWrapText(true);


                        ///////////end column

                        ////////header
                        $event->sheet->getDelegate()->mergeCells('A1:K1');
                        $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal . " " . $this->nama_lokasi ." ".$this->tahun);
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                                'size' => 18
                            ]
                        ]);
                        // end header

                        $date = date('d/m/Y');
                        $f1 = $max+1;
                        $f2 = $max+3;

                        ///////////////border total
                        $event->sheet->getStyle('A'.$f1.':K'.$f1)->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '000000']
                                ],
                            ],
                        ]);
                        // total
                        $event->sheet->getDelegate()->mergeCells('E'.$f1.':F'.$f1);
                        $event->sheet->getDelegate()->setCellValue('E'.$f1, "Total");
                        $event->sheet->getStyle('E'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);
                        // F total jumlah barang
                        // $event->sheet->getDelegate()->setCellValue('F'.$f1 , $this->total_jumlah_barang);
                        // $event->sheet->getStyle('F'.$f1)->getNumberFormat()
                        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        // $event->sheet->getStyle('F'.$f1)->applyFromArray([
                        //     'alignment' => [
                        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        //     ],
                        //     'font' => [
                        //         'bold' => true,
                        //     ]
                        // ]);
                        // G total harga total
                        $event->sheet->getDelegate()->setCellValue('G'.$f1 , $this->total_harga_total);
                        $event->sheet->getStyle('G'.$f1)->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE);
                        $event->sheet->getStyle('G'.$f1)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true,
                            ]
                        ]);
                        // H total baik
                        // $event->sheet->getDelegate()->setCellValue('H'.$f1 , $this->total_baik);
                        // $event->sheet->getStyle('H'.$f1)->getNumberFormat()
                        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        // $event->sheet->getStyle('H'.$f1)->applyFromArray([
                        //     'alignment' => [
                        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        //     ],
                        //     'font' => [
                        //         'bold' => true,
                        //     ]
                        // ]);
                        // I total kurang baik
                        // $event->sheet->getDelegate()->setCellValue('I'.$f1 , $this->total_kurang_baik);
                        // $event->sheet->getStyle('I'.$f1)->getNumberFormat()
                        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        // $event->sheet->getStyle('I'.$f1)->applyFromArray([
                        //     'alignment' => [
                        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        //     ],
                        //     'font' => [
                        //         'bold' => true,
                        //     ]
                        // ]);
                        // J rusak berat
                        // $event->sheet->getDelegate()->setCellValue('J'.$f1 , $this->total_rusak_berat);
                        // $event->sheet->getStyle('J'.$f1)->getNumberFormat()
                        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        // $event->sheet->getStyle('J'.$f1)->applyFromArray([
                        //     'alignment' => [
                        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        //     ],
                        //     'font' => [
                        //         'bold' => true,
                        //     ]
                        // ]);

                        for($i = 0; $i<5; $i++) {
                            $event->sheet->getDelegate()->mergeCells('A'.$f2.':F'.$f2);
                            $event->sheet->getDelegate()->mergeCells('G'.$f2.':K'.$f2);
                            $event->sheet->getStyle('A'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);
                            $event->sheet->getStyle('G'.$f2)->applyFromArray([
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]);

                            if($i == 0) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "Mengetahui");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "Mojokerto, ".$date);
                            }

                            if($i == 4) {
                                $event->sheet->getDelegate()->setCellValue('A'.$f2, "NIP");
                                $event->sheet->getDelegate()->setCellValue('G'.$f2, "NIP");
                            }

                            $f2++;
                        }
                    },
                ];
            }
        }
	}

	public function columnFormats(): array
    {
        if($this->kode_jurnal == '101') {
            if($this->nomor_lokasi == '12.01.35.16.111.00001' || $this->nomor_lokasi == '12.01.35.16.111.00002' || $this->nomor_lokasi == '12.01.35.16.131.00001.00001')  {
                return [
                    'I' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
                ];
            } else {

                return [
                    'G' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
                ];
            }
        } else{
            if($this->kode_jurnal == '103' && $this->nomor_lokasi=='12.01.35.16.111.00001'){
                return [
                    'I' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
                ];
            } else {
                return [
                    'G' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
                ];
            }
        }
    }

	public function title(): string
    {
        return 'Data';
    }

}

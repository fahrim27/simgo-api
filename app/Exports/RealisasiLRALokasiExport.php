<?php

namespace App\Exports;

use App\Models\Jurnal\Kib;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_spm;
use App\Models\Jurnal\Penunjang;
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

class RealisasiLRALokasiExport implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting, WithHeadingRow, WithCustomStartCell, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

	public $nomor_lokasi;
	public $kode_kepemilikan;

	function __construct($args){
		$this->nama_lokasi = $args['nama_lokasi'];
        $this->nomor_lokasi = $args['nomor_lokasi'];
        $this->nama_jurnal = $args['nama_jurnal'];
        $this->tahun_sekarang = date('Y')-1;

        $this->total_spk = 0;
        $this->total_realisasi = 0;
	}

    public function collection()
    {
        $data = Kib::select('nomor_lokasi', 'no_bukti_perolehan', 'harga_total_plus_pajak_saldo')
                ->where('kibs.nomor_lokasi', 'like', $this->nomor_lokasi . '%')
                ->where('kibs.kode_jurnal', '=', '101')
                ->where('kibs.tahun_spj', '=', 2019)
                ->orderBy('kibs.no_bukti_perolehan', 'asc')
                ->get();

        $exported = array();
        $i = 0;

        $total_nilai_spk_penunjang = 0;
        $total_nilai_realisasi = 0;

        foreach ($data as $value) {
            $found = false;

            $found = false;
            foreach($exported as $key => $s)
            {
                if ($s["no_spk"] == $value["no_bukti_perolehan"]) {
                    $found = true;
                    break;
                }
            }

            if($found) {
                continue;
            } else {
                $spk = Kamus_spk::select('nilai_spk')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])->first();
                $nilai_spk = $spk->nilai_spk;

                $total_penunjang = 0;
                $total_realisasi = 0;
                $total_spm = 0;

                $spm = Kamus_spm::selectRaw('SUM(nilai_spm_spmu) as total_spm')
                                ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($spm)) {
                    $total_spm = doubleval($spm[0]["total_spm"]);
                }

                $penunjang = Penunjang::selectRaw('SUM(nilai_penunjang) as total_penunjang')
                                ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($penunjang)) {
                    $total_penunjang = doubleval($penunjang[0]["total_penunjang"]);

                    $daftar_penunjang = Penunjang::select('no_spk_penunjang', 'nilai_penunjang')
                                                ->where('no_spk_sp_dokumen', 'like', $value['no_bukti_perolehan'])
                                                ->get()
                                                ->toArray();
                }

                $nilai_spk_plus_penunjang = $total_spm + $total_penunjang;

                $realisasi = Kib::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                ->groupBy('no_bukti_perolehan')->where('no_bukti_perolehan', '=', $value["no_bukti_perolehan"])
                                ->get()
                                ->toArray();

                if(!empty($realisasi)) {
                    $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
                } else {
                    $total_realisasi = 0;
                }

                $this->total_spk += $nilai_spk_plus_penunjang;
                $this->total_realisasi += $total_realisasi;

                $total_nilai_spk_penunjang_tmp = $nilai_spk_plus_penunjang;
                $total_nilai_spk_penunjang += $total_nilai_spk_penunjang_tmp;
                $this->total_nilai_spk_penunjang = $total_nilai_spk_penunjang;

                $total_nilai_realisasi_tmp = $total_realisasi;
                $total_nilai_realisasi += $total_nilai_realisasi_tmp;
                $this->total_nilai_realisasi = $total_nilai_realisasi;

                $exported[$i++] = array(
                    "nomor_lokasi" => $value["nomor_lokasi"],
                    "nama_lokasi" => $this->nama_lokasi,
                    "no_spk" => $value["no_bukti_perolehan"],
                    "nilai_spk_plus_penunjang" => $nilai_spk_plus_penunjang,
                    "total_realisasi" => $total_realisasi
                );

                if(!empty($penunjang)) {
                    foreach ($daftar_penunjang as $p) {

                        $exported[$i++] = array(
                            "nomor_lokasi" => null,
                            "nama_lokasi" => null,
                            "no_spk" => $p['no_spk_penunjang'],
                            "nilai_spk_plus_penunjang" => $p['nilai_penunjang'],
                            "total_realisasi" => null
                        );
                    }
                }
            }
        }

        $export = collect($exported);
        return $export;
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
        $headings = [
            [
                "NOMOR LOKASI", "NAMA LOKASI", "NO SPK SP DOKUMEN", "NILAI SPK + PENUNJANG", "NILAI REALISASI"
            ],
            [
                2,3,4,5,6
            ]
        ];

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
            	$max = $event->sheet->getDelegate()->getHighestRow();
                ////////// set paper
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getPageSetup()->setFitToPage(true);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_FOLIO);
                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 4);
                $event->sheet->freezePane('G5');
                // end set paper

                /////////border heading
                $event->sheet->getStyle('A3:F3')->applyFromArray([
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
                $event->sheet->getStyle('A4:F4')->applyFromArray([
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
                $event->sheet->getStyle('A5:F'.$max)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A'.($max+1).':F'.($max+1))->applyFromArray([
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
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // end border

                // footer
                $event->sheet->getHeaderFooter()
                ->setOddFooter('&L&B '.$this->nama_jurnal.' / '.$this->tahun_sekarang . '&R &P / &N');
                // end footer

                /////////header
                $event->sheet->getDelegate()->mergeCells('A1:F1');
                $event->sheet->getDelegate()->setCellValue("A1", "Laporan ".$this->nama_jurnal ." ".$this->tahun_sekarang);
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
                $event->sheet->getRowDimension('1')->setRowHeight(50);
                $event->sheet->getStyle('A1')->getAlignment()->setWrapText(true);
                // end header

                ///////heading
                $event->sheet->getStyle('A3:F3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A3:F3')->getAlignment()->setWrapText(true);
                // end heading

                //////////numbering
                $event->sheet->getDelegate()->setCellValue("A3", "No.");
                $event->sheet->getDelegate()->setCellValue("A4", "1");
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

                // format text
                $event->sheet->getStyle('A4:F4')->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_TEXT ] );

                /////////////////column
                // b nomor lokasi
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(30);
                $event->sheet->getStyle('B3:B'.$max)->getAlignment()->setWrapText(true);
                // c nama lokasi
                $event->sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('C3:C'.$max)->getAlignment()->setWrapText(true);
                // d no spk sp dokumen
                $event->sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(25);
                $event->sheet->getStyle('D3:D'.$max)->getAlignment()->setWrapText(true);
                // E nilai spk + penunjang
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                // F nilai realisasi
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                ////////end column

                // total
                $baris_total = $max+1;
                $event->sheet->getStyle('A'.$baris_total.':F'.$baris_total)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('C'.$baris_total.':D'.$baris_total);
                $event->sheet->getDelegate()->setCellValue('C'.$baris_total, "TOTAL");

                // $event->sheet->getStyle('E'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                $event->sheet->getDelegate()->setCellValue('F'.$baris_total, $this->total_nilai_realisasi);
                $event->sheet->getStyle('F'.$baris_total)->getNumberFormat()->applyFromArray( [ 'formatCode' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE ] );
                // /end total

                $date = date('d/m/Y');
                $f1 = $max+3;
                for($i = 0; $i<5; $i++) {
                    $event->sheet->getDelegate()->mergeCells('A'.$f1.':C'.$f1);
                    $event->sheet->getStyle('A'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $event->sheet->getDelegate()->mergeCells('D'.$f1.':F'.$f1);
                    $event->sheet->getStyle('D'.$f1)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    if($i == 0) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "Mengetahui");
                        $event->sheet->getDelegate()->setCellValue('D'.$f1, "Mojokerto, ".$date);
                    }

                    if($i == 4) {
                        $event->sheet->getDelegate()->setCellValue('A'.$f1, "NIP");
                        $event->sheet->getDelegate()->setCellValue('D'.$f1, "NIP");
                    }

                    $f1++;
                }
            },
        ];
	}

	public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_IDR_SIMPLE
        ];
    }

	public function title(): string
    {
        return 'Data';
    }

}

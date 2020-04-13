<?php

namespace App\Http\Controllers\API\Sinkron;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Sinkron\Sinkron;
use APP\Models\Kamus\Kamus_spk;
use App\Http\Resources\Sinkron\SinkronCollection;
use Validator;

class SinkronController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importSpk(Request $request)
    {
        ini_set('memory_limit', '-1');
        $mysql = DB::connection('mysql');
        $sqlsrv = DB::connection('mysql2');
        $result = $sqlsrv->select('select distinct tahun, no_kontrak, kd_urusan, kd_bidang, kd_unit, kd_sub, kd_prog, id_prog, kd_keg, date_format(tgl_kontrak, "%Y-%m-%d") as tgl_kontrak, keperluan, waktu, nilai, nm_perusahaan, bentuk, alamat, sumber_dana from ta_kontrak');
        $result_spm = $sqlsrv->select('select * from kamus_spms');

        $data_spm = array();
        $data_spk = array();
        $index = 0;
        $j = 0;

        foreach ($result as $i) {
            foreach ($i as $value) {
                $i = json_decode(json_encode($i), true);
                $no_kontrak = stripslashes($i["no_kontrak"]);
                $no_kontrak = str_replace(' ','',$no_kontrak);
                $timestamp = date("Y-m-d H:i:s");

                $sub_unit = $i["kd_urusan"].$i["kd_bidang"].$i["kd_unit"].$i["kd_sub"];

                if($i["kd_urusan"] == 1 && $i["kd_bidang"] == 1 && $i["kd_unit"] == 1) {
                    if($i["sumber_dana"] == 3) {
                        if($i["kd_sub"] > 9 && $i["kd_sub"] < 100) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00002" . ".000" . $i["kd_sub"] ;
                        } else if($i["kd_sub"] > 100) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00002" . ".00" . $i["kd_sub"] ;
                        } else {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00002" . ".0000" . $i["kd_sub"] ;
                        }
                    } else {
                        if($i["kd_sub"] > 9 && $i["kd_sub"] < 100) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00001" . ".000" . $i["kd_sub"];
                        } else if($i["kd_sub"] > 100) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00001" . ".00" . $i["kd_sub"];
                        } else {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00001" . ".0000" . $i["kd_sub"];
                        }
                    }
                } else if ($i["kd_urusan"] == 1 && $i["kd_bidang"] == 2 && $i["kd_unit"] == 1) {
                    if($i["sumber_dana"] == 3) {
                        if($i["kd_sub"] > 9) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00002" . ".000" . $i["kd_sub"] ;
                        } else {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00002" . ".0000" . $i["kd_sub"] ;
                        }
                    } else {
                        if($i["kd_sub"] > 9) {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00001" . ".000" . $i["kd_sub"];
                        } else {
                            $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".00001" . ".0000" . $i["kd_sub"];
                        }
                    }
                } else {
                    if($i["kd_sub"] > 9) {
                        $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".000" . $i["kd_sub"] . ".00001";
                    } else {
                        $nomor_sub_unit = "12.01.35.16." . $i["kd_urusan"] . $i["kd_bidang"] . $i["kd_unit"] . ".0000" . $i["kd_sub"] . ".00001";
                    }
                }

                $id_kegiatan = $i["kd_urusan"] .".". $i["kd_bidang"] .".". $i["kd_prog"] .".". $i["kd_keg"];

                $id_kontrak = $id_kegiatan .".". $i["id_prog"] .".". $sub_unit .".". $no_kontrak .".". $i["nilai"];                

                $data_spk[$index]["id_kontrak"] = $id_kontrak;
                $data_spk[$index]["id_kegiatan"] = $id_kegiatan;
                $data_spk[$index]["nomor_sub_unit"] = $nomor_sub_unit;
                $data_spk[$index]["no_spk_sp_dokumen"] = $no_kontrak;
                $data_spk[$index]["tgl_spk_sp_dokumen"] = $i["tgl_kontrak"];
                $data_spk[$index]["deskripsi_spk_dokumen"] = $i["keperluan"];
                $data_spk[$index]["nilai_spk"] = $i["nilai"];
                $data_spk[$index]["tahun_spj"] = $i["tahun"];
                $data_spk[$index]["rekanan"] = $i["nm_perusahaan"];
                $data_spk[$index]["alamat_rekanan"] = $i["alamat"];
                $data_spk[$index]["termin"] = NULL;
                $data_spk[$index]["estimasi_termin"] = 0;
                $data_spk[$index]["addendum"] = 0;
                $data_spk[$index]["no_add"] = NULL;
                $data_spk[$index]["tgl_add"] = NULL;
                $data_spk[$index]["nilai_add"] = 0;
                $data_spk[$index]["tahun_add"] = NULL;
                $data_spk[$index]["jml_termin_add"] = 0;
                $data_spk[$index]["created_at"] = $timestamp;
                $data_spk[$index]["updated_at"] = $timestamp;
            }
            ++$index;
        }

        foreach ($result_spm as $i) {
            foreach ($i as $value) {
                $i = json_decode(json_encode($i), true);
                $no_spk_sp_dokumen = stripslashes($i["no_spk_sp_dokumen"]);
                $no_spk_sp_dokumen = str_replace(' ','',$no_spk_sp_dokumen);
                $no_spm_spmu = stripslashes($i["no_spm_spmu"]);
                $no_spm_spmu = str_replace(' ','',$no_spm_spmu);
                $no_spp = stripslashes($i["no_spp"]);
                $no_spp = str_replace(' ','',$no_spp);
                $no_sp2d = stripslashes($i["no_sp2d"]);
                $no_sp2d = str_replace(' ','',$no_sp2d);
                $timestamp = date("Y-m-d H:i:s");           

                $data_spm[$j]["id_spm"] = $i["id_spm"];
                $data_spm[$j]["nomor_sub_unit"] = $i["nomor_sub_unit"];
                $data_spm[$j]["no_spk_sp_dokumen"] = $no_spk_sp_dokumen;
                $data_spm[$j]["no_jurnal"] = $i["no_jurnal"];
                $data_spm[$j]["no_ba_st"] = $i["no_ba_st"];
                $data_spm[$j]["kode_rek_belanja"] = $i["kode_rek_belanja"];
                $data_spm[$j]["termin_ke"] = $i["termin_ke"];
                $data_spm[$j]["uraian_belanja"] = $i["uraian_belanja"];
                $data_spm[$j]["no_spm_spmu"] = $no_spm_spmu;
                $data_spm[$j]["tgl_spm_spmu"] = $i["tgl_spm_spmu"];
                $data_spm[$j]["nilai_spm_spmu"] = $i["nilai_spm_spmu"];
                $data_spm[$j]["tahun_spj"] = $i["tahun_spj"];
                $data_spm[$j]["operator"] = $i["operator"];
                $data_spm[$j]["tgl_update"] = $i["tgl_update"];
                $data_spm[$j]["no_sp2d"] = $no_sp2d;
                $data_spm[$j]["tgl_sp2d"] = $i["tgl_sp2d"];
                $data_spm[$j]["no_sko"] = $i["no_sko"];
                $data_spm[$j]["tgl_sko"] = $i["tgl_sko"];
                $data_spm[$j]["no_spp"] = $no_spp;
                $data_spm[$j]["tgl_spp"] = $i["tgl_spp"];
                $data_spm[$j]["keterangan"] = $i["keterangan"];
                $data_spm[$j]["tahun_jurnal"] = $i["tahun_jurnal"];
                $data_spm[$j]["no_key"] = $i["no_key"];
                $data_spm[$j]["created_at"] = $timestamp;
                $data_spm[$j]["updated_at"] = $timestamp;
            }
            ++$j;
        }

        if(!empty($data_spk)) {
            DB::table('kamus_spks')->truncate();
            $chunks = array_chunk($data_spk, 200);

            foreach ($chunks as $chunk) {
                $response_spk = DB::table('kamus_spks')->insert($chunk);
            }
        }

        if(!empty($data_spm)) {
            DB::table('kamus_spms')->truncate();
            $chunks = array_chunk($data_spm, 200);

            foreach ($chunks as $chunk) {
                $response_spk = DB::table('kamus_spms')->insert($chunk);
            }
        }

        if($response_spk || $response_spm)
            echo 'success';
        else
            echo 'fail';
    }

    public function importSpm(Request $request)
    {
        ini_set('memory_limit', '-1');
        $mysql = DB::connection('mysql');
        $sqlsrv = DB::connection('mysql2');
        $result_spm = $sqlsrv->select('select * from kamus_spms');

        $data_spm = array();
        $j = 0;

        foreach ($result_spm as $i) {
            foreach ($i as $value) {
                $i = json_decode(json_encode($i), true);
                $no_spk_sp_dokumen = stripslashes($i["no_spk_sp_dokumen"]);
                $no_spk_sp_dokumen = str_replace(' ','',$no_spk_sp_dokumen);
                $no_spm_spmu = stripslashes($i["no_spm_spmu"]);
                $no_spm_spmu = str_replace(' ','',$no_spm_spmu);
                $no_spp = stripslashes($i["no_spp"]);
                $no_spp = str_replace(' ','',$no_spp);
                $no_sp2d = stripslashes($i["no_sp2d"]);
                $no_sp2d = str_replace(' ','',$no_sp2d);
                $timestamp = date("Y-m-d H:i:s");           

                $data_spm[$j]["id_spm"] = $i["id_spm"];
                $data_spm[$j]["nomor_sub_unit"] = $i["nomor_sub_unit"];
                $data_spm[$j]["no_spk_sp_dokumen"] = $no_spk_sp_dokumen;
                $data_spm[$j]["no_jurnal"] = $i["no_jurnal"];
                $data_spm[$j]["no_ba_st"] = $i["no_ba_st"];
                $data_spm[$j]["kode_rek_belanja"] = $i["kode_rek_belanja"];
                $data_spm[$j]["termin_ke"] = $i["termin_ke"];
                $data_spm[$j]["uraian_belanja"] = $i["uraian_belanja"];
                $data_spm[$j]["no_spm_spmu"] = $no_spm_spmu;
                $data_spm[$j]["tgl_spm_spmu"] = $i["tgl_spm_spmu"];
                $data_spm[$j]["nilai_spm_spmu"] = $i["nilai_spm_spmu"];
                $data_spm[$j]["tahun_spj"] = $i["tahun_spj"];
                $data_spm[$j]["operator"] = $i["operator"];
                $data_spm[$j]["tgl_update"] = $i["tgl_update"];
                $data_spm[$j]["no_sp2d"] = $no_sp2d;
                $data_spm[$j]["tgl_sp2d"] = $i["tgl_sp2d"];
                $data_spm[$j]["no_sko"] = $i["no_sko"];
                $data_spm[$j]["tgl_sko"] = $i["tgl_sko"];
                $data_spm[$j]["no_spp"] = $no_spp;
                $data_spm[$j]["tgl_spp"] = $i["tgl_spp"];
                $data_spm[$j]["keterangan"] = $i["keterangan"];
                $data_spm[$j]["tahun_jurnal"] = $i["tahun_jurnal"];
                $data_spm[$j]["no_key"] = $i["no_key"];
                $data_spm[$j]["created_at"] = $timestamp;
                $data_spm[$j]["updated_at"] = $timestamp;
            }
            ++$j;
        }

        if(!empty($data_spm)) {
            DB::table('kamus_spms')->truncate();
            $response = DB::table('kamus_spms')->insert($data_spm);
        }

        if($response)
            echo 'success';
        else
            echo 'fail';
    }

    public function getLatestDate() 
    {
        $date = DB::table('kamus_spks')->select('created_at as date')->latest()->first();
        $date = json_decode(json_encode($date), true);

        return $date;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
}
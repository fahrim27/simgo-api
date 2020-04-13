<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Jurnal;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Models\Kamus\Kamus_lokasi;
use App\Http\Resources\Kamus\Kamus_lokasiCollection;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Kib_awal;
use App\Http\Resources\Jurnal\KibCollection;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Rincian_koreksi;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use App\Models\Jurnal\Rincian_keluar;
use App\Http\Resources\Jurnal\Rincian_keluarCollection;
use Validator;

class JurnalController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $jurnals = new JurnalCollection(Jurnal::all());
        } else {
            $jurnals = new JurnalCollection(Jurnal::paginate(10));
        }

        return $jurnals;
    }

    public function getJurnalByLokasi(Request $request, $kode_jurnal, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();

        if($kode_jurnal == "102") {
            $kode_jurnal = "202";
            if ($pagination === 0) {
                $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.ke_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                    ->where('jurnals.ke_lokasi', 'like', '%' . $nomor_lokasi . '%')
                    ->where('jurnals.kode_jurnal', '=', $kode_jurnal)->get());
            } else {
                $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.ke_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                    ->where('jurnals.ke_lokasi', 'like', '%' . $nomor_lokasi . '%')
                    ->where('jurnals.kode_jurnal', '=', $kode_jurnal)->paginate(10));
            }
        } else {
            if ($pagination === 0) {
                $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                    ->where('jurnals.nomor_lokasi', 'like', '%' . $nomor_lokasi . '%')
                    ->where('jurnals.kode_jurnal', $kode_jurnal)->get());
            } else {
                $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                    ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                    ->where('jurnals.nomor_lokasi', 'like', '%' . $nomor_lokasi . '%')
                    ->where('jurnals.kode_jurnal', '=', $kode_jurnal)->paginate(10));
            }
        }

        return $jurnals;
    }

    public function getJurnalPenunjang(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $kode_jurnal = "101";

        if ($pagination === 0) {
            $jurnals = new JurnalCollection(Jurnal::select('no_key', 'nomor_lokasi', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nilai_spk')->where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->where('kode_jurnal', $kode_jurnal)->get());
        } else {
            $jurnals = new JurnalCollection(Jurnal::select('no_key', 'nomor_lokasi', 'no_spk_sp_dokumen', 'tgl_spk_sp_dokumen', 'nilai_spk')->where('nomor_lokasi', 'like', '%'.$nomor_lokasi.'%')->where('kode_jurnal', $kode_jurnal)->paginate(10));
        }

        return $jurnals;
    }

    public function getJurnalByStatusLokasi(Request $request, $status, $kode_jurnal, $nomor_lokasi)
    {
        $pagination = (int) $request->header('Pagination');
        $input = $request->all();
        $nomor_unit = $nomor_lokasi;

        if ($kode_jurnal == "102") {
            if ($status == 0) {
                $kode_jurnal = "202";
                if ($pagination === 0) {
                    $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.ke_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                        ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                        ->where('jurnals.ke_lokasi', 'like', '%' . $nomor_unit . '%')
                        ->where('jurnals.kode_jurnal', 'like', $kode_jurnal . '%')
                        ->where('diterima', 0)
                        ->get());
                } else {
                    $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.ke_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                        ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                        ->where('jurnals.ke_lokasi', 'like', '%' . $nomor_unit . '%')
                        ->where('jurnals.kode_jurnal', 'like', $kode_jurnal . '%')
                        ->where('diterima', 0)
                        ->paginate(10));
                }
            } else {
                if ($pagination === 0) {
                    $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                        ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                        ->where('jurnals.nomor_lokasi', 'like', '%' . $nomor_unit . '%')
                        ->where('jurnals.kode_jurnal', 'like', $kode_jurnal . '%')
                        ->get());
                } else {
                    $jurnals = new JurnalCollection(Jurnal::join('kamus_lokasis', 'jurnals.nomor_lokasi', '=', 'kamus_lokasis.nomor_lokasi')
                        ->select('jurnals.*', 'kamus_lokasis.nama_lokasi')
                        ->where('jurnals.nomor_lokasi', 'like', '%' . $nomor_unit . '%')
                        ->where('jurnals.kode_jurnal', 'like', $kode_jurnal . '%')
                        ->paginate(10));
                }
            }
        }

        return $jurnals;
    }

    public function getBySpk(Request $request, $spk)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_spk = base64_decode($spk);

        if($pagination === 0) {
            $jurnals = new JurnalCollection(Jurnal::where('no_spk_sp_dokumen', '=', $nomor_spk)->get());
        } else {
            $jurnals = new JurnalCollection(Jurnal::where('no_spk_sp_dokumen', '=', $nomor_spk)->paginate($request->get('per_page')));
        }

        return $jurnals;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $nomor_lokasi = $input["nomor_lokasi"];
        $tahun_spj = date('Y') - 1;
        $kode_jurnal = $input["kode_jurnal"];

        $max_no_ba = Jurnal::select('no_ba_penerimaan')->where([
            ['nomor_lokasi', '=', $nomor_lokasi],
            ['tahun_spj', '=', $tahun_spj],
            ['kode_jurnal', '=', $kode_jurnal],
        ])->orderBy('no_key', 'DESC')->first();

        if(is_null($max_no_ba)) {
            $no_ba_penerimaan = "0001";
        } else {
            $max_no_ba = $max_no_ba->no_ba_penerimaan;
            $no_ba_penerimaan = intval($max_no_ba);
            ++$no_ba_penerimaan;

            $no_ba_penerimaan = strval($no_ba_penerimaan);
            $s = strlen($no_ba_penerimaan);
            if($s == 1) {
                $no_ba_penerimaan = "000".$no_ba_penerimaan;
            } else if($s == 2) {
                $no_ba_penerimaan = "00".$no_ba_penerimaan;
            } else if($s == 3) {
                $no_ba_penerimaan = "0".$no_ba_penerimaan;
            }
        }

        $input["no_key"] = $nomor_lokasi . "." . $kode_jurnal . "." . $no_ba_penerimaan . "." . $tahun_spj;
        $input["terkunci"] = "1";
        $input["tahun_spj"] = $tahun_spj;

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'terkunci' => 'required',
            'nomor_lokasi' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $jurnal = Jurnal::create($input);

        return $this->sendResponse($jurnal->toArray(), 'Jurnal created successfully.');
    }

    public function save(Request $request, $kode_jurnal)
    {
        $input = $request->all();

        $nomor_lokasi = $input["nomor_lokasi"];
        $tahun_spj = date('Y') - 1;

        $max_no_ba = Jurnal::select('no_ba_penerimaan')->where([
            ['nomor_lokasi', '=', $nomor_lokasi],
            ['tahun_spj', '=', $tahun_spj],
            ['kode_jurnal', '=', $kode_jurnal],
        ])->orderBy('no_key', 'DESC')->first();

        if(is_null($max_no_ba)) {
            $no_ba_penerimaan = "0001";
        } else {
            $max_no_ba = $max_no_ba->no_ba_penerimaan;
            $no_ba_penerimaan = intval($max_no_ba);
            ++$no_ba_penerimaan;

            $no_ba_penerimaan = strval($no_ba_penerimaan);
            $s = strlen($no_ba_penerimaan);
            if($s == 1) {
                $no_ba_penerimaan = "000".$no_ba_penerimaan;
            } else if($s == 2) {
                $no_ba_penerimaan = "00".$no_ba_penerimaan;
            } else if($s == 3) {
                $no_ba_penerimaan = "0".$no_ba_penerimaan;
            }
        }

        $input["no_key"] = $nomor_lokasi . "." . $kode_jurnal . "." . $no_ba_penerimaan . "." . $tahun_spj;
        $input["terkunci"] = "1";
        $input["tahun_spj"] = $tahun_spj;

        if ($kode_jurnal == "202") {
            $ke_lokasi = $input["ke_lokasi"];
            $nama_lokasi_asal = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', '=', $nomor_lokasi)->get()->toArray();
            $nama_lokasi_tujuan = Kamus_lokasi::select('nama_lokasi')->where('nomor_lokasi', '=', $ke_lokasi)->get()->toArray();
            $lokasi_asal = $nama_lokasi_asal[0]["nama_lokasi"];
            $lokasi_tujuan = $nama_lokasi_tujuan[0]["nama_lokasi"];

            $input["dari_lokasi"] = $nomor_lokasi;
            $input["no_ba_penerimaan"] = $no_ba_penerimaan;
            $input["tgl_ba_penerimaan"] = $input["tgl_ba_st"];
            $input["nomor"] = $no_ba_penerimaan;
            $input["instansi_yg_menyerahkan"]  = $lokasi_asal;
            $input["instansi_tujuan"]  = $lokasi_tujuan;
            $input["instansi_penerima"]  = $lokasi_tujuan;
            $input["kode_jurnal"] = $kode_jurnal;
            $input["diterima"] = "0";
        } else if ($kode_jurnal == "107") {
            $input["no_ba_penerimaan"] = $no_ba_penerimaan;
            $input["tgl_ba_penerimaan"] = $input["tgl_jurnal"];
            $input["nomor"] = $no_ba_penerimaan;
            $input["kode_jurnal"] = $kode_jurnal;
        } else if ($kode_jurnal == "208") {
            $input["no_ba_penerimaan"] = $no_ba_penerimaan;
            $input["tgl_ba_penerimaan"] = $input["tgl_ba_st"];
            $input["nomor"] = $no_ba_penerimaan;
            $input["kode_jurnal"] = $kode_jurnal;
        } else if (substr($kode_jurnal, 0, 1) == "3") {
            $input["no_ba_penerimaan"] = $no_ba_penerimaan;
            $input["tgl_ba_penerimaan"] = $input["tgl_jurnal"];
            $input["tgl_ba_st"] = $input["tgl_jurnal"];
            $input["nomor"] = $no_ba_penerimaan;
            $input["kode_jurnal"] = $kode_jurnal;
        } else {
            $input["no_ba_penerimaan"] = $no_ba_penerimaan;
            $input["tgl_ba_penerimaan"] = $input["tgl_ba_st"];
            $input["nomor"] = $no_ba_penerimaan;
            $input["kode_jurnal"] = $kode_jurnal;
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'nomor_lokasi' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $jurnal = Jurnal::create($input);

        return $this->sendResponse($jurnal->toArray(), 'Jurnal created successfully.');
    }

    public function enter(Request $request, $kode_jurnal)
    {
        $input = $request->all();

        $tahun_spj = date('Y') - 1;
        $no_key = $input["no_key"];

        $jurnal_keluar = Jurnal::where('no_key', $input["no_key"])->first();
        $nomor_lokasi = $jurnal_keluar->ke_lokasi;
        $dari_lokasi = $jurnal_keluar->dari_lokasi;
        $ke_lokasi = $jurnal_keluar->ke_lokasi;
        $no_ba_p = $jurnal_keluar->no_ba_penerimaan;
        $tgl_ba_p = $jurnal_keluar->tgl_ba_penerimaan;
        $instansi_yg_menyerahkan = $jurnal_keluar->instansi_yg_menyerahkan;
        $instansi_tujuan = $jurnal_keluar->instansi_tujuan;
        $no_ba_st = $jurnal_keluar->no_ba_st;
        $tgl_ba_st = $jurnal_keluar->tgl_ba_st;
        $nip_pengeluar = $jurnal_keluar->nip_pengeluar;
        $nama_pengeluar = $jurnal_keluar->nama_pengeluar;
        $jabatan_pengeluar = $jurnal_keluar->jabatan_pengeluar;
        $pangkat_gol_pengeluar = $jurnal_keluar->pangkat_gol_pengeluar;
        $nip_penerima = $jurnal_keluar->nip_penerima;
        $nama_penerima = $jurnal_keluar->nama_penerima;
        $jabatan_penerima = $jurnal_keluar->jabatan_penerima;
        $pangkat_gol_penerima = $jurnal_keluar->pangkat_gol_penerima;
        $nip_pengeluar = $jurnal_keluar->nip_pengeluar;
        $atasan_atasan_pengeluar = $jurnal_keluar->atasan_atasan_pengeluar;
        $jabatan_atasan_pengeluar = $jurnal_keluar->jabatan_atasan_pengeluar;
        $pangkat_gol_atasan_pengeluar = $jurnal_keluar->pangkat_gol_atasan_pengeluar;

        $input["nomor_lokasi"] = $jurnal_keluar->ke_lokasi;
        $input["dari_lokasi"] = $jurnal_keluar->dari_lokasi;
        $input["ke_lokasi"] = $jurnal_keluar->ke_lokasi;
        $input["no_ba_p"] = $jurnal_keluar->no_ba_penerimaan;
        $input["tgl_ba_p"] = $jurnal_keluar->tgl_ba_penerimaan;
        $input["instansi_yg_menyerahkan"] = $jurnal_keluar->instansi_yg_menyerahkan;
        $input["instansi_tujuan"] = $jurnal_keluar->instansi_tujuan;
        $input["no_ba_st"] = $jurnal_keluar->no_ba_st;
        $input["tgl_ba_st"] = $jurnal_keluar->tgl_ba_st;
        $input["nip_pengeluar"] = $jurnal_keluar->nip_pengeluar;
        $input["nama_pengeluar"] = $jurnal_keluar->nama_pengeluar;
        $input["jabatan_pengeluar"] = $jurnal_keluar->jabatan_pengeluar;
        $input["pangkat_gol_pengeluar"] = $jurnal_keluar->pangkat_gol_pengeluar;
        $input["nip_penerima"] = $jurnal_keluar->nip_penerima;
        $input["nama_penerima"] = $jurnal_keluar->nama_penerima;
        $input["jabatan_penerima"] = $jurnal_keluar->jabatan_penerima;
        $input["pangkat_gol_penerima"] = $jurnal_keluar->pangkat_gol_penerima;
        $input["nip_pengeluar"] = $jurnal_keluar->nip_pengeluar;
        $input["atasan_atasan_pengeluar"] = $jurnal_keluar->atasan_atasan_pengeluar;
        $input["jabatan_atasan_pengeluar"] = $jurnal_keluar->jabatan_atasan_pengeluar;
        $input["pangkat_gol_atasan_pengeluar"] = $jurnal_keluar->pangkat_gol_atasan_pengeluar;
        $input["instansi_penerima"]  = $jurnal_keluar->instansi_penerima;
        $input["kode_jurnal"] = $kode_jurnal;
        $input["terkunci"] = "1";
        $input["no_ba_penerimaan"] = $jurnal_keluar->no_ba_penerimaan;
        $input["tgl_ba_penerimaan"] = $jurnal_keluar->tgl_ba_penerimaan;
        $input["tahun_spj"] = $tahun_spj;
        $input["nomor"] = $jurnal_keluar->no_ba_penerimaan;

        $max_no_ba = Jurnal::select('no_ba_penerimaan')->where([
            ['nomor_lokasi', '=', $nomor_lokasi],
            ['tahun_spj', '=', $tahun_spj],
            ['kode_jurnal', '=', $kode_jurnal],
        ])->orderBy('no_key', 'DESC')->first();

        if (is_null($max_no_ba)) {
            $no_ba_penerimaan = "0001";
        } else {
            $max_no_ba = $max_no_ba->no_ba_penerimaan;
            $no_ba_penerimaan = intval($max_no_ba);
            ++$no_ba_penerimaan;

            $no_ba_penerimaan = strval($no_ba_penerimaan);
            $s = strlen($no_ba_penerimaan);
            if ($s == 1) {
                $no_ba_penerimaan = "000" . $no_ba_penerimaan;
            } else if ($s == 2) {
                $no_ba_penerimaan = "00" . $no_ba_penerimaan;
            } else if ($s == 3) {
                $no_ba_penerimaan = "0" . $no_ba_penerimaan;
            }
        }

        $input["no_ba_penerimaan"] = $no_ba_penerimaan;
        $input["no_key"] = $nomor_lokasi . "." . $kode_jurnal . "." . $no_ba_penerimaan . "." . $tahun_spj;

        if ($kode_jurnal == "102") {
            $rincian_masuk = array();
            $rincian_keluar = array();
            $index = 0;
            $rincian = Rincian_keluar::where('no_key', $no_key)->get();

            foreach ($rincian as $key=>$i) {

                    $i = json_decode(json_encode($i), true);
                    $id_aset = stripslashes($i["id_aset"]);

                    $kib = Kib::where('id_aset', $id_aset)->first();

                    $rincian_masuk[$index]["no_key"] = $input["no_key"];
                    $rincian_masuk[$index]["nomor_lokasi"] = $nomor_lokasi;
                    $rincian_masuk[$index]["kode_jurnal"] = $kode_jurnal;
                    $rincian_masuk[$index]["terkunci"] = "1";
                    $rincian_masuk[$index]["tahun_spj"] = $tahun_spj;
                    $rincian_masuk[$index]["no_ba_penerimaan"] = $no_ba_penerimaan;
                    $rincian_masuk[$index]["id_aset"] = $kib->id_aset;
                    $rincian_masuk[$index]["id_transaksi"] = $kode_jurnal;
                    $rincian_masuk[$index]["tgl_perolehan"] = $tgl_ba_p;
                    $rincian_masuk[$index]["kode_jurnal"] = $kode_jurnal;
                    $rincian_masuk[$index]["jumlah_barang"] = $i["jumlah_barang"];
                    $rincian_masuk[$index]["saldo_barang"] = $i["jumlah_barang"];
                    $rincian_masuk[$index]["saldo_gudang"] = $i["jumlah_barang"];
                    $rincian_masuk[$index]["satuan"] = $kib->satuan;
                    $rincian_masuk[$index]["harga_satuan"] = $i["harga_satuan"];
                    $rincian_masuk[$index]["pajak"] = $i["pajak"];
                    $rincian_masuk[$index]["harga_total"] = $i["nilai_keluar"];
                    $rincian_masuk[$index]["harga_total_plus_pajak"] = $i["nilai_keluar"];
                    $rincian_masuk[$index]["harga_total_plus_pajak_saldo"] = $i["nilai_keluar"];
                    $rincian_masuk[$index]["tahun_pengadaan"] = $kib->tahun_pengadaan;
                    $rincian_masuk[$index]["tahun_perolehan"] = $tahun_spj;
                    $rincian_masuk[$index]["ak_penyusutan"] = $i["akumulasi_penyusutan"];
                    $rincian_masuk[$index]["waktu_susut"] = $i["waktu_susut"];
                    $rincian_masuk[$index]["tahun_buku"] = $i["tahun_buku"];
                    $rincian_masuk[$index]["nilai_buku"] = $i["nilai_buku"];
                    $rincian_masuk[$index]["baik"] = $i["baik"];
                    $rincian_masuk[$index]["kb"] = $i["kb"];
                    $rincian_masuk[$index]["rb"] = $i["rb"];
                    $rincian_masuk[$index]["sendiri"] = $i["sendiri"];
                    $rincian_masuk[$index]["sengketa"] = $i["sengketa"];
                    $rincian_masuk[$index]["pihak_3"] = $i["pihak_3"];
                    $rincian_masuk[$index]["keterangan"] = $i["keterangan"];
                    $rincian_masuk[$index]["kode_108"] = $i["kode_108"];
                    $rincian_masuk[$index]["kode_64"] = $i["kode_64"];

                    if ($i["rb"] > 0 && $i["baik"] == 0 && $i["kb"] == 0) {
                        $rincian_masuk[$index]["jenis_aset"] = "R";
                    } else {
                        $rincian_masuk[$index]["jenis_aset"] = "A";
                    }

                    $rincian_masuk[$index]["nama_barang"] = $kib->nama_barang;
                    $rincian_masuk[$index]["merk_alamat"] = $kib->merk_alamat;
                    $rincian_masuk[$index]["tipe"] = $kib->tipe;
                    $rincian_masuk[$index]["kode_kepemilikan"] = $kib->kode_kepemilikan;
                    $rincian_masuk[$index]["bidang_barang"] = $kib->bidang_barang;
                    $rincian_masuk[$index]["no_sertifikat"] = $kib->no_sertifikat;
                    $rincian_masuk[$index]["tgl_sertifikat"] = $kib->tgl_sertifikat;
                    $rincian_masuk[$index]["atas_nama_sertifikat"] = $kib->atas_nama_sertifikat;
                    $rincian_masuk[$index]["status_tanah"] = $kib->status_tanah;
                    $rincian_masuk[$index]["jenis_dokumen_tanah"] = $kib->jenis_dokumen_tanah;
                    $rincian_masuk[$index]["panjang_tanah"] = $kib->panjang_tanah;
                    $rincian_masuk[$index]["lebar_tanah"] = $kib->lebar_tanah;
                    $rincian_masuk[$index]["luas_tanah"] = $kib->luas_tanah;
                    $rincian_masuk[$index]["no_imb"] = $kib->no_imb;
                    $rincian_masuk[$index]["tgl_imb"] = $kib->tgl_imb;
                    $rincian_masuk[$index]["atas_nama_dokumen_gedung"] = $kib->atas_nama_dokumen_gedung;
                    $rincian_masuk[$index]["jenis_dok_gedung"] = $kib->jenis_dok_gedung;
                    $rincian_masuk[$index]["konstruksi"] = $kib->konstruksi;
                    $rincian_masuk[$index]["bahan"] = $kib->bahan;
                    $rincian_masuk[$index]["jumlah_lantai"] = $kib->jumlah_lantai;
                    $rincian_masuk[$index]["luas_lantai"] = $kib->luas_lantai;
                    $rincian_masuk[$index]["luas_bangunan"] = $kib->luas_bangunan;
                    $rincian_masuk[$index]["panjang"] = $kib->panjang;
                    $rincian_masuk[$index]["lebar"] = $kib->lebar;
                    $rincian_masuk[$index]["no_regs_induk_tanah"] = $kib->no_regs_induk_tanah;
                    $rincian_masuk[$index]["pos_entri_tanah"] = $kib->pos_entri_tanah;
                    $rincian_masuk[$index]["id_transaksi_tanah"] = $kib->id_transaksi_tanah;
                    $rincian_masuk[$index]["umur_teknis"] = $kib->umur_teknis;
                    $rincian_masuk[$index]["nilai_residu"] = $kib->nilai_residu;
                    $rincian_masuk[$index]["nama_ruas"] = $kib->nama_ruas;
                    $rincian_masuk[$index]["pangkal"] = $kib->pangkal;
                    $rincian_masuk[$index]["ujung"] = $kib->ujung;
                    $rincian_masuk[$index]["no_bpkb"] = $kib->no_bpkb;
                    $rincian_masuk[$index]["tgl_bpkb"] = $kib->tgl_bpkb;
                    $rincian_masuk[$index]["no_stnk"] = $kib->no_stnk;
                    $rincian_masuk[$index]["tgl_stnk"] = $kib->tgl_stnk;
                    $rincian_masuk[$index]["no_rangka_seri"] = $kib->no_rangka_seri;
                    $rincian_masuk[$index]["nopol"] = $kib->nopol;
                    $rincian_masuk[$index]["warna"] = $kib->warna;
                    $rincian_masuk[$index]["no_mesin"] = $kib->no_mesin;
                    $rincian_masuk[$index]["bahan_bakar"] = $kib->bahan_bakar;
                    $rincian_masuk[$index]["cc"] = $kib->cc;
                    $rincian_masuk[$index]["tahun_pembuatan"] = $kib->tahun_pembuatan;
                    $rincian_masuk[$index]["tahun_perakitan"] = $kib->tahun_perakitan;
                    $rincian_masuk[$index]["no_faktur"] = $kib->no_faktur;
                    $rincian_masuk[$index]["tgl_faktur"] = $kib->tgl_faktur;
                    $rincian_masuk[$index]["ukuran"] = $kib->ukuran;
                    $rincian_masuk[$index]["wajib_kalibrasi"] = $kib->wajib_kalibrasi;
                    $rincian_masuk[$index]["periode_kalibrasi"] = $kib->periode_kalibrasi;
                    $rincian_masuk[$index]["waktu_kalibrasi"] = $kib->waktu_kalibrasi;
                    $rincian_masuk[$index]["tgl_kalibrasi"] = $kib->tgl_kalibrasi;
                    $rincian_masuk[$index]["pengkalibrasi"] = $kib->pengkalibrasi;
                    $rincian_masuk[$index]["biaya_kalibrasi"] = $kib->biaya_kalibrasi;
                    $rincian_masuk[$index]["usul_hapus"] = $kib->usul_hapus;
                    $rincian_masuk[$index]["tgl_usul_hapus"] = $kib->tgl_usul_hapus;
                    $rincian_masuk[$index]["alasan_usul_hapus"] = $kib->alasan_usul_hapus;
                    $rincian_masuk[$index]["kunci_tmp"] = $kib->kunci_tmp;
                    $rincian_masuk[$index]["terkunci"] = $kib->terkunci;
                    $rincian_masuk[$index]["tahun_verifikasi"] = $kib->tahun_verifikasi;
                    $rincian_masuk[$index]["tutup_buku"] = $kib->tutup_buku;
                    $rincian_masuk[$index]["komtabel"] = $kib->komtabel;
                    $rincian_masuk[$index]["tdk_ditemukan"] = $kib->tdk_ditemukan;
                    $rincian_masuk[$index]["created_at"] = Date('Y-m-d H:i:s');
                    $rincian_masuk[$index]["updated_at"] = Date('Y-m-d H:i:s');

                    $temp = $nomor_lokasi . "." . $tahun_spj . "." . $kode_jurnal . "." . $i["kode_108"];

                    $max_id_aset = Rincian_masuk::select('id_aset')->where('id_aset', 'like', '%' . $temp . '%')->orderBy('id_aset', 'DESC')->first();

                    if (is_null($max_id_aset)) {
                        $id_aset_index = "00001";
                    } else {
                        $max_id_aset = $max_id_aset->id_aset;

                        if (empty($max_id_aset)) {
                            $id_aset_index = "00001";
                        } else {
                            $id_aset_index = str_replace($temp, '', $max_id_aset);
                            $id_aset_index = str_replace('.', '', $id_aset_index);

                            $id_aset_index = intval($id_aset_index);
                            ++$id_aset_index;
                            $id = $id_aset_index;

                            $id_aset_index = strval($id_aset_index);
                            $s = strlen($id_aset_index);
                            if ($s == 1) {
                                $id_aset_index = "0000" . $id_aset_index;
                            } else if ($s == 2) {
                                $id_aset_index = "000" . $id_aset_index;
                            } else if ($s == 3) {
                                $id_aset_index = "00" . $id_aset_index;
                            } else if ($s == 4) {
                                $id_aset_index = "0" . $id_aset_index;
                            }
                        }
                    }

                    $id_aset = $temp . "." . $id_aset_index;

                    $rincian_masuk[$index]["id_aset"] = $id_aset;
                    $rincian_masuk[$index]["no_register"] = $kib->id_aset;

                    $rincian_keluar[$index]["id_aset"] = $kib->id_aset;
                    $rincian_keluar[$index]["saldo_barang"] = $kib->saldo_barang - $i["jumlah_barang"];
                    $rincian_keluar[$index]["saldo_gudang"] = $kib->saldo_barang - $i["jumlah_barang"];
                    $rincian_keluar[$index]["harga_total_plus_pajak_saldo"] = $rincian_keluar[$index]["saldo_barang"] * $kib->harga_satuan;

                    if($kib->ak_penyusutan == 0 || $kib->saldo_barang == 0) {
                        $ak_penyusutan = 0;
                    } else {
                        $ak_penyusutan = $rincian_keluar[$index]["saldo_barang"] / $kib->saldo_barang * $kib->ak_penyusutan;
                    }

                    $rincian_keluar[$index]["ak_penyusutan"] = $ak_penyusutan;
                    $rincian_keluar[$index]["nilai_buku"] = $rincian_keluar[$index]["harga_total_plus_pajak_saldo"] - $rincian_keluar[$index]["ak_penyusutan"];

                    $rincian_keluar[$index]["baik"] = $kib->baik - $i["baik"];
                    $rincian_keluar[$index]["kb"] = $kib->kb - $i["kb"];
                    $rincian_keluar[$index]["rb"] = $kib->rb - $i["rb"];
                    $rincian_keluar[$index]["sendiri"] = $kib->sendiri - $i["sendiri"];
                    $rincian_keluar[$index]["sengketa"] = $kib->sengketa - $i["sengketa"];
                    $rincian_keluar[$index]["pihak_3"] = $kib->pihak_3 - $i["pihak_3"];
                ++$index;
            }

        }

        foreach ($rincian_keluar as $row) {
            $update_kib = Kib::where('id_aset', $row["id_aset"])->update($row);

            $rm = Rincian_masuk::where('id_aset', $row["id_aset"])->first();
            if(!empty($rm)) {
                $update_rm = Rincian_masuk::where('id_aset', $row["id_aset"])->update($row);
            }
            //masih perlu pengecekan tambahan untuk kib awal
        }

        $validator = Validator::make($input, [
            'no_key' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data_jurnal_keluar = array();
        $data_jurnal_keluar["diterima"] = 1;
        $input["nomor_lokasi"] = $nomor_lokasi;

        $jurnal = Jurnal::create($input);
        $jurnal_keluar = Jurnal::where('no_key', $no_key)->update($data_jurnal_keluar);

        $tampungan = [];
        $inserted = [];
        foreach ($rincian_masuk as $row) {
            $merger = "";
            if(!in_array($row['id_aset'], $tampungan)){
                array_push($tampungan, $row['id_aset']);
                $merger = $row['id_aset'];
            }else{
                $addition = 1;
                $merger = $this->recreate($row['id_aset'],$addition);

                if(!in_array($merger, $tampungan)){
                    array_push($tampungan, $merger);
                }
                else{
                    while(in_array($merger, $tampungan)){
                        $addition++;
                        $merger = $this->recreate($row['id_aset'],$addition);
                    }
                    array_push($tampungan, $merger);
                }
            }
            $row['id_aset'] = $merger;

            array_push($inserted, $row);


            $rincian_masuk = Rincian_masuk::create($row);
            $kib_masuk = Kib::create($row);
        }

        return $this->sendResponse($inserted, 'Jurnal created successfully.');
    }

    function recreate($number,$addition){
        $aw = explode(".", $number);
        $take_last = array_pop($aw);

        // hitung yg sama
        // $count = array_count_values($tampungan);
        $take_last = $take_last +$addition;

        $s = strlen($take_last);
        if ($s == 1) {
            $take_last = "0000" . $take_last;
        } else if ($s == 2) {
            $take_last = "000" . $take_last;
        } else if ($s == 3) {
            $take_last = "00" . $take_last;
        } else if ($s == 4) {
            $take_last = "0" . $take_last;
        }

        $piece = explode(".", $number);
        $last_remove = array_pop($piece);
        $merger = trim(implode('.', $piece)).".".$take_last;
        return $merger;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($no_key)
    {
        $jurnal = Jurnal::find($no_key);

        if (is_null($jurnal)) {
            return $this->sendError('Jurnal not found.');
        }

        return $this->sendResponse($jurnal->toArray(), 'Jurnal retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal $jurnal)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'nomor_lokasi' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $jurnal->update($input);

        return $this->sendResponse($jurnal->toArray(), 'Jurnal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * modify delete condition from jurnal
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnal $jurnal)
    {   
        $jurnal->toArray();

        //masuk
        if ($jurnal["kode_jurnal"] == 102) {
            $no_key = Kib::where('no_key', 'LIKE', '%'.$jurnal["no_key"].'%')->get();

            if (!empty($no_key)) {
                foreach ($no_key as $kibs) {
                    $rincian_koreksi = Rincian_koreksi::where('id_aset', 'LIKE', '%'.$kibs["id_aset"].'%')->delete();
                }        
            }

            foreach ($no_key as $key) {
                $aset_kibs = Kib::where('id_aset', $key["no_register"])->get();

                if (isset($aset_kibs)) {

                    foreach ($aset_kibs as $kibs) {
                        $aset_keluar = Rincian_keluar::where('id_aset', $kibs["id_aset"])->get();

                        foreach ($aset_keluar as $ak) {
                            $rKeluar = Rincian_keluar::where('no_key', $ak["no_key"])->get();

                            foreach ($rKeluar as $rincian_keluar) {
                                if ($rincian_keluar["tahun_spj"] < 2019) {
                                    $kibs_awal = Kib_awal::where('id_aset', $rincian_keluar["id_aset"])->first();
                                    $rincian_masuk = Rincian_masuk::where('id_aset', $rincian_keluar["id_aset"])->first();

                                    //return $this->sendResponse(array("kibs awal" => $kibs_awal, "rincian masuk" => $rincian_masuk), "NO no NO");

                                    if (isset($kibs_awal)) {
                                        $updateKibs["saldo_barang"] = $kibs_awal["saldo_barang"] + $rincian_keluar["jumlah_barang"];
                                        $updateKibs["harga_total_plus_pajak_saldo"] = $kibs["harga_total_plus_pajak_saldo"] + $rincian_keluar["nilai_keluar"];

                                        $kibs_awal->update($updateKibs);
                                    }

                                    else if(isset($rincian_masuk)) {
                                        $updateRincianMasuk["saldo_barang"] = $rincian_masuk["saldo_barang"] + $rincian_keluar["jumlah_barang"];
                                        $updateRincianMasuk["harga_total_plus_pajak_saldo"] = $rincian_masuk["harga_total_plus_pajak_saldo"] + $rincian_keluar["nilai_keluar"];

                                        $rincian_masuk->update($updateRincianMasuk);
                                    }                         
                                }
                                else{
                                    $kibs = Kib::where('id_aset', $rincian_keluar["id_aset"])->first();
                                    $rincian_masuk = Rincian_masuk::where('id_aset', $rincian_keluar["id_aset"])->first();

                                    //return $this->sendResponse(array("kibs" => $kibs, "rincian masuk" => $rincian_masuk), "NO no NO");

                                    if (isset($kibs)) {
                                        $updateKibs["saldo_barang"] = $kibs["saldo_barang"] + $rincian_keluar["jumlah_barang"];
                                        $updateKibs["harga_total_plus_pajak_saldo"] = $kibs["harga_total_plus_pajak_saldo"] + $rincian_keluar["nilai_keluar"];

                                         $kibs->update($updateKibs);
                                    }
                                    else if(isset($rincian_masuk))
                                    {
                                        $updateRincianMasuk["saldo_barang"] = $rincian_masuk["saldo_barang"] + $rincian_keluar["jumlah_barang"];
                                        $updateRincianMasuk["harga_total_plus_pajak_saldo"] = $rincian_masuk["harga_total_plus_pajak_saldo"] + $rincian_keluar["nilai_keluar"];

                                        $rincian_masuk->update($updateRincianMasuk);
                                    }     
                                    
                                }
                            }
                        }
                    }

                }

                Kib::where('no_key', $key["no_key"])->delete();
                Rincian_masuk::where('no_key', $key["no_key"])->delete();
            }

            $update_jurnal["diterima"] = "0";
            $jurnal->update($update_jurnal);

            return $this->sendResponse(array('Jurnal' => $jurnal), 'Jurnal update successfully.');         
        }

        //keluar
        elseif($jurnal["kode_jurnal"] == 202) {

            $id_aset = Rincian_keluar::where('no_key', 'LIKE', '%'.$jurnal["no_key"].'%')->first();

            if (isset($id_aset)) {
               $no_key = Kib::where('no_register', 'LIKE', '%'.$id_aset["id_aset"].'%')->first();

               if (isset($no_key)) {
                   $rincian_masuk = Rincian_masuk::where('no_key', 'LIKE', '%'.$no_key["no_key"].'%')->get();
                   $jumlahBarang = 0 ;
                   $jumlahHarga = 0 ;

                   //return $rincian_masuk->toArray();

                   $jumlahBarang = Rincian_masuk::select(DB::raw("SUM(jumlah_barang)"))->where('no_key', 'LIKE', '%'.$no_key["no_key"].'%');

                   // return $rincian_masuk->toArray();

                   foreach ($rincian_masuk as $rm) {
                       if (Rincian_koreksi::where('id_aset', 'LIKE', '%'.$rm["id_aset"].'%')->exists()) {
                           $rincian_koreksi = Rincian_koreksi::where('id_aset', 'LIKE', '%'.$rm["id_aset"].'%')->delete();
                       }

                        $rincianKeluar = Rincian_keluar::where('id_aset', $rm["no_register"])->first();

                        if ($jurnal["tahun_spj"] >= 2019) {
                            $rincianMasuk = Rincian_masuk::where('nomor_lokasi', 'LIKE', '%'.$jurnal["ke_lokasi"].'%')->where('id_aset', 'LIKE', '%'.$rm["id_aset"].'%')->first();
                            $kibs = Kib::where('nomor_lokasi', 'LIKE', '%'.$rincianMasuk["nomor_lokasi"].'%')->where('id_aset', 'LIKE', '%'.$rincianMasuk["id_aset"].'%')->first();

                            $updateRincianMasuk["saldo_barang"] = $rincianMasuk["saldo_barang"] += $rincianKeluar["jumlah_barang"];
                            $updateRincianMasuk["harga_total_plus_pajak_saldo"] = $rincianMasuk["harga_total_plus_pajak_saldo"] += $rincianKeluar["nilai_keluar"];

                            $updateKibs["saldo_barang"] = $kibs["saldo_barang"] += $rincianKeluar["jumlah_barang"];
                            $updateKibs["harga_total_plus_pajak_saldo"] = $kibs["harga_total_plus_pajak_saldo"] += $rincianKeluar["nilai_keluar"];                      
                               $kibs->update($updateKibs);
                               $rincianMasuk->update($updateRincianMasuk);
                               
                           }
                           else{
                               $rincianMasuk = Rincian_masuk::where('nomor_lokasi', 'LIKE', '%'.$jurnal["ke_lokasi"].'%')->first();
                                $kibs = Kib_awal::where('nomor_lokasi', 'LIKE', '%'.$rincianMasuk["nomor_lokasi"].'%')->where('id_aset', 'LIKE', '%'.$rincianMasuk["id_aset"].'%')->first();

                                $updateRincianMasuk["saldo_barang"] = $rincianMasuk["saldo_barang"] += $rincianKeluar["jumlah_barang"];
                                $updateRincianMasuk["harga_total_plus_pajak_saldo"] = $rincianMasuk["harga_total_plus_pajak_saldo"] += $rincianKeluar["nilai_keluar"];

                                $updateKibs["saldo_barang"] = $kibs["saldo_barang"] += $rincianKeluar["jumlah_barang"];
                                $updateKibs["harga_total_plus_pajak_saldo"] = $kibs["harga_total_plus_pajak_saldo"] += $rincianKeluar["nilai_keluar"];                      
                                   $kibs->update($updateKibs);
                                   $rincianMasuk->update($updateRincianMasuk);

                           }
                   
                        $rm->delete();
                        Kib::where('id_aset', $rm["id_aset"])->delete();

                   }                 
               }
            }

            Rincian_keluar::where('no_key', $jurnal["no_key"])->delete();
            $jurnal->delete();

            return $this->sendResponse(array('Jurnal' => $jurnal, 'Rincian Masuk' => $rincianMasuk, 'Data Kib' => $kibs, 'Rincian Masuk 101' => $rincian_masuk), 'Jurnal delete successfully.');
        }

        $kode_jurnalKoreksi = substr($jurnal["kode_jurnal"], 0);
        //koreksi
        elseif($kode_jurnalKoreksi == 3){
            $rincianKoreksi = Rincian_koreksi::where('no_key', $jurnal["no_key"])->get();

            foreach ($rincianKoreksi as $key => $rK) {
                $rk->toArray();

                if (Rincian_keluar::where('id_aset', $rK["id_aset"])->exists()) {
                    $no_key = Rincian_keluar::select('no_key')->where('id_aset', $rK["id_aset"])->first()->no_key;

                    $jurnalMasuk = Jurnal::where('no_key', $no_key)->first();

                    if (isset($jurnalMasuk["diterima"] == 1)) {
                        if ($jurnalMasuk["kode_koreksi", 302] || $jurnalMasuk["kode_koreksi", 332]) {
                            $update["harga_total_plus_pajak_saldo"] = $rk["nilai"]

                            Kib::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Rincian_masuk::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 303] || $jurnalMasuk["kode_koreksi", 333]) {
                            $update["harga_total_plus_pajak_saldo"] = $rk["nilai"]

                            Kib::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Rincian_masuk::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 306] || $jurnalMasuk["kode_koreksi", 336]) {
                            $update["kode_108"] = $rk["kode_108"]
                            $update["kode_64"] = $rK["kode_64"]

                            Kib::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Rincian_masuk::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 307] || $jurnalMasuk["kode_koreksi", 337]) {
                            $update["kode_kepemilikan"] = $rk["kode_kepemilikan"]

                            Kib::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                            Rincian_masuk::where('no_register', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }
                    }

                    else{
                        if ($jurnalMasuk["kode_koreksi", 302] || $jurnalMasuk["kode_koreksi", 332]) {
                            $update["harga_total_plus_pajak_saldo"] = $rk["nilai"]

                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 303] || $jurnalMasuk["kode_koreksi", 333]) {
                            $update["harga_total_plus_pajak_saldo"] = $rk["nilai"]

                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 306] || $jurnalMasuk["kode_koreksi", 336]) {
                            $update["kode_108"] = $rk["kode_108"]
                            $update["kode_64"] = $rK["kode_64"]

                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);
                        
                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }

                        elseif($jurnalMasuk["kode_koreksi", 307] || $jurnalMasuk["kode_koreksi", 337]) {
                            $update["kode_kepemilikan"] = $rk["kode_kepemilikan"]

                            Kib::where('id_aset', $rK["id_aset"])->where('kode_jurnal', 102)->update($update);

                            if (Kib_awal::where('id_aset', $rK["id_aset"])->exists()) {
                                Kib_awal::where('id_aset', $rK["id_aset"])->update($update);
                            }
                            eles() {
                                Rincian_masuk::where('no_register', $rK["id_aset"])->update($update);
                            }
                        }
                    }
                }
                
                $rk->delete();
            }

            $jurnal->delete();

            return $this->sendResponse('Jurnal' => $jurnal, 'Jurnal delete successfully.');
        }
        
    }
}

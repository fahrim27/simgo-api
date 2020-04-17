<?php

namespace App\Http\Controllers\API\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Jurnal\Rincian_masuk;
use App\Models\Jurnal\Rincian_keluar;
use App\Models\Jurnal\Rincian_koreksi;
use App\Models\Jurnal\Kib;
use App\Models\Jurnal\Penunjang;
use App\Models\Kamus\Kamus_rekening;
use App\Models\Kamus\Rekening_priorita;
use App\Models\Kamus\Kamus_spk;
use App\Models\Kamus\Kamus_spm;
use App\Models\Kamus\Kamus_masa_manfaat;
use App\Models\Jurnal\Jurnal;
use App\Models\Jurnal\Reklasifikasi;
use App\Http\Resources\Jurnal\Rincian_masukCollection;
use App\Http\Resources\Kamus\Kamus_rekeningCollection;
use App\Http\Resources\Kamus\Kamus_masa_manfaatCollection;
use App\Http\Resources\Kamus\Kamus_spkCollection;
use App\Http\Resources\Kamus\Kamus_spmCollection;
use App\Http\Resources\Jurnal\JurnalCollection;
use App\Http\Resources\Jurnal\ReklasifikasiCollection;
use App\Http\Resources\Jurnal\KibCollection;
use Validator;
use DB;

//penggantian tahun menggunakan db selector dengan implement DB class usage

class Rincian_masukController extends BaseController
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
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::all());
        } else {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::paginate($request->get('per_page')));
        }

        return $rincian_masuks;
    }

    public function getByLokasi(Request $request, $nomor_lokasi)
    {
        $pagination = (int)$request->header('Pagination');

        if($pagination === 0) {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->get());
        } else {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('nomor_lokasi', 'like', '%'. $nomor_lokasi .'%')->paginate($request->get('per_page')));
        }

        return $rincian_masuks;
    }

    public function getByIdKontrak(Request $request, $id_kontrak)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $id = base64_decode($id_kontrak);

        if($pagination === 0) {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('id_kontrak', $id)->get());
        } else {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('id_kontrak', $id)->paginate($request->get('per_page')));
        }

        return $rincian_masuks;
    }

    public function getRealisasiByIdKontrak(Request $request, $id_kontrak)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $id = base64_decode($id_kontrak);

        $pembayaran = Kamus_spm::selectRaw('SUM(nilai_spm_spmu) as total_pembayaran')
                                    ->groupBy('id_kontrak')->where('id_kontrak', '=', $id)->get()->toArray();

        $realisasi = Rincian_masuk::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                    ->groupBy('id_kontrak')->where('id_kontrak', '=', $id)->get()->toArray();

        if(empty($pembayaran)) {
            $total_pembayaran = 0;
        } else {
            $total_pembayaran = doubleval($pembayaran[0]["total_pembayaran"]);
        }

        if(empty($realisasi)) {
            $total_realisasi = 0;
        } else {
            $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
        }

        $data = array();
        $data["total_pembayaran"] = $total_pembayaran;
        $data["total_realisasi"] = $total_realisasi;

        return $data;
    }

    public function getBySpk(Request $request, $spk)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_spk = base64_decode($spk);

        if($pagination === 0) {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('no_bukti_perolehan', '=', $nomor_spk)->get());
        } else {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('no_bukti_perolehan', '=', $nomor_spk)->paginate($request->get('per_page')));
        }

        return $rincian_masuks;
    }

    public function getRealisasiBySpk(Request $request, $spk)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        $nomor_spk = base64_decode($spk);

        $pembayaran = Kamus_spm::selectRaw('SUM(nilai_spm_spmu) as total_pembayaran')
                                    ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', '=', $nomor_spk)->get()->toArray();

        $realisasi = Rincian_masuk::selectRaw('SUM(harga_total_plus_pajak) as total_realisasi')
                                    ->groupBy('no_bukti_perolehan')->where('no_bukti_perolehan', '=', $nomor_spk)->get()->toArray();

        $penunjang = Penunjang::selectRaw('SUM(nilai_penunjang) as total_penunjang')
                                    ->groupBy('no_spk_sp_dokumen')->where('no_spk_sp_dokumen', $nomor_spk)->get()->toArray();

        if(empty($penunjang)) {
            $nilai_penunjang = 0;
        } else {
            $nilai_penunjang = doubleval($penunjang[0]["total_penunjang"]);
        }

        if(empty($pembayaran)) {
            $total_pembayaran = 0;
        } else {
            $total_pembayaran = doubleval($pembayaran[0]["total_pembayaran"]);
        }

        if(empty($realisasi)) {
            $total_realisasi = 0;
        } else {
            $total_realisasi = doubleval($realisasi[0]["total_realisasi"]);
        }

        $total_pembayaran = $total_pembayaran + $nilai_penunjang;
        $total_realisasi = $total_realisasi;

        $data = array();
        $data["total_pembayaran"] = $total_pembayaran;
        $data["total_realisasi"] = $total_realisasi;

        return $data;
    }

    public function getByNoKey(Request $request, $no_key)
    {
        $pagination = (int) $request->header('Pagination');

        if ($pagination === 0) {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('no_key', $no_key)->get());
        } else {
            $rincian_masuks = new Rincian_masukCollection(Rincian_masuk::where('no_key', $no_key)->paginate($request->get('per_page')));
        }

        return $rincian_masuks;
    }

    public function getDataJurnalByNoKey(Request $request, $no_key)
    {
        $jurnal = Jurnal::where('no_key', $no_key)->first();

        if (is_null($jurnal)) {
            return $this->sendError('Rincian masuk not found.');
        }

        $kode_jurnal = $jurnal->kode_jurnal;
        $no_ba_st = $jurnal->no_ba_st;
        $tgl_ba_st = $jurnal->tgl_ba_st;
        $tahun_perolehan = $jurnal->tahun_spj;

        $data["no_ba_st"] = $no_ba_st;
        $data["tgl_ba_st"] = $tgl_ba_st;
        $data["tahun_perolehan"] = $tahun_perolehan;
        $data["jenis_bukti"] = "01 - No. BA Penerimaan";

        if($kode_jurnal == "103") {
            $data["cara_perolehan"] = "02 - Hibah";
        } else if ($kode_jurnal == "104") {
            $data["cara_perolehan"] = "03 - Ruislag";
        } else if ($kode_jurnal == "105") {
            $data["cara_perolehan"] = "04 - Rampasan";
        } else if ($kode_jurnal == "106") {
            $data["cara_perolehan"] = "05 - BOT/BTO/BT";
        } else if ($kode_jurnal == "108") {
            $data["cara_perolehan"] = "06 - Pinjam Pakai";
        } else if ($kode_jurnal == "109") {
            $data["cara_perolehan"] = "07 - Penggantian Kehilangan";
        } else {
            $data["cara_perolehan"] = "99 - Lainnya";
        }

        return $this->sendResponse($data, 'Rincian masuk retrieved successfully.');
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

        if(array_key_exists("kode_108_sub_sub_blud", $input)) {
            if($input["kode_108_sub_sub_blud"] != NULL) {
                $input["kode_108"] = $input["kode_108_sub_sub_blud"];
                $kode = substr($input["kode_108"], 0, 11);
                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                if(count($kode_64) > 1) {
                    $kode = substr($input["kode_108"], 0, 14);
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                    if(count($kode_64) > 1) {
                        $kode = $input["kode_108"];
                        $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                    } else {
                        $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                    }
                } else {
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                }

                $input["kode_64"] = $kode_64->kode_64;
            }
        }

        $kode_108 = substr($input["kode_108"], 0, 11);
        $nomor_lokasi = $input["nomor_lokasi"];
        $tahun_spj = DB::table('tahun_spj')->select('tahun')->first()->tahun;

        $input["tahun_spj"] = $tahun_spj;
        $input["sumber_dana"] = "02";
        $input["kode_kepemilikan"] = "12";
        $input["tahun_pembuatan"] = $tahun_spj;
        $input["tahun_perakitan"] = $tahun_spj;
        $input["tahun_pengadaan"] = $tahun_spj;
        $input["tahun_perolehan"] = $tahun_spj;

        if(!array_key_exists("no_key", $input)) {
            $nomor_spk = $input["no_bukti_perolehan"];
            $kode_jurnal = "101";

            $jurnal = Jurnal::select('no_key','terkunci','nomor_lokasi','tahun_spj','no_ba_penerimaan','kode_jurnal','tgl_spk_sp_dokumen')->where('no_spk_sp_dokumen', 'like', $nomor_spk)->first();

            if(empty($jurnal)) {
                $max_no_ba = Jurnal::select('no_ba_penerimaan')
                ->where('nomor_lokasi', $nomor_lokasi)
                ->where('tahun_spj', $tahun_spj)
                ->where('kode_jurnal', $kode_jurnal)
                ->orderBy('no_key', 'DESC')
                ->first();

                $spk = Kamus_spk::select('no_spk_sp_dokumen','tgl_spk_sp_dokumen','deskripsi_spk_dokumen', 'id_kegiatan', 'nilai_spk', 'rekanan', 'alamat_rekanan')->where('no_spk_sp_dokumen', 'like', $nomor_spk)->first();

                if(empty($max_no_ba)) {
                    $nomor = 1;
                    $no_ba_penerimaan = "0001";
                } else {
                    $max_no_ba = $max_no_ba->no_ba_penerimaan;
                    $no_ba_penerimaan = intval($max_no_ba);
                    ++$no_ba_penerimaan;

                    $nomor = $no_ba_penerimaan;
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

                $input["no_ba_penerimaan"] = $no_ba_penerimaan;
                $input["no_key"] = $nomor_lokasi . "." . $kode_jurnal .".". $no_ba_penerimaan . "." . $tahun_spj;

                $data_jurnal = array();
                $data_jurnal["nomor_lokasi"] = $input["nomor_lokasi"];
                $data_jurnal["kode_jurnal"] = $kode_jurnal;
                $data_jurnal["tahun_spj"] = $tahun_spj;
                $data_jurnal["cara_perolehan"] = "Pembelian";
                $data_jurnal["no_key"] = $input["no_key"];
                $data_jurnal["terkunci"] = "1";
                $data_jurnal["no_spk_sp_dokumen"] = $spk->no_spk_sp_dokumen;
                $data_jurnal["tgl_spk_sp_dokumen"] = $spk->tgl_spk_sp_dokumen;
                $data_jurnal["uraian_spk"] = $spk->deskripsi_spk_dokumen;
                $data_jurnal["kode_kegiatan"] = $spk->id_kegiatan;
                $data_jurnal["nilai_spk"] = $spk->nilai_spk;
                $data_jurnal["tahun_spm"] = $spk->tahun_spj;
                $data_jurnal["no_ba_penerimaan"] = $no_ba_penerimaan;
                $data_jurnal["tgl_ba_penerimaan"] = $spk->tgl_spk_sp_dokumen;
                $data_jurnal["instansi_yg_menyerahkan"] = $spk->rekanan;
                $data_jurnal["alamat"] = $spk->alamat_rekanan;
                $data_jurnal["tgl_bap"] = $spk->tgl_spk_sp_dokumen;
                $data_jurnal["tgl_ba_st"] = $spk->tgl_spk_sp_dokumen;
                $data_jurnal["tgl_dokumen"] = $spk->tgl_spk_sp_dokumen;
                $data_jurnal["nomor"] = $spk->nomor;

                $jurnal = Jurnal::create($data_jurnal);

                $input["tgl_perolehan"] = $spk->tgl_spk_sp_dokumen;
                $input["id_transaksi"] = $kode_jurnal;
                $input["kode_jurnal"] = $kode_jurnal;
                $input["terkunci"] = "1";
            } else {
                $input["no_key"] = $jurnal->no_key;
                $input["nomor_lokasi"] = $jurnal->nomor_lokasi;
                $input["terkunci"] = $jurnal->terkunci;
                $input["tahun_spj"] = $jurnal->tahun_spj;
                $input["no_ba_penerimaan"] = $jurnal->no_ba_penerimaan;
                $input["id_transaksi"] = $jurnal->kode_jurnal;
                $input["tgl_perolehan"] = $jurnal->tgl_spk_sp_dokumen;
                $input["kode_jurnal"] = $jurnal->kode_jurnal;
            }
        }

        if(array_key_exists("ekstrakomptabel", $input)) {
            if($input["ekstrakomptabel"] == "1") {
                $temp1 = substr($input["nomor_lokasi"], 0, 3);
                $temp2 = substr($input["nomor_lokasi"], 3, 2);
                $temp3 = substr($input["nomor_lokasi"], 5);

                $nomor_lokasi = $temp1 . "02" . $temp3;
                $input["nomor_lokasi"] = $nomor_lokasi;
            }
        }

        $temp = $input["nomor_lokasi"].".".$tahun_spj.".".$input["kode_jurnal"].".".$input["kode_108"];
        $max_id_aset = Rincian_masuk::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first();

        if(is_null($max_id_aset)) {
            $id_aset_index = "00001";
        } else {
            $max_id_aset = $max_id_aset->id_aset;

            if(empty($max_id_aset)) {
                $id_aset_index = "00001";
            } else {
                $id_aset_index = str_replace($temp, '', $max_id_aset);
                $id_aset_index = str_replace('.', '', $id_aset_index);

                $id_aset_index = intval($id_aset_index);
                ++$id_aset_index;

                $id_aset_index = strval($id_aset_index);
                $s = strlen($id_aset_index);
                if($s == 1) {
                    $id_aset_index = "0000".$id_aset_index;
                } else if($s == 2) {
                    $id_aset_index = "000".$id_aset_index;
                } else if($s == 3) {
                    $id_aset_index = "00".$id_aset_index;
                } else if($s == 4) {
                    $id_aset_index = "0".$id_aset_index;
                }
            }
        }

        $id_aset = $temp.".".$id_aset_index;

        $input["id_aset"] = $id_aset;
        $input["no_register"] = $id_aset;
        $input["harga_total_plus_pajak_saldo"] = $input["harga_total_plus_pajak"];
        $input["saldo_barang"] = $input["jumlah_barang"];
        $input["saldo_gudang"] = $input["jumlah_barang"];
        $input["baik"] = $input["jumlah_barang"];
        $input["sendiri"] = $input["jumlah_barang"];


        if(array_key_exists("aset_rehab", $input)) {
            if($input["aset_rehab"] == "1") {
                $input["pos_entri"] = "rehab";
                $input["aset_rehab"] = 1;
            }
        }

        $masa_manfaat = Kamus_rekening::select('masa_manfaat')->where('kode_108', 'like', $kode_108.'%')->first()->masa_manfaat;

        if(!is_null($masa_manfaat)) {
            $nilai_pengadaan = (double) $input["harga_total_plus_pajak"];
            $penyusutan_per_tahun = (double) $nilai_pengadaan/$masa_manfaat;
            $akumulasi_penyusutan = $penyusutan_per_tahun;
            $nilai_buku = $nilai_pengadaan - $akumulasi_penyusutan;
            $prosen_susut = 1/$masa_manfaat*100;

            $input["waktu_susut"] = $masa_manfaat;
            $input["prosen_susut"] = $prosen_susut;
            $input["ak_penyusutan"] = $akumulasi_penyusutan;
            $input["tahun_buku"] = $tahun_spj;
            $input["nilai_buku"] = $nilai_buku;
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'no_ba_penerimaan' => 'required',
            'nomor_lokasi' => 'required',
            'bidang_barang' => 'required',
            'terkunci' => 'required',
            'tahun_spj' => 'required',
            'kode_108' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if(array_key_exists("ekstrakomptabel", $input)) {
            if($input["ekstrakomptabel"] == "1") {
                $input["pos_entri"] = "EKSTRAKOMPTABEL";
            }
        }

        if(array_key_exists("pakai_habis", $input)) {
            if($input["pakai_habis"] == "1") {
                $input["pos_entri"] = "PAKAI_HABIS";
            }
        }

        if (array_key_exists("reklas",$input))
        {
            if(substr($input["nomor_lokasi"], 16, 5) == "00002") {
                if(($input["reklas"]) == 1) {
                    $input["kode_asal"] = $input["asal"];
                    $input["kode_108"] = $input["tujuan"];
                }
            } else {
                if(($input["reklas"]) == 1) {
                    $reklas = array();
                    $reklas["nomor_lokasi"] = $input["nomor_lokasi"];
                    $reklas["id_aset"] = $input["id_aset"];
                    $reklas["tahun_reklas"] = $input["tahun_spj"];
                    $reklas["jenis_reklas"] = "kode";
                    $reklas["asal"] = $input["asal"];
                    $reklas["tujuan"] = $input["tujuan"];

                    $reklasifikasi = Reklasifikasi::create($reklas);

                    $input["kode_asal"] = $input["asal"];
                    $input["kode_108"] = $input["tujuan"];

                    $kode = substr($input["kode_108"], 0, 11);
                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                    if(count($kode_64) > 1) {
                        $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                        if(count($prioritas) > 1) {
                            $kode = substr($input["kode_108"], 0, 14);
                            $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                            if(count($prioritas) > 1) {
                                $kode = $input["kode_108"];
                                $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();

                                $kode_64 = $prioritas;
                            } else {
                                $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();

                                $kode_64 = $prioritas;
                            }
                        } else {
                            $prioritas = Rekening_priorita::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();

                            if(is_null($prioritas)) {
                                $kode = substr($input["kode_108"], 0, 14);
                                $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->get()->toArray();

                                if(count($kode_64) > 1) {
                                    $kode = $input["kode_108"];
                                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                                } else {
                                    $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                                }
                            }
                        }
                    } else {
                        $kode_64 = Kamus_rekening::select('kode_64')->where('kode_108', 'like', $kode . '%')->first();
                    }

                    $input["kode_64"] = $kode_64->kode_64;

                    $reklasRincian = array();
                    $reklasRincian['nomor_lokasi'] = $input['nomor_lokasi'];
                    $reklasRincian['id_aset'] = $input['id_aset'];
                    $reklasRincian['kode_koreksi'] = "366";
                    $reklasRincian['kode_sb_koreksi'] = "5";
                    $reklasRincian['no_key'] = $input['no_key'];
                    $reklasRincian['bidang_barang'] = $input['bidang_barang'];
                    $reklasRincian['no_ba_penerimaan'] = $input['no_ba_penerimaan'];
                    $reklasRincian['no_register'] = $input['no_register'];
                    $reklasRincian['nama_barang'] = $input['nama_barang'];
                    $reklasRincian['merk_alamat'] = $input['merk_alamat'];
                    $reklasRincian['kode_108'] = $input['kode_asal'];
                    $reklasRincian['kode_108_baru'] = $input['kode_108'];
                    $reklasRincian['kode_64'] = $kode_64;
                    $reklasRincian['kode_64_baru'] = $input['kode_64'];
                    $reklasRincian['kode_kepemilikan'] = "12";
                    $reklasRincian['jumlah_barang'] = $input['saldo_barang'];
                    $reklasRincian['nilai'] = $input['harga_total_plus_pajak_saldo'];
                    if (isset($input['keterangan'])) {
                        $reklasRincian['keterangan'] = $input['keterangan'];
                    }
                    else{
                        $reklasRincian['keterangan'] = " ";
                    }
                    $reklasRincian['tahun_koreksi'] = $input['tahun_spj'];

                    $rincian_koreksi = Rincian_koreksi::create($reklasRincian);
                }
            }
        }

        $rincian_masuk = Rincian_masuk::create($input);
        $kib = Kib::create($input);

        return $this->sendResponse($rincian_masuk->toArray(), 'Rincian masuk created successfully.');
    }

    public function save(Request $request, $kode_jurnal)
    {
        $input = $request->all();
        $kode_108 = substr($input["kode_108"], 0, 11);
        $nomor_lokasi = $input["nomor_lokasi"];
        $no_key = $input["no_key"];
        $tahun_spj = DB::table('tahun_spj')->select('tahun')->first()->tahun;

        $jurnal = Jurnal::select('no_key','terkunci','nomor_lokasi','tahun_spj','no_ba_penerimaan','kode_jurnal','tgl_spk_sp_dokumen')->where('no_key', $no_key)->first();

        $input["terkunci"] = $jurnal->terkunci;
        $input["tahun_spj"] = $jurnal->tahun_spj;
        $input["no_ba_penerimaan"] = $jurnal->no_ba_penerimaan;
        $input["id_transaksi"] = $jurnal->kode_jurnal;
        $input["kode_jurnal"] = $jurnal->kode_jurnal;
        $input["pinjam_pakai"] = 0;

        $temp = $input["nomor_lokasi"].".".$tahun_spj.".".$input["kode_jurnal"].".".$input["kode_108"];
        $max_id_aset = Rincian_masuk::select('id_aset')->where('id_aset', 'like', '%'.$temp.'%')->orderBy('id_aset', 'DESC')->first();

        if(is_null($max_id_aset)) {
            $id_aset_index = "00001";
        } else {
            $max_id_aset = $max_id_aset->id_aset;

            if(empty($max_id_aset)) {
                $id_aset_index = "00001";
            } else {
                $id_aset_index = str_replace($temp, '', $max_id_aset);
                $id_aset_index = str_replace('.', '', $id_aset_index);

                $id_aset_index = intval($id_aset_index);
                ++$id_aset_index;

                $id_aset_index = strval($id_aset_index);
                $s = strlen($id_aset_index);
                if($s == 1) {
                    $id_aset_index = "0000".$id_aset_index;
                } else if($s == 2) {
                    $id_aset_index = "000".$id_aset_index;
                } else if($s == 3) {
                    $id_aset_index = "00".$id_aset_index;
                } else if($s == 4) {
                    $id_aset_index = "0".$id_aset_index;
                }
            }
        }

        $id_aset = $temp.".".$id_aset_index;

        $input["id_aset"] = $id_aset;
        $input["no_register"] = $id_aset;
        $input["harga_total_plus_pajak_saldo"] = $input["harga_total_plus_pajak"];
        $input["saldo_barang"] = $input["jumlah_barang"];
        $input["saldo_gudang"] = $input["jumlah_barang"];

        if(array_key_exists("kondisi", $input)) {
            if($input["kondisi"] == "baik") {
            $input["baik"] = $input["jumlah_barang"];
            } else if ($input["kondisi"] == "kb") {
                $input["kb"] = $input["jumlah_barang"];
            } else if ($input["kondisi"] == "rb") {
                $input["rb"] = $input["jumlah_barang"];
            }
        }

        if(array_key_exists("penguasaan", $input)) {
            if($input["penguasaan"] == "sendiri") {
                $input["sendiri"] = $input["jumlah_barang"];
            } else if ($input["penguasaan"] == "sengketa") {
                $input["sengketa"] = $input["jumlah_barang"];
            } else if ($input["penguasaan"] == "pihak_3") {
                $input["pihak_3"] = $input["jumlah_barang"];
            }
        }

        $masa_manfaat = Kamus_rekening::select('masa_manfaat')->where('kode_108', 'like', $kode_108.'%')->first()->masa_manfaat;

        if(!is_null($masa_manfaat)) {
            $nilai_pengadaan = (double) $input["harga_total_plus_pajak"];
            $penyusutan_per_tahun = (double) $nilai_pengadaan/$masa_manfaat;
            $akumulasi_penyusutan = $penyusutan_per_tahun;
            $nilai_buku = $nilai_pengadaan - $akumulasi_penyusutan;
            $prosen_susut = 1/$masa_manfaat*100;

            $input["waktu_susut"] = $masa_manfaat;
            $input["prosen_susut"] = $prosen_susut;
            $input["ak_penyusutan"] = $akumulasi_penyusutan;
            $input["tahun_buku"] = $tahun_spj;
            $input["nilai_buku"] = $nilai_buku;
        }

        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required',
            'no_ba_penerimaan' => 'required',
            'nomor_lokasi' => 'required',
            'id_transaksi' => 'required',
            'pinjam_pakai' => 'required',
            'pajak' => 'required',
            'bidang_barang' => 'required',
            'terkunci' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $rincian_masuk = Rincian_masuk::create($input);
        $kib = Kib::create($input);

        return $this->sendResponse($rincian_masuk->toArray(), 'Rincian masuk created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id_aset)
    {
        $rincian_masuk = Rincian_masuk::find($id_aset);

        if (is_null($rincian_masuk)) {
            return $this->sendError('Rincian masuk not found.');
        }

        return $this->sendResponse($rincian_masuk->toArray(), 'Rincian masuk retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rincian_masuk $rincian_masuk)
    {
        $input = $request->all();

        $id_aset = $rincian_masuk->id_aset;
        $data = Kib::select("pajak", "id_transaksi", "nomor_lokasi", "bidang_barang", "tahun_spj")->where('id_aset', $id_aset)->first();

        $input["terkunci"] = "1";
        $input["pajak"] = $data->pajak;
        $input["pinjam_pakai"] = $data->pinjam_pakai;
        $input["id_transaksi"] = $data->id_transaksi;
        $input["nomor_lokasi"] = $data->nomor_lokasi;
        $input["bidang_barang"] = $data->bidang_barang;
        $input["tahun_spj"] = $data->tahun_spj;
        $input["id_aset"] = $id_aset;

        if(array_key_exists("harga_total_plus_pajak", $input)) {
            $input["harga_total_plus_pajak_saldo"] = $input["harga_total_plus_pajak"];
        }
        $validator = Validator::make($input, [
            'no_key' => 'required',
            'id_aset' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $kib = $input;
        if(array_key_exists("fetched_kode_64", $input)) {
            $kib["kode_64"] = $kib["fetched_kode_64"];
        }

        if(array_key_exists("fetched_kode_108", $input)) {
            $kib["kode_108"] = $kib["fetched_kode_108"];
        }

        if(array_key_exists("kondisi", $kib)) {
            if($kib["kondisi"] == "baik") {
            $kib["baik"] = $kib["jumlah_barang"];
            } else if ($kib["kondisi"] == "kb") {
                $kib["kb"] = $kib["jumlah_barang"];
            } else if ($kib["kondisi"] == "rb") {
                $kib["rb"] = $kib["jumlah_barang"];
            }
        }

        if(array_key_exists("penguasaan", $kib)) {
            if($kib["penguasaan"] == "sendiri") {
                $kib["sendiri"] = $kib["jumlah_barang"];
            } else if ($kib["penguasaan"] == "sengketa") {
                $kib["sengketa"] = $kib["jumlah_barang"];
            } else if ($kib["penguasaan"] == "pihak_3") {
                $kib["pihak_3"] = $kib["jumlah_barang"];
            }
        }

        unset($kib["filter_kode_sub_108"]);
        unset($kib["filter_kode_sub_sub_108"]);
        unset($kib["fetched_kode_64"]);
        unset($kib["fetched_kode_108"]);
        unset($kib["filter_kode_108_reklas"]);
        unset($kib["filter_kode_108_sub_reklas"]);
        unset($kib["filter_kode_108_sub_sub_reklas"]);
        unset($kib["kode_108_baru"]);
        unset($kib["filter_kode_108"]);
        unset($kib["kode_sub_108"]);
        unset($kib["kode_sub_sub_108"]);
        unset($kib["uraian_rek_belanja"]);
        unset($kib["kondisi"]);
        unset($kib["penguasaan"]);

        $kib["kode_64"] = null;

        if($kib["kode_64"] == NULL) {
            $kode = Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $kib["kode_108"])->first();

            if(is_null($kode)) {
                $sub_rekening = substr($kib["kode_108"], 0, 14);
                $kode = Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $sub_rekening)->first();

                if(is_null($kode)) {
                    $rekening = substr($sub_rekening, 0, 11);
                    $kode = new Kamus_rekeningCollection(Kamus_rekening::select('kode_64', 'uraian_64')->where('kode_108', $rekening)->first());
                    $kode_64 = $kode->kode_64;
                } else {
                    $kode_64 = $kode->kode_64;
                }
            } else {
                $kode_64 = $kode->kode_64;
            }

            $kib["kode_64"] = $kode_64;
        }

        $validator2 = Validator::make($kib, [
            'kode_108' => 'required'
        ]);

        if($validator2->fails()){
            return $this->sendError('Validation Error.', $validator2->errors());
        }

        if (Rincian_keluar::select('id_aset')->where('id_aset', 'LIKE', '%'.$id_aset.'%')->exists()) {
            $check = Rincian_keluar::select('id_aset')->where('id_aset', 'LIKE', '%'.$id_aset.'%')->first()->toArray();
            if ($check["status"] = 1) {
                $rincian_masuk->update($kib);
                Kib::where('no_register',$input["id_aset"])->where('nomor_lokasi', $)->update($kib);
                Rincian_keluar::where('id_aset',$input["id_aset"])->update($kib);
            }
            else{
                $rincian_masuk->where('no_register', 'LIKE', '%'.$id_aset.'%')->update($kib);
                Kib::where('no_register',$input["id_aset"])->update($kib);
                Rincian_keluar::where('id_aset',$input["id_aset"])->update($kib);
            }
        }
        else{
            $rincian_masuk->update($kib);
            Kib::where('id_aset',$input["id_aset"])->update($kib);
        }


        return $this->sendResponse($rincian_masuk->toArray(), 'Rincian masuk updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rincian_masuk $rincian_masuk)
    {
        $rincian_masuk->toArray();

        $no_spk = Kib::select('no_bukti_perolehan')->where('id_aset', $rincian_masuk["id_aset"])->first()->no_bukti_perolehan;

        if (Penunjang::where('no_spk_sp_dokumen', $no_spk)->exists()) {
            Penunjang::where('no_spk_sp_dokumen', $no_spk)->delete();
        }

        if(Rincian_koreksi::where('id_aset', $rincian_masuk["id_aset"])->exists()) {
            Rincian_koreksi::where('id_aset', $rincian_masuk["id_aset"])->delete();
        }

        if(Rincian_keluar::where('id_aset', $rincian_masuk["id_aset"])->exists()) {
           Rincian_keluar::where('id_aset', $rincian_masuk["id_aset"])->where('kode_jurnal', 102)->delete();
           $rincian_masuk->where('no_register', $rincian_masuk["id_aset"])->where('kode_jurnal', 102)->delete();
        }

        if(Rehab::where('rehab_id', $rincian_masuk["id_aset"])->exists()) {
            Rehab::where('rehab_id', $rincian_masuk["id_aset"])->delete();
        }

        $no_key = Kib::select('no_key')->where('id_aset', $rincian_masuk["id_aset"])->first()->no_key;

        $jumlah_data = Rincian_masuk::selectRaw('count(*) as jumlah')->groupBy('no_key')->where('no_key', $no_key)->first()->jumlah;

        if(!empty($jumlah_data)) {
            if($jumlah_data == 1) { 
                Jurnal::where('no_key', $no_key)->delete();
            }
        }

        Kib::where('id_aset',$rincian_masuk["id_aset"])->delete();
        $rincian_masuk->delete();

        return $this->sendResponse($rincian_masuk->toArray(), 'Rincian masuk deleted successfully.');
    }
}

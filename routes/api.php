<?php

use Illuminate\Http\Request;
Use App\Secgroup;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::post('register', 'API\RegisterController@register');

// Route::post('oauth/token', 'API\AuthController@auth');

// Route::get('access', 'API\Secman\SecgroupController@index');

// Route::middleware('auth:api')->group( function () {
// 	Route::resource('secgroups', 'API\SecgroupController');
// });

//Laporan
Route::middleware('cors')->group(function(){
	Route::post( 'register', 'API\RegisterController@register');
	Route::post( 'oauth/token', 'API\AuthController@auth');
	Route::get( 'access', 'API\Secman\SecgroupController@index');

	Route::get('laporan/1/p/{kode_jurnal}/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\Penambahan\Laporan_penambahanController@exportLaporanPenerimaan');

  Route::get('laporan/kibs/exp/{bidang_barang}/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanKibUrutTahun');
	Route::get('laporan/susut/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@getSusutKIB');

	Route::get('laporan/export/rekap/rinci108/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanRekapRincian108');
	Route::get('laporan/export/rekap/sub108/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanRekapSubRincian108');
	Route::get('laporan/export/rekap/108/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanRekap108');
	Route::get('laporan/export/rekap/64/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanRekap64');

	Route::get('laporan/export/susut/108/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusut108');
	Route::get('laporan/export/susut/64/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusut64');
	Route::get('laporan/export/susut/ak108/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusutAk108');
	Route::get('laporan/export/susut/reklas/keluar/64/{nomor_lokasi}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusutReklasKeluar64');
	Route::get('laporan/export/susut/reklas/keluar/108/{nomor_lokasi}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusutReklasKeluar108');
	Route::get('laporan/export/susut/reklas/masuk/64/{nomor_lokasi}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusutReklasMasuk64');
	Route::get('laporan/export/susut/reklas/masuk/108/{nomor_lokasi}/{jenis_aset}', 'API\Laporan\Penyusutan\Laporan_penyusutanController@exportSusutReklasMasuk108');

	Route::get('laporan/export/mutasi/1/108/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportMasuk108');
	Route::get('laporan/export/mutasi/2/108/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportKeluar108');
	Route::get('laporan/export/mutasi/1/64/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportMasuk64');
    Route::get('laporan/export/mutasi/2/64/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportKeluar64');

	Route::get('laporan/export/LRA/realisasiLRA', 'API\Laporan\LRA\Laporan_realisasiController@realisasiLRA');
	Route::get('laporan/export/LRA/realisasiLRA/{nomor_lokasi}', 'API\Laporan\LRA\Laporan_realisasiController@realisasiLRAOPD');

    ///////////laporan excel
	// laporan mutasi masuk & keluar barang
	Route::get('laporan/export/mutasi/masuk/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportMasukBarang');
	Route::get('laporan/export/mutasi/keluar/{nomor_lokasi}', 'API\Laporan\Mutasi\Laporan_MutasiController@exportKeluarBarang');
	// end laporan mutasi masuk & keluar barang
	// route neraca
	Route::get('laporan/neraca/{bidang_barang}/{kode_kepemilikan}/{kode_108}', 'API\Laporan\Neraca\Laporan_NeracaController@exportLaporanNeraca');
	// end route neraca
	// route kib saldo awal
    Route::get('laporan/kibs/exp/saldo-awal/{bidang_barang}/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanKibAwal');
    Route::get('laporan/kibs/exp/inv/saldo-awal/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@exportLaporanKibAwalInv');
    // end kib saldo awal


    ////////////////laporan pdf
    // pdf laporan neraca
    Route::get('laporan/export/pdf/neraca/{bidang_barang}/{kode_kepemilikan}/{kode_108}', 'API\ExportPdf\Neraca\Neraca_pdf@exportLaporanNeraca');
    //
    // export pdf kib saldo awal & urut tahun & saldo inventaris
    Route::get('laporan/export/pdf/kibs/{bidang_barang}/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportLaporanKibUrutTahun');
    Route::get('laporan/export/pdf/kibs/saldo-awal/{bidang_barang}/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportLaporanKibAwal');
    Route::get('laporan/export/pdf/kibs/inv/saldo-awal/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKIB@exportLaporanKibAwalInv');
    //
    //pdf laporan rekap kib / saldo current
	Route::get('laporan/export/pdf/rekap/rinci108/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportLaporanRekapRincian108');
	Route::get('laporan/export/pdf/rekap/sub108/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportLaporanRekapSubRincian108');
	Route::get('laporan/export/pdf/rekap/64/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportKib64');
	Route::get('laporan/export/pdf/rekap/108/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\KIB\rekapKib@exportKib108');
    //
    //pdf peyusutan
	Route::get('laporan/export/pdf/susut/108/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusut108');
	Route::get('laporan/export/pdf/susut/64/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusut64');
    Route::get('laporan/export/pdf/susut/ak/108/{nomor_lokasi}/{bidang_barang}/{kode_kepemilikan}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusutAk108');
    Route::get('laporan/export/pdf/susut/reklas/keluar/64/{nomor_lokasi}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusutReklasKeluar64');
	Route::get('laporan/export/pdf/susut/reklas/keluar/108/{nomor_lokasi}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusutReklasKeluar108');
	Route::get('laporan/export/pdf/susut/reklas/masuk/64/{nomor_lokasi}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusutReklasMasuk64');
    Route::get('laporan/export/pdf/susut/reklas/masuk/108/{nomor_lokasi}/{jenis_aset}', 'API\ExportPdf\Penyusutan\Penyusutan_pdf@exportSusutReklasMasuk108');
    //
    // pdf laporan realisasi
    Route::get('laporan/export/pdf/LRA/realisasiLRA', 'API\ExportPdf\LRA\realisasiLRA_pdf@realisasiLRA');
	Route::get('laporan/export/pdf/LRA/realisasiLRA/{nomor_lokasi}', 'API\ExportPdf\LRA\realisasiLRA_pdf@realisasiLRAOPD');
    //
    // pdf laporan mutasi masuk keluar 64 & 108
    Route::get('laporan/export/pdf/mutasi/1/108/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportMasuk108');
	Route::get('laporan/export/pdf/mutasi/2/108/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportKeluar108');
	Route::get('laporan/export/pdf/mutasi/1/64/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportMasuk64');
    Route::get('laporan/export/pdf/mutasi/2/64/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportKeluar64');
    Route::get('laporan/export/pdf/mutasi/masuk/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportMasukBarang');
    Route::get('laporan/export/pdf/mutasi/keluar/{nomor_lokasi}', 'API\ExportPdf\Mutasi\Mutasi_pdf@exportKeluarBarang');
    //
    // pdf laporan penambahan
    Route::get('laporan/export/pdf/penerimaan/{kode_jurnal}/{nomor_lokasi}/{kode_kepemilikan}', 'API\ExportPdf\Penambahan\Penambahan_pdf@exportLaporanPenerimaan');
    //
});

Route::group(['middleware'=>['cors','auth:api']], function () {
	//untuk laporan
	Route::get('laporan/1/v/{kode_jurnal}/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\Penambahan\Laporan_penambahanController@laporanPenerimaan');

	Route::get('kamus_rekenings/{rekening}', 'API\Kamus\Kamus_rekeningController@getByRekening');
	Route::get('kamus_rekenings/kode64/{sub_sub}', 'API\Kamus\Kamus_rekeningController@getKode64BySub');

	Route::get('kamus_lokasis/spk', 'API\Kamus\Kamus_lokasiController@getSpkLocation');

	Route::get('kamus_rekenings/bidang/{kode_bidang}', 'API\Kamus\Kamus_rekeningController@getListByBidang');
	Route::get('sub_rincian_108s/list/{rincian}', 'API\Kamus\Sub_rincian_108Controller@getListBy');
	Route::get('sub_sub_rincian_108s/list/{sub_rincian}', 'API\Kamus\Sub_sub_rincian_108Controller@getListBy');
	Route::get('sub_sub_rincian_108s/bidang/{kode_bidang}', 'API\Kamus\Sub_sub_rincian_108Controller@getListByBidang');

	Route::get('penyusutans/lokasi/{lokasi}/{bidang}', 'API\Laporan\Penyusutan\Laporan_penyusutan_pengadaanController@getPenyusutan');

	Route::get('kamus_pegawais/nip/{nip}', 'API\Kamus\Kamus_pegawaiController@getData');
	Route::put('kamus_pegawais/nip/{nip}', 'API\Kamus\Kamus_pegawaiController@change');

	//untuk KIB
	Route::get('kibs/export/', 'API\Jurnal\KibController@export');
	Route::get('kibs/show/{bidang_barang}/{nomor_lokasi}/{kode_kepemilikan}', 'API\Laporan\KIB\Laporan_KIBController@laporanKibUrutTahun');
	Route::get('kibs/{kode_jurnal}/{nomor_lokasi}', 'API\Jurnal\KibController@getListByJurnalLokasi');

	Route::get('penunjangs/jurnal/{lokasi}', 'API\Jurnal\JurnalController@getJurnalPenunjang');
	Route::get('penunjangs/spm/{no_spk}', 'API\Jurnal\PenunjangController@getSpmBySpk');
	Route::get('penunjangs/spk/{nomor_lokasi}', 'API\Jurnal\PenunjangController@getSpkByLokasi');
	Route::delete('penunjangs/spk/{no_spk}', 'API\Jurnal\PenunjangController@deleteBySpk');

	//untuk Rincian Keluar
	Route::get('rincian_keluars/data/{id_aset}', 'API\Jurnal\Rincian_keluarController@getDetailAsetKeluar');
	Route::get('rincian_keluars/aset/{nomor_lokasi}', 'API\Jurnal\Rincian_keluarController@getAvailableAset');
	Route::get('rincian_keluars/key/{no_key}', 'API\Jurnal\Rincian_keluarController@getByNoKey');
	Route::post('rincian_keluars/{kode_jurnal}', 'API\Jurnal\Rincian_keluarController@save');

	//untuk Jurnal
	Route::get('jurnals/{kodeJurnal}/{lokasi}', 'API\Jurnal\JurnalController@getJurnalByLokasi');
	Route::get('jurnals/{status}/{kodeJurnal}/{lokasi}', 'API\Jurnal\JurnalController@getJurnalByStatusLokasi');
	Route::post('jurnals/{kodeJurnal}', 'API\Jurnal\JurnalController@save');
	Route::post('jurnals/enter/{kodeJurnal}', 'API\Jurnal\JurnalController@enter');

	//untuk kamus pegawai
	Route::get('kamus_pegawais/lokasi/{lokasi}', 'API\Kamus\Kamus_pegawaiController@getByLokasi');

	//untuk spk
	Route::get('kamus_spks/lokasi/{lokasi}', 'API\Kamus\Kamus_spkController@getNotJurnalledSpkByLokasi');
	Route::get('kamus_spks/done/lokasi/{lokasi}', 'API\Kamus\Kamus_spkController@getJurnalledSpkByLokasi');

	Route::get('kamus_spms/spk/{spk}', 'API\Kamus\Kamus_spmController@getBySpk');
	Route::get('kamus_spms/kontrak/{id_kontrak}', 'API\Kamus\Kamus_spmController@getByIdKontrak');

	//untuk rincian masuk
	Route::get('rincian_masuks/lokasi/{lokasi}', 'API\Jurnal\Rincian_masukController@getByLokasi');
	Route::get('rincian_masuks/kontrak/{id_kontrak}', 'API\Jurnal\Rincian_masukController@getByIdKontrak');
	Route::get('rincian_masuks/realisasi/kontrak/{id_kontrak}', 'API\Jurnal\Rincian_masukController@getRealisasiByIdKontrak');
	Route::get('rincian_masuks/spk/{spk}', 'API\Jurnal\Rincian_masukController@getBySpk');
	Route::get('rincian_masuks/realisasi/{spk}', 'API\Jurnal\Rincian_masukController@getRealisasiBySpk');
	Route::get('rincian_masuks/key/{no_key}', 'API\Jurnal\Rincian_masukController@getByNoKey');
	Route::get('rincian_masuks/data/{no_key}', 'API\Jurnal\Rincian_masukController@getDataJurnalByNoKey');
	Route::post('rincian_masuks/{kode_jurnal}', 'API\Jurnal\Rincian_masukController@save');

	//untuk rincian koreksi
	Route::get('rincian_koreksis/lokasi/{lokasi}', 'API\Jurnal\Rincian_koreksiController@getByLokasi');
	Route::get('rincian_koreksis/key/{no_key}', 'API\Jurnal\Rincian_koreksiController@getByNoKey');
	Route::get('rincian_koreksis/data/{id_aset}', 'API\Jurnal\Rincian_koreksiController@getDetailAsetKoreksi');
	Route::get('rincian_koreksis/aset/{nomor_lokasi}', 'API\Jurnal\Rincian_koreksiController@getAvailableAset');
	Route::get('rincian_koreksis/key/{no_key}', 'API\Jurnal\Rincian_koreksiController@getByNoKey');
	Route::post('rincian_koreksis/{kode_jurnal}', 'API\Jurnal\Rincian_koreksiController@save');

	//untuk rincian koreksi berjalan
	Route::get('rincian_koreksis/aset/current/{nomor_lokasi}', 'API\Jurnal\Rincian_koreksiController@getAvailableAsetBerjalan');

	//untuk rehab
	Route::get('rehabs/lists/{id_aset}', 'API\Jurnal\RehabController@getListRehabByInduk');
	Route::get('rehabs/rehab/free/{lokasi}', 'API\Jurnal\RehabController@getFreeAsetRehab');
	Route::get('rehabs/rehab/done/{lokasi}', 'API\Jurnal\RehabController@getSavedAsetRehab');
	Route::get('rehabs/induk/free/{lokasi}', 'API\Jurnal\RehabController@getFreeAsetInduk');
	Route::get('rehabs/induk/done/{lokasi}', 'API\Jurnal\RehabController@getSavedAsetInduk');
	Route::get('rehabs/lokasi/{lokasi}', 'API\Jurnal\RehabController@getByLokasi');

	Route::get('atribut/{nomor_lokasi}/{bidang_barang}', 'API\Jurnal\Ubah_atributController@index');
	Route::get('atribut/aset/{bidang_barang}/{id_aset}', 'API\Jurnal\Ubah_atributController@getDetailAset');
	Route::post('atribut/{id_aset}', 'API\Jurnal\Ubah_atributController@save');

	Route::get('roles/user/{user}', 'API\Role\RoleController@getUserRole');
	Route::get('sinkrons/spk', 'API\Sinkron\SinkronController@importSpk');
	Route::get('sinkrons/spm', 'API\Sinkron\SinkronController@importSpm');
	Route::get('sinkrons/date', 'API\Sinkron\SinkronController@getLatestDate');

	Route::get('migrates/108', 'API\Sinkron\MigrasiController@doMigrate');
	Route::get('migrates/rehab', 'API\Sinkron\Migrasi_rehabController@doMigrate');
	Route::get('migrates/rehab/generateID', 'API\Sinkron\Migrasi_rehabController@generateID');
	Route::get('migrates/generateID', 'API\Sinkron\MigrasiController@generateID');

	Route::get('migrates/pendidikan', 'API\Sinkron\Migrasi_bosController@generateID');
	Route::get('migrates/pendidikan/bansos', 'API\Sinkron\Migrasi_bosController@generateBansosID');
	Route::get('migrates/dishub', 'API\Sinkron\Migrasi_dishubController@generateID');
	Route::get('migrates/purehab', 'API\Sinkron\Migrasi_rehabController@purehab');
	Route::get('migrates/diknasrehab', 'API\Sinkron\Migrasi_rehabController@diknasrehab');
	Route::get('migrates/aslala', 'API\Sinkron\Migrasi_reklasController@aslala');
	Route::get('migrates/syncreklas', 'API\Sinkron\Migrasi_reklasController@sync');

	Route::resource('secgroups', 'API\Secman\SecgroupController');
	Route::resource('secusergroups', 'API\Secman\SecusergroupController');
	Route::resource('secmenus', 'API\Secman\SecmenuController');
	Route::resource('secmenugroups', 'API\Secman\SecmenugroupController');
	Route::resource('secorgs', 'API\Secman\SecorgController');
	Route::resource('users', 'API\Secman\UserController');

	Route::resource('kamus_provinsis', 'API\Kamus\Kamus_provinsiController');
	Route::resource('kamus_kab_kotas', 'API\Kamus\Kamus_kab_kotaController');
	Route::resource('kamus_bidang_units', 'API\Kamus\Kamus_bidang_unitController');
	Route::resource('kamus_units', 'API\Kamus\Kamus_unitController');
	Route::resource('kamus_sub_units', 'API\Kamus\Kamus_sub_unitController');
	Route::resource('kamus_lokasis', 'API\Kamus\Kamus_lokasiController');
	Route::resource('kamus_ruangans', 'API\Kamus\Kamus_ruanganController');
	Route::resource('kamus_pegawais', 'API\Kamus\Kamus_pegawaiController');
	Route::resource('kamus_kegiatans', 'API\Kamus\Kamus_kegiatanController');
	Route::resource('kamus_rekenings', 'API\Kamus\Kamus_rekeningController');
	Route::resource('kamus_spks', 'API\Kamus\Kamus_spkController');
	Route::resource('kamus_spms', 'API\Kamus\Kamus_spmController');
	Route::resource('kamus_permen_108s', 'API\Kamus\Kamus_permen_108Controller');
	Route::resource('kamus_permen_64s', 'API\Kamus\Kamus_permen_64Controller');
	Route::resource('kamus_permen_17s', 'API\Kamus\Kamus_permen_17Controller');
	Route::resource('kamus_permen_13s', 'API\Kamus\Kamus_permen_13Controller');
	Route::resource('kamus_mapping_permens', 'API\Kamus\Kamus_mapping_permenController');
	Route::resource('sub_rincian_108s', 'API\Kamus\Sub_rincian_108Controller');
	Route::resource('sub_sub_rincian_108s', 'API\Kamus\Sub_sub_rincian_108Controller');

	Route::resource('rincian_keluars', 'API\Jurnal\Rincian_keluarController');
	Route::resource('rincian_masuks', 'API\Jurnal\Rincian_masukController');
	Route::resource('rincian_koreksis', 'API\Jurnal\Rincian_koreksiController');
	Route::resource('transaksis', 'API\Transaksi\TransaksiController');
	Route::resource('jurnals', 'API\Jurnal\JurnalController');
	Route::resource('pembayarans', 'API\Jurnal\PembayaranController');
	Route::resource('sinkrons', 'API\Sinkron\SinkronController');
	Route::resource('roles', 'API\Role\RoleController');
	Route::resource('rehabs', 'API\Jurnal\RehabController');
	Route::resource('kibs', 'API\Jurnal\KibController');
	Route::resource('penunjangs', 'API\Jurnal\PenunjangController');
});

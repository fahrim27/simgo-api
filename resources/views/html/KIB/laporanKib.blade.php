<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
			section {
				max-width: auto;
				margin: 0 auto;
			}

			body{
				font-family: "Calibri", Helvetica, Arial, sans-serif;
			}

            table.footer{
                width: 100%;
				background-color: none;
            }
            table.footer tr{
                border:none;
            }
            table.footer td.spasi{
                width: 70%;
            }

			table {
				width: 100%;
				border-collapse: collapse;
				font-family: "Calibri", Helvetica, Arial, sans-serif;
				background-color: none;
			}

			.table--striped tr:nth-of-type(odd) {
				background-color: #F9F9F9;
			}

			.table--bordered tr {
				border-bottom: 1px solid black;
			}

			th {
				border-top: 1px solid black;
				font-weight: bold;
				font-size: 11px;
				border-bottom: 1px solid black;
				text-align: center;
                color: black;
			}

			td {
				font-size: 10px;
				padding: 2px;
				text-align: left;
				text-overflow: ellipsis;
				color: black;
				line-height: 1.5em;
				border-top: 1px solid black;
			}

			tbody tr:first-child {
				border-top: 0;
			}

            @page {
                footer: page-footer;
            }

	</style>
</head>
<body>
	<center>
		<h2 style="text-align: center;">Laporan KIB Urut Tahun</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$nama_lokasi}} {{date('Y')-1}}</h3>
	</center>

	<section style="margin-top: 2%;">
        <div class="table-responsive">
        <table class="table--hover" style="text-align: center; justify-content: center;">
            @if($bidang_barang=="A")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>LUAS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>TGL SERTIFIKAT</th>
                        <th>NO SERTIFIKAT</th>
                        <th>PENGGUNAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;">{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['penggunaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="10" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="B")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>UKURAN</th>
                        <th>CC</th>
                        <th>BAHAN</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>NO RANGKA</th>
                        <th>NO MESIN</th>
                        <th>NOPOL</th>
                        <th>NO BPKB</th>
                        <th>JUMLAH BARANG</th>
                        <th>SATUAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;">{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['ukuran']}}</td>
                            <td style="text-align: center;">{{$dt['cc']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['no_rangka_seri']}}</td>
                            <td style="text-align: center;">{{$dt['no_mesin']}}</td>
                            <td style="text-align: center;">{{$dt['nopol']}}</td>
                            <td style="text-align: center;">{{$dt['no_bpkb']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td style="text-align: center;">{{$dt['satuan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="18" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="C")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>KONSTRUKSI</th>
                        <th>BAHAN</th>
                        <th>JUMLAH LANTAI</th>
                        <th>LUAS LANTAI</th>
                        <th>NO IMB</th>
                        <th>TGL IMB</th>
                        <th>LUAS BANGUNAN</th>
                        <th>STATUS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['luas_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['no_imb']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_imb']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="17" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="D")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>KONSTRUKSI</th>
                        <th>BAHAN</th>
                        <th>PANJANG</th>
                        <th>LEBAR</th>
                        <th>LUAS</th>
                        <th>NO IMB</th>
                        <th>TGL IMB</th>
                        <th>STATUS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['panjang_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['lebar_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['no_imb']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_imb']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="17" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="E")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>KONSTRUKSI</th>
                        <th>BAHAN</th>
                        <th>JUMLAH LANTAI</th>
                        <th>LUAS LANTAI</th>
                        <th>NO IMB</th>
                        <th>TGL IMB</th>
                        <th>LUAS BANGUNAN</th>
                        <th>STATUS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['luas_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['no_imb']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_imb']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="17" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="F")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>KONSTRUKSI</th>
                        <th>BAHAN</th>
                        <th>JUMLAH LANTAI</th>
                        <th>LUAS LANTAI</th>
                        <th>NO IMB</th>
                        <th>TGL IMB</th>
                        <th>LUAS BANGUNAN</th>
                        <th>STATUS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['luas_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['no_imb']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_imb']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="17" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="G")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>KONSTRUKSI</th>
                        <th>BAHAN</th>
                        <th>JUMLAH LANTAI</th>
                        <th>LUAS LANTAI</th>
                        <th>NO IMB</th>
                        <th>TGL IMB</th>
                        <th>LUAS BANGUNAN</th>
                        <th>STATUS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['luas_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['no_imb']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_imb']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="17" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang=="RB")
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>UKURAN</th>
                        <th>CC</th>
                        <th>BAHAN</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>BAIK</th>
                        <th>KB</th>
                        <th>RB</th>
                        <th>NO RANGKA</th>
                        <th>NO MESIN</th>
                        <th>NOPOL</th>
                        <th>NO BPKB</th>
                        <th>JUMLAH BARANG</th>
                        <th>SATUAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;" >{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['ukuran']}}</td>
                            <td style="text-align: center;">{{$dt['cc']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['kb']}}</td>
                            <td style="text-align: center;">{{$dt['rb']}}</td>
                            <td style="text-align: center;">{{$dt['no_rangka_seri']}}</td>
                            <td style="text-align: center;">{{$dt['no_mesin']}}</td>
                            <td style="text-align: center;">{{$dt['nopol']}}</td>
                            <td style="text-align: center;">{{$dt['no_bpkb']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td style="text-align: center;">{{$dt['satuan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="18" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @else
                <thead>
                    <tr>
                        <th>NO. </th>
                        <th>KODE 108</th>
                        <th style="width:10%;">N0 REGISTER</th>
                        <th>NAMA BARANG</th>
                        <th>MERK/ALAMAT</th>
                        <th>LUAS TANAH</th>
                        <th>TAHUN PENGADAAN</th>
                        <th>TGL SERTIFIKAT</th>
                        <th>NO SERTIFIKAT</th>
                        <th>PENGGUNAAN</th>
                        <th>HARGA TOTAL</th>
                        <th>KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th style="width:10%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        <tr>
                            <td >{{$x++}}</td>
                            <td >{{$dt['kode_108']}}</td>
                            <td style="width:10%;">{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['penggunaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                            @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="10" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @endif
        </table>
        </div>
	  	<br>
        <footer>
            <table class="footer">
                <tr>
                    <td>
                        Mengetahui,
                        <br><br><br><br><span>NIP</span>
                    </td>
                    <td class="spasi"></td>
                    <td>
                        Mojokerto, {{date('d/m/Y')}}
                        <br><br><br><br><span>NIP</span>
                    </td>
                </tr>
            </table>
        </footer>

        <htmlpagefooter name="page-footer">
            <table class="footer">
                <tr>
                    <td>
                        Laporan KIB Urut Tahun {{$bidang_barang}} {{$nama_lokasi}} {{date('Y')-1}}
                    </td>
                    <td style="text-align: right;">
                        Halaman {PAGENO} / {nbpg}
                    </td>
                </tr>
            </table>
        </htmlpagefooter>
    </section>


</body>
</html>

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

        table.footer,
        table.header{
            width: 100%;
            background-color: none;
        }
        table.footer tr,
        table.header tr{
            border:none;
        }
        table.header td{
            font-weight: bold;
        }
        table.footer td.spasi{
            width: 70%;
        }
        table.header td.spasi{
            width: 40%;
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
        <h2 style="text-align: center;">Laporan Saldo Awak KIB {{$bidang_barang}}</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$nama_lokasi}}</h3>
		<h3 style="margin-top: -1%; text-align: center;">{{date('Y')-1}}</h3>
	</center>

    <section style="margin-top: 2%;">
        <table class="header">
            <tr>
                <td>
                    Propinsi
                </td>
                <td>: Jawa Timur</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>
                    Kab / Kota
                </td>
                <td>: Kabupaten Mojokerto</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>
                    Lokasi
                </td>
                <td>
                    : {{$nama_lokasi}}
                </td>
                <td class="spasi"></td>
                <td>
                    Kepemilikan : {{$kode_kepemilikan}} - Pemerintah Kab / Kota
                </td>
            </tr>
        </table>
	  <div class="table-responsive">
        <table class="table--hover" style="text-align: center; justify-content: center;">
            @if($bidang_barang=="A")
                <thead>
                    <tr>
                        <th colspan="7">Nomor</th>
                        <th colspan="3">Status Tanah</th>
                        <th rowspan="2">Penggunaan</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>No. </th>
                        <th style="width:15%;">Jenis / Nama</th>
                        <th>Kode Barang</th>
                        <th>No. Regs. Induk & Lokal</th>
                        <th>Luas(M2)</th>
                        <th>Tahun Pengadaan</th>
                        <th>Letak / Alamat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th style="width:15%;">2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        @if(isset($dt['no_register']))
                            <tr>
                                <td >{{$x++}}</td>
                                <td style="width:15%;">{{$dt['nama_barang']}}</td>
                                <td >{!! $dt['kode_64'] !!}</td>
                                <td >{{$dt['no_register']}}</td>
                                <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td>{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['penggunaan']}}</td>
                                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                <td >{{$dt['keterangan']}}</td>
                                    @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                            </tr>
                        @endif
                    @endforeach
                        <tr>
                            <td colspan="11" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                            <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                        </tr>
                </tbody>
            @elseif($bidang_barang == "B")
                <thead>
                    <tr>
                        <th rowspan="2">No. </th>
                        <th rowspan="2">Kode Barang No. Regs. Induk & Lokal</th>
                        <th rowspan="2">Jenis / Nama</th>
                        <th rowspan="2">Merk / Tipe</th>
                        <th rowspan="2">Ukuran / CC</th>
                        <th rowspan="2">Bahan</th>
                        <th rowspan="2">Tahun Pengadaan</th>
                        <th rowspan="2">Kondisi</th>
                        <th colspan="4">Nomor</th>
                        <th rowspan="2">Jumlah Barang</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Rangka</th>
                        <th>Mesin</th>
                        <th>NOPOL</th>
                        <th>BPKB</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td >{!! $dt['no_register'] !!}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['ukuran']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['no_rangka_seri']}}</td>
                            <td style="text-align: center;">{{$dt['no_mesin']}}</td>
                            <td style="text-align: center;">{{$dt['nopol']}}</td>
                            <td style="text-align: center;">{{$dt['no_bpkb']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="13" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "C")
                <thead>
                    <tr>
                        <th rowspan="2">No. </th>
                        <th rowspan="2">Jenis / Nama</th>
                        <th colspan="2">Nomor</th>
                        <th>Kondisi Bangunan</th>
                        <th colspan="2">Konstruksi Bangunan</th>
                        <th rowspan="2">Luas Lantai(M2)</th>
                        <th rowspan="2">Letak / Lokasi / Alamat</th>
                        <th colspan="2">Dokumen Gedung</th>
                        <th rowspan="2">Luas Bangunan(M2)</th>
                        <th rowspan="2">Status Tanah</th>
                        <th rowspan="2">Nomor Kode Tanah</th>
                        <th rowspan="2">Asal - Usul & Tahun</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Kode Barang</th>
                        <th>No. Regs Induk & Lokal</th>
                        <th>(B, KB, RB)</th>
                        <th>Bertingkat / Tingkat</th>
                        <th>Bahan / Jenis Konstruksi</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td >{{ $dt['nama_barang'] }}</td>
                            <td >{!!$dt['kode_64']!!}</td>
                            <td >{{$dt['no_register']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{!! $dt['bahan'] !!}</td>
                            <td style="text-align: center;">{{$dt['luas_lantai']}}</td>
                            <td>{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['no_regs_induk_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="15" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "D")
                <thead>
                    <tr>
                        <th rowspan="2">No. </th>
                        <th rowspan="2">Jenis / Nama</th>
                        <th colspan="2">Nomor</th>
                        <th rowspan="2">Konstruksi & Tahun</th>
                        <th rowspan="2">Panjan(M)</th>
                        <th rowspan="2">Lebar(M)</th>
                        <th rowspan="2">Luas(M2)</th>
                        <th rowspan="2">Letak / Lokasi / Alamat</th>
                        <th colspan="2">Dokumen Gedung</th>
                        <th rowspan="2">Status Tanah</th>
                        <th rowspan="2">Nomor Kode Tanah</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th>Kondisi</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Kode Barang</th>
                        <th>No. Regs Induk & Lokal</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>(B, KB, RB)</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td >{{ $dt['nama_barang'] }}</td>
                            <td >{!!$dt['kode_64']!!}</td>
                            <td >{{$dt['no_register']}}</td>
                            <td style="text-align: center;">{!!$dt['konstruksi']!!}</td>
                            <td style="text-align: center;">{{$dt['panjang_tanah']}}</td>
                            <td style="text-align: center;">{{ $dt['lebar_tanah'] }}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td>{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td style="text-align: center;">{{$dt['no_regs_induk_tanah']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="13" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="3" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "E")
                <thead>
                    <tr>
                        <th rowspan="2">No. </th>
                        <th rowspan="2">Jenis / Nama</th>
                        <th colspan="2">Nomor</th>
                        <th colspan="2">Buku Perpustakaan</th>
                        <th >Barang Bercorak / Kesenian</th>
                        <th >Hewan, Ternak / Tumbuhan</th>
                        <th rowspan="2">Jumlah & Kondisi</th>
                        <th rowspan="2">Tahun Pengadaan</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Kode Barang</th>
                        <th>No. Regs Induk & Lokal</th>
                        <th>Judul / Pencipta</th>
                        <th>Spesifikasi</th>
                        <th>Bahan</th>
                        <th>Ukuran</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
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
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{!! $dt['kode_64'] !!}</td>
                            <td >{{$dt['no_register']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td  style="text-align: center;" >{{$dt['tipe']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['ukuran']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="10" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "F")
                <thead>
                    <tr>
                        <th rowspan="2">No. </th>
                        <th rowspan="2" style="width:15%">Jenis / Nama</th>
                        <th >Nomor</th>
                        <th colspan="4">Konstruksi Bangunan</th>
                        <th rowspan="2">Letak / Lokasi / Alamat</th>
                        <th colspan="2">Dokumen</th>
                        <th rowspan="2">Status Tanah</th>
                        <th rowspan="2">Nilai Saat Ini(Rp.)</th>
                        <th rowspan="2">Nilai Kontrak(Rp.)</th>
                        <th rowspan="2">Ket.</th>
                    </tr>
                    <tr>
                        <th>No. Regs Induk & Lokal</th>
                        <th>Bangunan (P, SP, D)</th>
                        <th>Jumlah Tingkat</th>
                        <th>Beton / Tidak</th>
                        <th>Luas(M2)</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th style="width:15%">2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @php $total1=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td style="width:15%">{{$dt['nama_barang']}}</td>
                            <td >{{$dt['no_register']}}</td>
                            <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                            <td style="text-align: center;">{{$dt['jumlah_lantai']}}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                            <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td style="text-align: center;">Rp. {{number_format($dt['nilai_lunas'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                @php $total1 += $dt['nilai_lunas'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="11" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="1" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                        <td colspan="1" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total1, 2, ',','.')}}</td>
                        <td colspan="1" style="color: black; border-bottom: 1px solid black;"></td>
                    </tr>
                </tbody>
            @else
                <thead>
                    <tr>
                        <th colspan="7">Nomor</th>
                        <th colspan="3">Status Tanah</th>
                        <th rowspan="2">Penggunaan</th>
                        <th rowspan="2">Harga(Rp.)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>No. </th>
                        <th style="width:15%;">Jenis / Nama</th>
                        <th>Kode Barang</th>
                        <th>No. Regs. Induk & Lokal</th>
                        <th>Luas(M2)</th>
                        <th>Tahun Pengadaan</th>
                        <th>Letak / Alamat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th style="width:15%;">2</th>
                        <th>3</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        @if(isset($dt['no_register']))
                            <tr>
                                <td >{{$x++}}</td>
                                <td style="width:15%;">{{$dt['nama_barang']}}</td>
                                <td >{!! $dt['kode_64'] !!}</td>
                                <td >{{$dt['no_register']}}</td>
                                <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td>{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['penggunaan']}}</td>
                                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                <td >{{$dt['keterangan']}}</td>
                                    @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                            </tr>
                        @endif
                    @endforeach
                        <tr>
                            <td colspan="11" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
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
                    Laporan KIB Saldo Awal {{$bidang_barang}} {{$nama_lokasi}} {{date('Y')-1}}
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

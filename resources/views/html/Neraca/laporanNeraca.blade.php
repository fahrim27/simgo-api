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
        <h2 style="text-align: center;">Laporan Daftar Aset Tetap {{$bidang_barang}}</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$uraian_sub_rincian['uraian_sub_rincian']}}</h3>
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
                <td>
                    : Kabupaten Mojokerto
                </td>
                <td class="spasi"></td>
                <td>
                    Kode Rincian : {{$new_kode_108}} {{$uraian_sub_rincian['uraian_sub_rincian']}}
                </td>
            </tr>
        </table>
	  <div class="table-responsive">
        <table class="table--hover" style="text-align: center; justify-content: center;">
            @if($bidang_barang=="A")
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2" style="width:15%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th rowspan="2">Jenis / Nama Barang</th>
                        <th rowspan="2">Letak / Lokasi / Alamat</th>
                        <th colspan="3">Status Tanah</th>
                        <th rowspan="2">Luas(M2)</th>
                        <th rowspan="2">Konstruksi</th>
                        <th rowspan="2">Tahun Pengadaan & Tahun Perolehan</th>
                        <th rowspan="2">Jumlah Barang & Kondisi</th>
                        <th rowspan="2">Harga</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
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
                                <td style="width:15%;">{{$dt['no_register']}}</td>
                                <td >{{$dt['nama_barang']}}</td>
                                <td >{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
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
                        <th>No. </th>
                        <th style="width:15%;">Kode Barang No. Regs. Induk & Lokal</th>
                        <th>Jenis / Nama</th>
                        <th>Merk / Tipe</th>
                        <th>Ukuran / CC</th>
                        <th>Bahan</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>No. Seri / Rangka & No. Mesin</th>
                        <th>No. Polisi & No. BPKB</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td style="width:15%;">{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td style="text-align: center;">{!!$dt['merk_alamat']!!}</td>
                            <td style="text-align: center;">{!!$dt['ukuran']!!}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{!!$dt['no_rangka_seri']!!}</td>
                            <td style="text-align: center;">{!!$dt['nopol']!!}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
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
            @elseif($bidang_barang == "C")
                <thead>
                    <tr>
                        <th>No.</th>
                        <th style="width:15%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th>Jenis / Nama Barang</th>
                        <th>Letak / Lokasi / Alamat</th>
                        <th>Panjang(M) & Lebar(M)</th>
                        <th>Luas(M2)</th>
                        <th>Konstruksi</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        @if(isset($dt['no_register']))
                            <tr>
                                <td style="text-align: center;">{{$x++}}</td>
                                <td style="width:15%;" >{{ $dt['no_register'] }}</td>
                                <td >{{$dt['nama_barang']}}</td>
                                <td >{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{!! $dt['panjang'] !!}</td>
                                <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                                <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                <td >{{$dt['keterangan']}}</td>
                                    @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="9" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "D")
                <thead>
                    <tr>
                        <th>No.</th>
                        <th style="width:15%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th>Jenis / Nama Barang</th>
                        <th>Letak / Lokasi / Alamat</th>
                        <th>Panjang(M) & Lebar(M)</th>
                        <th>Luas(M2)</th>
                        <th>Konstruksi</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        @if(isset($dt['kode_64']))
                            <tr>
                                <td style="text-align: center;">{{$x++}}</td>
                                <td style="width:15%;" >{!! $dt['kode_64'] !!}</td>
                                <td >{{$dt['nama_barang']}}</td>
                                <td >{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{!! $dt['panjang'] !!}</td>
                                <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                                <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                <td >{{$dt['keterangan']}}</td>
                                    @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="9" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "E")
                <thead>
                    <tr>
                        <th>No.</th>
                        <th style="width:20%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th style="width:15%;">Jenis / Nama Barang</th>
                        <th>Uraian</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th style="width:20%;">2</th>
                        <th style="width:15%;">3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td style="width:20%;" >{{$dt['no_register']}}</td>
                            <td style="width:15%;" >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="6" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "F")
                <thead>
                    <tr>
                        <th>No.</th>
                        <th style="width:15%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th>Jenis / Nama Barang</th>
                        <th>Letak / Lokasi / Alamat</th>
                        <th>Panjang(M) & Lebar(M)</th>
                        <th>Luas(M2)</th>
                        <th>Konstruksi</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                    @foreach($data as $dt)
                        @if(isset($dt['no_register']))
                            <tr>
                                <td style="text-align: center;">{{$x++}}</td>
                                <td style="width:15%;" >{!! $dt['no_register'] !!}</td>
                                <td >{{$dt['nama_barang']}}</td>
                                <td >{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{!! $dt['panjang'] !!}</td>
                                <td style="text-align: center;">{{$dt['luas_bangunan']}}</td>
                                <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                <td >{{$dt['keterangan']}}</td>
                                    @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="9" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @elseif($bidang_barang == "G")
                <thead>
                    <tr>
                        <th>No.</th>
                        <th style="width:15%;">Kode Rinc. Objek No. Regs Induk</th>
                        <th>Jenis / Nama Barang</th>
                        <th>Uraian</th>
                        <th>Tahun Pengadaan & Tahun Perolehan</th>
                        <th>Jumlah Barang & Kondisi</th>
                        <th>Harga(Rp.)</th>
                        <th>Keterangan</th>
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
                    </tr>
                </thead>
                <tbody>
                    @php $x=1 @endphp
                    @php $total=0 @endphp
                @foreach($data as $dt)
                    @if(isset($dt['no_register']))
                        <tr>
                            <td style="text-align: center;">{{$x++}}</td>
                            <td style="width:15%;">{{$dt['no_register']}}</td>
                            <td >{{$dt['nama_barang']}}</td>
                            <td >{{$dt['merk_alamat']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="6" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
                </tbody>
            @else
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2" style="width:15%;">Kode Rinc. Objek No. Regs. Induk</th>
                        <th rowspan="2">Jenis / Nama Barang</th>
                        <th rowspan="2">Letak / Lokasi / Alamat</th>
                        <th colspan="3">Status Tanah</th>
                        <th rowspan="2">Luas(M2)</th>
                        <th rowspan="2">Konstruksi</th>
                        <th rowspan="2">Tahun Pengadaan & Tahun Perolehan</th>
                        <th rowspan="2">Jumlah Barang & Kondisi</th>
                        <th rowspan="2">Harga</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
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
                                <td style="width:15%;">{{$dt['no_register']}}</td>
                                <td >{{$dt['nama_barang']}}</td>
                                <td >{{$dt['merk_alamat']}}</td>
                                <td style="text-align: center;">{{$dt['status_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['tgl_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['no_sertifikat']}}</td>
                                <td style="text-align: center;">{{$dt['luas_tanah']}}</td>
                                <td style="text-align: center;">{{$dt['konstruksi']}}</td>
                                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
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
                    Laporan Daftar Aset Tetap {{$bidang_barang}} Tanah {{date('Y')-1}}
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

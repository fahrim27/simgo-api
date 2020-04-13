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
        <h2 style="text-align: center;">Laporan {{$nama_jurnal}} {{$nama_lokasi}}</h2>
		<h3 style="margin-top: -1%; text-align: center;"> {{$tahun}} </h3>
	</center>

    <section style="margin-top: 2%;">
	  <div class="table-responsive">
        <table class="table--hover" style="text-align: center; justify-content: center;">
            @if ($kode_jurnal == "101")
                @if ($nomor_lokasi == "12.01.35.16.111.00002")
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th>Nomor SPK</th>
                            <th>Uraian SPK</th>
                            <th style="width:10%;">Kode Rekening Barang</th>
                            <th style="width:15%;">No. Register</th>
                            <th>Nama Barang</th>
                            <th style="width:10%;" >Merk / Alamat</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Total</th>
                            <th>Baik</th>
                            <th>Kurang Baik</th>
                            <th>Rusak Berat</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th style="width:10%;">4</th>
                            <th style="width:15%;">5</th>
                            <th>6</th>
                            <th style="width:10%;" >7</th>
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
                                    <td>{{$dt['nomor_lokasi']}}</td>
                                    <td >{{$dt['nama_lokasi']}}</td>
                                    <td style="width:10%;" >{{$dt['kode_108']}}</td>
                                    <td style="width:15%;" >{{$dt['no_register']}}</td>
                                    <td >{{$dt['nama_barang']}}</td>
                                    <td style="width:10%;"  >{{$dt['merk_alamat']}}</td>
                                    <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                    <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                    <td >{{$dt['baik']}}</td>
                                    <td >{{$dt['kb']}}</td>
                                    <td >{{$dt['rb']}}</td>
                                    <td >{{$dt['keterangan']}}</td>
                                        @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <td colspan="8" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                                <td colspan="5" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                            </tr>
                    </tbody>
                @elseif($nomor_lokasi == '12.01.35.16.111.00001' || $nomor_lokasi == '12.01.35.16.131.00001.00001')
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th>Nomor SPK</th>
                            <th>Uraian SPK</th>
                            <th style="width:10%;">Kode Rekening Barang</th>
                            <th style="width:15%;">No. Register</th>
                            <th style="width:10%;">Nama Barang</th>
                            <th style="width:10%;" >Merk / Alamat</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Total</th>
                            <th>Baik</th>
                            <th>Kurang Baik</th>
                            <th>Rusak Berat</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th style="width:10%;">4</th>
                            <th style="width:15%;">5</th>
                            <th style="width:10%;">6</th>
                            <th style="width:10%;" >7</th>
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
                                    <td>{{$dt['no_bukti_perolehan']}}</td>
                                    <td >{{$dt['deskripsi_spk_dokumen']}}</td>
                                    <td style="width:10%;" >{{$dt['kode_108']}}</td>
                                    <td style="width:15%;" >{{$dt['no_register']}}</td>
                                    <td style="width:10%;" >{{$dt['nama_barang']}}</td>
                                    <td style="width:10%;"  >{{$dt['merk_alamat']}}</td>
                                    <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                    <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                    <td >{{$dt['baik']}}</td>
                                    <td >{{$dt['kb']}}</td>
                                    <td >{{$dt['rb']}}</td>
                                    <td >{{$dt['keterangan']}}</td>
                                        @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <td colspan="8" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                                <td colspan="5" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                            </tr>
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th style="width:10%;">Kode Rekening Barang</th>
                            <th style="width:15%;">No. Register</th>
                            <th>Nama Barang</th>
                            <th style="width:10%;" >Merk / Alamat</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Total</th>
                            <th>Baik</th>
                            <th>Kurang Baik</th>
                            <th>Rusak Berat</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th style="width:10%;">2</th>
                            <th style="width:15%;">3</th>
                            <th>4</th>
                            <th style="width:10%;" >5</th>
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
                                    <td >{{$x++}}</td>
                                    <td style="width:10%;" >{{$dt['kode_108']}}</td>
                                    <td style="width:15%;" >{{$dt['no_register']}}</td>
                                    <td >{{$dt['nama_barang']}}</td>
                                    <td style="width:10%;"  >{{$dt['merk_alamat']}}</td>
                                    <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                    <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                    <td >{{$dt['baik']}}</td>
                                    <td >{{$dt['kb']}}</td>
                                    <td >{{$dt['rb']}}</td>
                                    <td >{{$dt['keterangan']}}</td>
                                        @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <td colspan="6" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                                <td colspan="5" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                            </tr>
                    </tbody>
                @endif
            @else
                @if($kode_jurnal == '103' && $nomor_lokasi == '12.01.35.16.111.00001')
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th>Nomor SPK</th>
                            <th>Uraian SPK</th>
                            <th style="width:10%;">Kode Rekening Barang</th>
                            <th style="width:15%;">No. Register</th>
                            <th>Nama Barang</th>
                            <th style="width:10%;" >Merk / Alamat</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Total</th>
                            <th>Baik</th>
                            <th>Kurang Baik</th>
                            <th>Rusak Berat</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th style="width:10%;">4</th>
                            <th style="width:15%;">5</th>
                            <th>6</th>
                            <th style="width:10%;" >7</th>
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
                                    <td>{{$dt['nomor_lokasi']}}</td>
                                    <td >{{$dt['nama_lokasi']}}</td>
                                    <td style="width:10%;" >{{$dt['kode_108']}}</td>
                                    <td style="width:15%;" >{{$dt['no_register']}}</td>
                                    <td >{{$dt['nama_barang']}}</td>
                                    <td style="width:10%;"  >{{$dt['merk_alamat']}}</td>
                                    <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                    <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                    <td >{{$dt['baik']}}</td>
                                    <td >{{$dt['kb']}}</td>
                                    <td >{{$dt['rb']}}</td>
                                    <td >{{$dt['keterangan']}}</td>
                                        @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <td colspan="8" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                                <td colspan="5" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                            </tr>
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th style="width:10%;">Kode Rekening Barang</th>
                            <th style="width:15%;">No. Register</th>
                            <th>Nama Barang</th>
                            <th style="width:10%;" >Merk / Alamat</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Total</th>
                            <th>Baik</th>
                            <th>Kurang Baik</th>
                            <th>Rusak Berat</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th style="width:10%;">2</th>
                            <th style="width:15%;">3</th>
                            <th>4</th>
                            <th style="width:10%;" >5</th>
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
                                    <td >{{$x++}}</td>
                                    <td style="width:10%;" >{{$dt['kode_108']}}</td>
                                    <td style="width:15%;" >{{$dt['no_register']}}</td>
                                    <td >{{$dt['nama_barang']}}</td>
                                    <td style="width:10%;"  >{{$dt['merk_alamat']}}</td>
                                    <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                                    <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                                    <td >{{$dt['baik']}}</td>
                                    <td >{{$dt['kb']}}</td>
                                    <td >{{$dt['rb']}}</td>
                                    <td >{{$dt['keterangan']}}</td>
                                        @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <td colspan="6" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                                <td colspan="5" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                            </tr>
                    </tbody>
                @endif
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
                    Laporan {{$nama_jurnal}} {{$nama_lokasi}} {{$tahun}}
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

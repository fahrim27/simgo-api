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
        <h2 style="text-align: center;">Laporan Saldo Awak KIB {{$data['nama_jurnal']}} {{$nama_lokasi}}</h2>
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
            <thead>
                <tr>
                    <th>No. </th>
                    <th style="width:10%;">NO. REGISTER INDUK</th>
                    <th style="width:10%;">NAMA BARANG</th>
                    <th style="width:10%;">MERK / ALAMAT & TIPE</th>
                    <th>NO. SERTIFIKAT & NO. RANGKA SERI</th>
                    <th>NO. MESIN & NOPOL</th>
                    <th>BAHAN</th>
                    <th>TAHUN PENGADAAN</th>
                    <th>KONSTRUKSI & UKURAN</th>
                    <th>SATUAN</th>
                    <th>KONDISI</th>
                    <th>JUMLAH</th>
                    <th>NILAI(Rp.)</th>
                    <th>KETERANGAN</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th style="width:10%;">2</th>
                    <th style="width:10%;">3</th>
                    <th style="width:10%;">4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
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
                            <td style="width:10%;">{!!$dt['no_register']!!}</td>
                            <td style="width:10%;" >{{$dt['nama_barang']}}</td>
                            <td style="width:10%;" >{!!$dt['merk_alamat']!!}</td>
                            <td style="text-align: center;">{!!$dt['no_sertifikat']!!}</td>
                            <td style="text-align: center;">{!!$dt['no_mesin']!!}</td>
                            <td style="text-align: center;">{{$dt['bahan']}}</td>
                            <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                            <td style="text-align: center;">{!!$dt['konstruksi']!!}</td>
                            <td style="text-align: center;">{{$dt['satuan']}}</td>
                            <td style="text-align: center;">{{$dt['baik']}}</td>
                            <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                            <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                            <td >{{$dt['keterangan']}}</td>
                                @php $total += $dt['harga_total_plus_pajak_saldo'] @endphp
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td colspan="12" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                        <td colspan="2" style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
                    </tr>
            </tbody>
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
                    Laporan {{$data['nama_jurnal']}} / {{$nama_lokasi}} {{date('Y')-1}}
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

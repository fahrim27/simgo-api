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
		<h2 style="text-align: center;">Laporan Mutasi Keluar Barang</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$nama_lokasi}} {{date('Y')-1}}</h3>

	<section style="margin-top: 2%;">
	  <div class="table-responsive">
	    <table class="table--hover" style="text-align: center; justify-content: center;">
	      <thead>
            <tr>
                <th rowspan="4">No.</th>
                <th rowspan="4">Kode Barang Kode Rinc. Objek No. Regs. Induk</th>
                <th colspan="2" rowspan="4">Nama Barang Merk / Alamat Tipe</th>
                <th rowspan="4">No. Sertifikat No. Pabrik No. Rangka</th>
                <th rowspan="4">No. Mesin No. Polisi</th>
                <th rowspan="4">Bahan / Konstruksi (P/S/D)</th>
                <th rowspan="4">Satuan / Asal-Usul</th>
                <th colspan="2">Jumlah Awal</th>
                <th colspan="2">Mutasi</th>
                <th colspan="2">Jumlah Akhir</th>
                <th rowspan="4">Tahun Pengadaan</th>
                <th rowspan="4">Ket.</th>
            </tr>
            <tr>
                <th rowspan="3">Barang</th>
                <th rowspan="3">Harga</th>
                <th colspan="2">Berkurang</th>
                <th rowspan="3">Barang</th>
                <th rowspan="3">Harga</th>
            </tr>
            <tr>
                <th colspan="2">Bertambah</th>
            </tr>
            <tr>
                <th>Barang</th>
                <th>Harga</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th colspan="2">3</th>
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
	      	<?php
	      		$total_nilai = 0;
	      		$total_nilai_keluar = 0;
	      		$total_nilai_baru = 0;
	      	?>
	      	@foreach($data as $dt)
	      	 @if(isset($dt['id_aset']))
		      <tr>
		        <td style="text-align: center;">{{$x++}}</td>
		        <td style="width:10%;">{!!$dt['id_aset']!!}</td>
		        <td>{{$dt['uraian_64']}}</td>
		        <td>{{$dt['merk_alamat']}}</td>
		        <td style="text-align: center;">{!!$dt['no_sertifikat']!!}</td>
		        <td style="text-align: center;">{!!$dt['no_mesin']!!}</td>
		        <td style="text-align: center;">{!!$dt['bahan']!!}</td>
		        <td style="text-align: center;">{{$dt['satuan']}}</td>
                <td style="text-align: center;">{{$dt['saldo_barang']}}</td>
                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo'], 2, ',','.')}}</td>
                <td style="text-align: center;">{{$dt['jumlah_barang']}}</td>
                <td>Rp. {{number_format($dt['nilai_keluar'], 2, ',','.')}}</td>
                <td style="text-align: center;">{{$dt['saldo_barang_baru']}}</td>
                <td>Rp. {{number_format($dt['harga_total_plus_pajak_saldo_baru'], 2, ',','.')}}</td>
                <td style="text-align: center;">{{$dt['tahun_pengadaan']}}</td>
                <td>{{$dt['keterangan']}}</td>
		        	<?php
                        $total_nilai += $dt['harga_total_plus_pajak_saldo'];
                        $total_nilai_keluar += $dt['nilai_keluar'];
                        $total_nilai_baru += $dt['harga_total_plus_pajak_saldo_baru'];
		        	?>
		      </tr>
		     @endif
		    @endforeach
		    <tr>
		   	  	<td colspan="9" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
                <td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_nilai, 2, ',','.')}}</td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_nilai_keluar, 2, ',','.')}}</td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_nilai_baru, 2, ',','.')}}</td>
                <td colspan="2" style="border-bottom: 1px solid black;"></td>
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
                    Laporan Mutasi Keluar Barang {{$nama_lokasi}} {{date('Y')-1}}
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

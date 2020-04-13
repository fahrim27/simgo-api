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
		<h2 style="text-align: center;">Laporan Mutasi Keluar 108</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$nama_lokasi}} {{date('Y')-1}}</h3>

	<section style="margin-top: 2%;">
	  <div class="table-responsive">
	    <table class="table--hover" style="text-align: center; justify-content: center;">
	      <thead>
	      <tr>
	        <th>NO. </th>
	        <th style="width:15%;">LOKASI TUJUAN</th>
	        <th>NO BA ST</th>
	        <th>TGL BA ST</th>
	        <th style="width:10%;">NO REGISTER</th>
	        <th>KODE 108</th>
	        <th>KODE 64</th>
	        <th style="width:10%;">NAMA BARANG</th>
	        <th style="width:10%;">MERK/ALAMAT</th>
	        <th>JUMLAH BARANG</th>
	        <th>HARGA SATUAN</th>
	        <th>HARGA TOTAL</th>
	        <th>NOPOL</th>
	      </tr>
	      <tr>
	        <th>1</th>
	        <th style="width:15%;">2</th>
	        <th>3</th>
	        <th>4</th>
	        <th style="width:10%;">5</th>
	        <th>6</th>
	        <th>7</th>
	        <th style="width:10%;">8</th>
	        <th style="width:10%;">9</th>
	        <th>10</th>
	        <th>11</th>
	        <th>12</th>
	        <th>13</th>
	      </tr>
	      </thead>
	      <tbody>
	      	@php $x=1 @endphp
	      	<?php
	      		$total = 0;
	      	?>
	      	@foreach($data as $dt)
	      	 @if(isset($dt['no_register']))
		      <tr>
		        <td style="text-align: center;">{{$x++}}</td>
		        <td style="width:15%;">{{$dt['nama_lokasi']}}</td>
		        <td>{{$dt['no_ba_st']}}</td>
		        <td>{{$dt['tgl_ba_st']}}</td>
		        <td style="width:10%;">{{$dt['no_register']}}</td>
		        <td>{{$dt['kode_108']}}</td>
		        <td>{{$dt['kode_64']}}</td>
		        <td style="width:10%;">{{$dt['nama_barang']}}</td>
		        <td style="width:10%;">{{$dt['merk_alamat']}}</td>
		        <td style="text-align: center;">{{$dt['jumlah_barang']}}</td>
		        <td>Rp. {{number_format($dt['harga_satuan'], 2, ',','.')}}</td>
		        <td>Rp. {{number_format($dt['nilai_keluar'], 2, ',','.')}}</td>
                <td style="text-align: center;">{{$dt['nopol']}}</td>
		        	<?php
		        		$total += $dt['nilai_keluar'];
		        	?>
		      </tr>
		     @endif
		    @endforeach
		    <tr>
		   	  	<td colspan="11" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
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
                    Laporan Mutasi Keluar 108 {{$nama_lokasi}} {{date('Y')-1}}
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

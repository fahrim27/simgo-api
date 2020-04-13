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
		<h2 style="text-align: center;">Laporan Penyusutan KIB Reklas Masuk 64</h2>
		<h3 style="margin-top: -1%; text-align: center;">{{$nama_lokasi}} {{date('Y')-1}}</h3>

	<section style="margin-top: 2%;">
	  <div class="table-responsive">
	    <table class="table--hover" style="text-align: center; justify-content: center;">
	      <thead>
	      <tr>
	        <th>NO. </th>
	        <th>KODE 64</th>
	        <th>KODE 64 BARU</th>
	        <th>NILAI PEROLEHAN</th>
	        <th>AKUMULASI PENYUSUTAN</th>
	        <th>BEBAN</th>
	        <th>AKUMULASI PENYUSUTAN BERJALAN</th>
	        <th>NILAI BUKU</th>
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
	      </tr>
	      </thead>
	      <tbody>
	      	@php $x=1 @endphp
	      	<?php
	      		$total_perolehan = 0;
	      		$total_penyusutan = 0;
	      		$total_penyusutanBerjalan = 0;
	      		$total_beban = 0;
	      		$total_buku = 0;
	      	?>
	      	@foreach($data as $dt)
	      	 @if(isset($dt['kode_64']))
		      <tr>
		        <td style="text-align: center;">{{$x++}}</td>
		        <td>{{$dt['kode_64']}}</td>
		        <td>{{$dt['kode_64_baru']}}</td>
		        @if(isset($dt['nilai_perolehan']))
		        	<td>Rp. {{number_format($dt['nilai_perolehan'], 2, ',','.')}}</td>
		        @else
		        	<td>Rp. {{number_format($dt['nilai_pengadaan'], 2, ',','.')}}</td>
		        @endif
		        <td>Rp. {{number_format($dt['akumulasi_penyusutan'], 2, ',','.')}}</td>
		        <td>Rp. {{number_format($dt['beban'], 2, ',','.')}}</td>
		        <td>Rp. {{number_format($dt['akumulasi_penyusutan_berjalan'], 2, ',','.')}}</td>
		        <td>Rp. {{number_format($dt['nilai_buku'], 2, ',','.')}}</td>
		        	<?php
		        		$total_perolehan += $dt['nilai_perolehan'];
			      		$total_penyusutan += $dt['akumulasi_penyusutan'];
			      		$total_penyusutanBerjalan += $dt['akumulasi_penyusutan_berjalan'];
			      		$total_beban += $dt['beban'];
			      		$total_buku += $dt['nilai_buku'];
		        	?>
		      </tr>
		     @endif
		    @endforeach
		    <tr>
		   	  	<td colspan="3" style="text-align: right; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_perolehan, 2, ',','.')}}</td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_penyusutan, 2, ',','.')}}</td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_beban, 2, ',','.')}}</td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_penyusutanBerjalan, 2, ',','.')}}</td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total_buku, 2, ',','.')}}</td>
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
                        Laporan Penyusutan KIB Reklas Masuk 64 {{$nama_lokasi}} {{date('Y')-1}}
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

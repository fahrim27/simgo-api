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
		<h2 style="text-align:center;">Laporan Rekap Realisasi {{date('Y')-1}}</h2>
	</center>

	<section style="margin-top: 2%;">
	  <div class="table-responsive">
	    <table class="table--hover" style="text-align: center; justify-content: center;">
	      <thead>
	      <tr>
	        <th>No. </th>
	        <th>Nomor Lokasi</th>
	        <th>Nama Lokasi</th>
	        <th>No SPK SP Dokumen</th>
	        <th>Nilai SPK + PENUNJANG</th>
	        <th>Nilai Realisasi</th>
          </tr>
          <tr>
              <th>1</th>
              <th>2</th>
              <th>3</th>
              <th>4</th>
              <th>5</th>
              <th>6</th>
          </tr>
	      </thead>
	      <tbody>
	      	@php $x=1 @endphp
	      	@php $total=0 @endphp
	      	@php $total1=0 @endphp
          @foreach($data as $dt)
            @if(isset($dt['nomor_lokasi']))
		      <tr>
		        <td data-label="First Name">{{$x++}}</td>
		        <td >{{$dt['nomor_lokasi']}}</td>
		        <td >{{$dt['nama_lokasi']}}</td>
		        <td >{{$dt['no_spk']}}</td>
		        <td >Rp. {{number_format($dt['nilai_spk_plus_penunjang'], 2, ',','.')}}</td>
		        <td >Rp. {{number_format($dt['total_realisasi'], 2, ',','.')}}</td>
		        	@php $total += $dt['nilai_spk_plus_penunjang'] @endphp
		        	@php $total1 += $dt['total_realisasi'] @endphp
              </tr>
              @endif
		   @endforeach
		   	  <tr>
		   	  	<td colspan="4" style="text-align: center; color: black; border-bottom: 1px solid black;">Total Nilai (Rp.) : </td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total, 2, ',','.')}}</td>
		   	  	<td style="color: black; border-bottom: 1px solid black;">Rp. {{number_format($total1, 2, ',','.')}}</td>
		   	  </tr>
	      </tbody>
	    </table>
	  </div>
	  	<br><br>
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
                    Laporan Rekap Realisasi {{date('Y')-1}}
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

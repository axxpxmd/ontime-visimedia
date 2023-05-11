<html>
<head>
	<title>Laporan Kehadiran</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 10pt;
		}
	</style>
	<center>
		<h5>Laporan Kehadiran</h5>
        <h5>
			@if(date('F') == 'January') {{ 'Januari' }}
			@elseif(date('F') == 'February') {{ 'Februari' }}
			@elseif(date('F') == 'March') {{ 'Maret' }}
			@elseif(date('F') == 'April') {{ 'April' }}
			@elseif(date('F') == 'May') {{ 'Mei' }}
			@elseif(date('F') == 'June') {{ 'Juni' }}
			@elseif(date('F') == 'July') {{ 'Juli' }}
			@elseif(date('F') == 'August') {{ 'Agustus' }}
			@elseif(date('F') == 'October') {{ 'Oktober' }}
			@elseif(date('F') == 'December') {{ 'Desember' }}
			@endif
			{{ date('Y') }}
		</h5>
		<br>
	</center>


	<table class='table table-bordered'>
		<thead>
			<tr>
				<th width='10'>No</th>
				<th width='55'>Tanggal</th>
				<th>Aktifitas</th>
				<th width='60'>Jam Mulai</th>
				<th width='60'>Jam Selesai</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@forelse($kehadiran as $item)
			<tr>
				<td>{{ $i++ }}</td>
				<td>{{$item->tanggal}}</td>
				<td>{{$item->keterangan}}</td>
				<td>{{$item->jam_mulai}}</td>
				<td>{{$item->jam_selesai}}</td>
			</tr>
            @empty
            <tr>
                <td colspan="5"
                    class="text-center">Tidak ada data yang tersedia</td>
            </tr>
			@endforelse
		</tbody>
	</table>
	<div style="width: 40%; float: right; text-align: center; font-size: 12px; font-family: arial,tahoma; margin-top: 5%;">
        <strong>
            Tangerang Selatan, {{ date('d F Y') }}<br>
        </strong>
        <br><br><br><br><br>
		{{ auth()->user()->nama }}
    </div>

</body>
</html>

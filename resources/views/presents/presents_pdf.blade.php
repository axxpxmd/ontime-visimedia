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

		<br>
	</center>

    <table>
        <tr>
            <td>Tanggal</td>
            <td>: {{  $filter['tanggal'] }}</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>: {{  $filter['uker'] }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>: {{  $filter['keterangan'] }}</td>
        </tr>
    </table>
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>#</th>
                                <th>Username</th>

                                <th>Keterangan</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Total Jam</th>
                                <th>Tanggal</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@forelse($kehadiran as $item)
			<tr>
				<th>{{ $i++ }}</th>

                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        @if ($item->jam_masuk)
                                            <td>{{ date('H:i:s', strtotime($item->jam_masuk)) }}


                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        @if ($item->jam_keluar)
                                            <td>{{ date('H:i:s', strtotime($item->jam_keluar)) }}

                                            </td>
                                            <td>
                                                {{$item->total_jam ? $item->total_jam : '-'}}
                                            </td>
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                        @endif
                                        <td>{{ $item->tanggal }}</td>
			</tr>
            @empty
            <tr>
                <td colspan="7"
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

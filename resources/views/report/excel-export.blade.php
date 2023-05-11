<table>
    <tbody>
        <tr>
            <td>Periode Mulai</td>
            <td>{{ $periode_mulai }}</td>
        </tr>
        <tr>
            <td>Periode Selesai</td>
            <td>{{ $periode_selesai }}</td>
        </tr>
        <tr>
            <td>OPD</td>
            <td>{{ $opd }}</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>{{ $unit_kerja }}</td>
        </tr>
        <tr>
            <td>Sub Unit Kerja</td>
            <td>{{ $subunit_kerja }}</td>
        </tr>
    </tbody>

</table>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>

            <th>Unit Kerja</th>
            <th>Jam Kerja</th>
            <th>Sallary</th>
            <th>Total Sanksi</th>
            <th>Sallary Final</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $k => $d)
            <tr>
                <td>{{ $k+1  }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->uker }}</td>
                <td>{{ $d->jam_kerja }}</td>
                <td>{{ rupiah($d->sallary) }}</td>
                <td>{{ $d->denda ? rupiah($d->denda):'-' }}</td>
                <td>{{ rupiah($d->sallary - $d->denda) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">TOTAL</td>
            <td>{{ rupiah($data->sum('sallary')) }}</td>
            <td>{{ rupiah($data->sum('denda')) }}</td>
            <td>{{ rupiah( (int) $data->sum('sallary') - (int) $data->sum('denda')) }}</td>
        </tr>
        {{-- <tr>
            <td colspan="5"><b>Total Telat {{ $totalJamTelat }} Jam Bulan Ini</b></td>
        </tr> --}}
    </tbody>
</table>

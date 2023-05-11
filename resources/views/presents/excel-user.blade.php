<table>
    <tbody>
        <tr>
            <td colspan="7" align="center"><h3>Rekap Absen</h3></td>

        </tr>
        <tr>
            <td>
                Tanggal Awal
            </td>
            <td>{{ date('d/m/Y', strtotime($periode_mulai)) }}  </td>
        </tr>
        <tr>
            <td>
                Tanggal Akhir
            </td>
            <td> {{ date('d/m/Y', strtotime($periode_selesai)) }}  </td>

        </tr>
        <tr>
            <td>
                Nama
            </td>
            <td>{{ $user->nama }}</td>

        </tr>
        <tr>
            <td>
                Gaji
            </td>
            <td>{{ rupiah($gaji) }}</td>

        </tr>
        <tr>
            <td>
                Unit Kerja
            </td>
            <td>{{ $unit_kerja }}</td>

        </tr>
    </tbody>
</table>
<table >
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            {{-- <th>Jam Kerja</th>
            <th>Diluar Jam Kerja</th> --}}
            <th>Sanksi Administrasi
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($presents as $present)
            <tr>
                <td>{{ date('d/m/Y', strtotime($present->tanggal)) }}</td>
                <td>{{ $present->keterangan }}</td>
                @if ($present->jam_masuk)
                    <td>{{ date('H:i:s', strtotime($present->jam_masuk)) }}</td>
                @else
                    <td>-</td>
                @endif
                @if($present->jam_keluar)
                    <td>{{ date('H:i:s', strtotime($present->jam_keluar)) }}</td>

                @else
                    <td>-</td>

                @endif
                <td>
                    {{$present->denda ? rupiah($present->denda) : '-'}}
                    @php

                    @endphp
                </td>
            </tr>

        @endforeach
        <tr>
            <td colspan="4">TOTAL SANKSI
            </td>
            <td>{{ $total_potongan > 0 ? rupiah($total_potongan):  "-"  }}</td>
        </tr>
        <tr>
            <td colspan="4">TOTAL TERIMA
            </td>
            <td>{{ rupiah($total_terima)  }}</td>
        </tr>
        {{-- <tr>
            <td colspan="5"><b>Total Telat {{ $totalJamTelat }} Jam Bulan Ini</b></td>
        </tr> --}}
    </tbody>
</table>

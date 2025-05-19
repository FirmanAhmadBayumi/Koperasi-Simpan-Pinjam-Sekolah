<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pinjaman</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h3 {
            margin: 2px 0;
            font-size: 14px;
            margin-top: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 8px 6px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>KOPERASI SIMPAN PINJAM SEKOLAH</h2>
        <h3>Alamat: Jl. Pendidikan No. 123, Kota Pendidikan</h3>
        <h3><u>LAPORAN PINJAMAN ANGGOTA</u></h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Alamat</th>
                <th>Besar Pinjaman</th>
                <th>Status Pinjaman</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($users as $user)
                @php $totalPinjamanUser = $user->pinjaman->count(); @endphp
                @foreach($user->pinjaman as $index => $pinjaman)
                                @php
                    $tanggungan = $pinjaman->tanggungan->first();
                    $totalPinjaman = $tanggungan->total_pinjaman ?? '-';
                    $statusPinjaman = $tanggungan->status_pinjaman ?? '-';
                                @endphp
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $totalPinjamanUser }}">{{ $no++ }}</td>
                                        <td rowspan="{{ $totalPinjamanUser }}" class="text-left">{{ $user->nama }}</td>
                                        <td rowspan="{{ $totalPinjamanUser }}">{{ $user->NIP }}</td>
                                        <td rowspan="{{ $totalPinjamanUser }}" class="text-left">{{ $user->alamat ?? '-' }}</td>
                                    @endif
                                    <td>{{ 'Rp. ' . number_format(intval($totalPinjaman), 0, ',', '.') }}</td>
                                    <td>{{ $statusPinjaman }}</td>
                                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>

</html>
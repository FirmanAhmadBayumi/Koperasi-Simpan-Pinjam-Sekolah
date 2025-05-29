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
        <h2 class="mb-3">KOPERASI SIMPAN PINJAM SEKOLAH</h2>
        <h3>{{ $profilSekolah->nama_sekolah }}</h3>
        <h3>{{ $profilSekolah->alamat_sekolah }}</h3>
        <h3><u>LAPORAN Simpanan ANGGOTA</u></h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Alamat</th>
                <th>Total Simpanan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($users as $user)
                @php
                    $totalSimpanan = $user->simpananPokok ? $user->simpananPokok->sum('total_simpanan') : 0;
                @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-left">{{ $user->nama }}</td>
                            <td>{{ $user->NIP }}</td>
                            <td class="text-left">{{ $user->alamat ?? '-' }}</td>
                            <td>{{ 'Rp.'.number_format($totalSimpanan, 0, ',', '.') }}</td>
                        </tr>
            @endforeach
        </tbody>
        </table>
</body>

</html>
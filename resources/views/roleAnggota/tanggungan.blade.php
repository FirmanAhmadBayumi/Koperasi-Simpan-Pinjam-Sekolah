<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="wrapper">
        <!-- Sidebar -->
        <x-sidebar></x-sidebar>

        <div class="main-panel">
            <!-- Navbar -->
        <x-main-header></x-main-header>

            <!-- Content -->
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Tanggungan</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Tanggungan Anggota</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Tanggungan Simpanan</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row2" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th>Iuran/Bulan</th>
                                                    <th style="text-align: center;">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transaksiPokok as $t)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->jatuh_tempo }}</td>
                                                        <td>Rp{{ number_format($t->simpananPokok->iuran, 0, ',', '.') }}</td>
                                                        <td id="aksi_{{ $t->id_transaksiPokok }}" style="text-align: center;">
                                                            @if ($t->keterangan == 'Lunas')
                                                                <button class="btn btn-dark" disabled>{{ $t->keterangan }}</button>
                                                            @else
                                                                <form class="form-update-status" action="{{ route('simpanan.update', $t->id_transaksiPokok) }}" 
                                                                    method="post" data-id="{{ $t->id_transaksiPokok }}" >
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Lunas">
                                                                    <button type="submit" class="btn btn-primary btn-setujui pay-buttonSimpanan" data-snap-token="{{ $t->snap_token }}">Bayar</button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Tanggungan Pinjaman</h4>
                                        @if ($cekPinjaman->count() > 1)
                                            <form class="form-update-status ms-auto" action="{{ route('pinjamanLunas.update') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="status" value="Lunas">
                                                <button type="submit" class="btn btn-warning btn-round btn-setujui pay-buttonLunas" data-snap-token="{{ $tanggungan->snap_tokenLunas }}">Bayar Lunas</button>
                                            </form>
                                        @else
                                            
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th>Iuran/Bulan</th>
                                                    <th style="text-align: center;">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transaksiPinjaman as $t)
                                                    <tr id="row_{{ $t->id_transaksiPinjaman }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->jatuh_tempo }}</td>
                                                        <td>Rp{{ number_format(ceil($t->tanggungan->iuran_perBulan), 0, ',', '.') }}</td>
                                                        <td id="aksi_{{ $t->id_transaksiPinjaman }}" style="text-align: center;">
                                                            @if ($t->keterangan != 'Lunas')
                                                                @php
                                                                    $prevTransaksi = $transaksiPinjaman->where('id_transaksiPinjaman', $t->id_transaksiPinjaman - 1)->first();
                                                                @endphp
                                                                @if ($loop->first || ($prevTransaksi && $prevTransaksi->keterangan == 'Lunas'))
                                                                    <form class="form-update-status" action="{{ route('pinjaman.update', $t->id_transaksiPinjaman) }}" 
                                                                        method="post" data-id="{{ $t->id_transaksiPinjaman }}" >
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="Lunas">
                                                                        <button type="submit" class="btn btn-primary btn-setujui pay-button" data-snap-token="{{ $t->snap_token }}">{{ $t->keterangan }}</button>
                                                                    </form>
                                                                @else
                                                                    <button class="btn btn-primary" disabled>{{ $t->keterangan }}</button>
                                                                @endif
                                                            @else
                                                                <button class="btn btn-dark" disabled>{{ $t->keterangan }}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <x-footer></x-footer>
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Tambahkan ini di bagian head HTML Anda -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>

    <!-- MidTrans -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        $("#add-row").DataTable({
            pageLength: 25,
        });

        $("#add-row2").DataTable({
            pageLength: 10,
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.pay-buttonLunas').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var snapToken = button.getAttribute('data-snap-token');
                    var form = button.closest('form');

                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            Swal.fire({
                                title: "Pembayaran Berhasil!",
                                text: "Terima kasih telah membayar tepat waktu.",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        },
                        onPending: function(result) {
                            Swal.fire({
                                title: "Pembayaran Tertunda",
                                icon: "info"
                            });
                        },
                        onError: function(result) {
                            Swal.fire({
                                title: "Pembayaran Gagal",
                                text: "Terjadi kesalahan saat memproses pembayaran.",
                                icon: "error"
                            });
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.pay-buttonSimpanan').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var snapToken = button.getAttribute('data-snap-token');
                    var form = button.closest('form');

                    snap.pay(snapToken, {
                        // Optional
                        onSuccess: function(result) {
                            Swal.fire({
                                title: "Pembayaran Berhasil!",
                                text: "Terima kasih telah membayar tepat waktu.",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        },
                        // Optional
                        onPending: function(result) {
                            Swal.fire({
                                title: "Pembayaran Tertunda",
                                icon: "info"
                            });
                        },
                        // Optional
                        onError: function(result) {
                            Swal.fire({
                                title: "Pembayaran Gagal",
                                text: "Terjadi kesalahan saat memproses pembayaran.",
                                icon: "error"
                            });
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.pay-button').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var snapToken = button.getAttribute('data-snap-token');
                    var form = button.closest('form');

                    snap.pay(snapToken, {
                        // Optional
                        onSuccess: function(result) {
                            Swal.fire({
                                title: "Pembayaran Berhasil!",
                                text: "Terima kasih telah membayar tepat waktu.",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        },
                        // Optional
                        onPending: function(result) {
                            Swal.fire({
                                title: "Pembayaran Tertunda",
                                icon: "info"
                            });
                        },
                        // Optional
                        onError: function(result) {
                            Swal.fire({
                                title: "Pembayaran Gagal",
                                text: "Terjadi kesalahan saat memproses pembayaran.",
                                icon: "error"
                            });
                        }
                    });
                });
            });
        });

    </script>

</x-layout>
<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="wrapper">
        <!-- Sidebar -->
        <x-sidebar-admin></x-sidebar-admin>

        <div class="main-panel">
            <!-- Navbar -->
            <x-main-header-admin></x-main-header>

                <!-- Content -->
                <div class="container">
                    <div class="page-inner">
                        <div class="page-header">
                            <h3 class="fw-bold mb-3">Laporan Pinjaman</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('laporan.pinjaman.eksporPDF') }}" class="btn btn-danger mb-3 ms-auto">
                                                <i class="fa fa-file-pdf"></i> Ekspor PDF 
                                            </a>
                                        </div>
                                    </div>                                    
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama</th>
                                                        <th>NIP</th>
                                                        <th>Status Pinjaman</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $user->nama }}</td>
                                                            <td>{{ $user->NIP }}</td>
                                                            <td>
                                                                @php
    $pinjamanTerakhir = $user->pinjaman()->latest()->first();
    $statusPinjaman = optional($pinjamanTerakhir->tanggungan->first())->status_pinjaman;
                                                                @endphp
                                                                @if ($statusPinjaman === 'Belum Lunas')
                                                                    <button class="btn btn-danger" disabled>Belum Lunas</button>
                                                                @else
                                                                    <button class="btn btn-success" disabled>Lunas</button>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-warning btn-detail-transaksi"
                                                                    data-nama="{{ $user->nama }}"
                                                                    data-nip="{{ $user->NIP }}"
                                                                    data-id="{{ $user->id_user }}">
                                                                    Detail Transaksi
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="detailTransaksiPinjaman" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title w-100 text-center fs-2">Detail Transaksi Pinjaman</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-between w-100 px-3 mt-3">
                                        <div class="me-4">
                                            <span class="fw-semibold fs-5">Nama:</span>
                                            <span id="detailNama" class="fs-5"></span>
                                        </div>
                                        <div>
                                            <span class="fw-semibold fs-5">NIP:</span>
                                            <span id="detailNIP" class="fs-5"></span>
                                        </div>
                                    </div>
                                    <div class="modal-body mt-5">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Total Pinjaman</th>
                                                        <th>Iuran/Bulan</th>
                                                        <th>Jatuh Tempo</th>
                                                        <th>Tanggal Pembayaran</th>
                                                        <th>Status Transaksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detailTransaksiBody">
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

    <!-- Core JS Files -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Demo Settings -->
    <script src="../assets/js/setting-demo2.js"></script>

    <!-- Custom Script -->
    <script>
        $(document).ready(function () {
            $("#add-row").DataTable({
                pageLength: 25,
            });

            // Fungsi format Rupiah
            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            };

            // Tombol detail transaksi klik handler
            $('.btn-detail-transaksi').on('click', function (e) {
                e.preventDefault();

                const nama = $(this).data('nama');
                const nip = $(this).data('nip');
                const id = $(this).data('id');

                $('#detailNama').text(nama);
                $('#detailNIP').text(nip);

                // Clear table body
                $('#detailTransaksiBody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

                // Show modal
                $('#detailTransaksiPinjaman').modal('show');

                // AJAX call to get detail data
                $.ajax({
                    url: `transaksiPinjaman/detail/${id}`,
                    type: 'GET',
                    success: function (data) {
                        let rows = '';
                        let rowIndex = 1;

                        if (data.length > 0) {
                            data.forEach((tanggungan) => {
                                const transaksi = tanggungan.transaksi;
                                const rowspan = transaksi.length;

                                transaksi.forEach((trx, index) => {
                                    rows += '<tr>';
                                    rows += `<td>${rowIndex++}</td>`;

                                    // Tampilkan total_pinjaman dan angsuran hanya di baris pertama
                                    if (index === 0) {
                                        const totalPinjaman = tanggungan.total_pinjaman != null ? formatRupiah(tanggungan.total_pinjaman) : '-';
                                        const angsuran = tanggungan.angsuran != null ? formatRupiah(tanggungan.angsuran) : '-';

                                        rows += `<td rowspan="${rowspan}">${totalPinjaman}</td>`;
                                        rows += `<td rowspan="${rowspan}">${angsuran}</td>`;
                                    }

                                    rows += `<td>${trx.jatuh_tempo}</td>`;
                                    rows += `<td>${trx.tanggal_pembayaran}</td>`;
                                    rows += `<td>${trx.status_transaksi}</td>`;
                                    rows += '</tr>';
                                });
                            });
                        } else {
                            rows = `<tr><td colspan="6" class="text-center">Tidak ada data transaksi.</td></tr>`;
                        }

                        $('#detailTransaksiBody').html(rows);
                    },
                    error: function () {
                        $('#detailTransaksiBody').html('<tr><td colspan="6" class="text-danger text-center">Gagal memuat data.</td></tr>');
                    }
                });
            });
        });
    </script>
    
</x-layout>
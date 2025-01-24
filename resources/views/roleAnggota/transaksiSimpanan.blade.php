<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

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
                        <h3 class="fw-bold mb-3">Data Transaksi Simpanan</h3>
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
                                <a href="#">Transaksi Simpanan</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ 'export-transaksi-simpananAnggota' }}" class="btn btn-success btn-round ms-auto">
                                            <i class="fa fa-file-excel"></i> Ekspor Excel
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th>Tanggal Pembayaran</th>
                                                    <th>Status Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transaksiPokok as $tk)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>Rp{{ number_format($tk->simpananPokok->iuran, 0, ',', '.') }}</td>
                                                    <td>{{ $tk->jatuh_tempo }}</td>
                                                    <td>{{ $tk->tanggal_pembayaran }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <button
                                                                class="btn btn-{{ $tk->keterangan == 'Belum Lunas' ? 'danger' : 'success' }}"
                                                                disabled>
                                                                {{ $tk->keterangan }}
                                                                </button>
                                                        </div>
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

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function () {
            $("#basic-datatables").DataTable({});

            // Add Row
            $("#add-row").DataTable({
                pageLength: 25,
            });
        });
    </script>
</x-layout>
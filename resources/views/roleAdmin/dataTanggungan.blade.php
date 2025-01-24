<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

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
                                    <a href="#">Data Tanggungan</a>
                                </li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ 'export-tanggungan' }}" class="btn btn-success btn-round ms-auto">
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
                                                        <th>Nama</th>
                                                        <th>Besar Pinjaman</th>
                                                        <th>Tenor</th>
                                                        <th>Bunga Pinjaman</th>
                                                        <th>Total Pinjaman</th>
                                                        <th>Iuran/Bulan</th>
                                                        <th>Sisa Pinjaman</th>
                                                        <th>Status Pinjaman</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tanggungan as $t)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $t->pinjaman->user->nama }}</td>
                                                            <td>{{ 'Rp. ' . number_format(ceil($t->pinjaman->besar_pinjaman), 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ $t->pinjaman->tenor_pinjaman }}</td>
                                                            <td>{{ 'Rp. ' . number_format(ceil($t->bunga_pinjaman), 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ 'Rp. ' . number_format(ceil($t->total_pinjaman), 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ 'Rp. ' . number_format(ceil($t->iuran_perBulan), 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ 'Rp. ' . number_format(ceil($t->sisa_pinjaman), 0, ',', '.') }}
                                                            </td>
                                                            <td id="aksi_{{ $t->sisa_pinjaman }}">
                                                                @if ($t->sisa_pinjaman > 0)
                                                                    <div class="d-flex justify-content-between">
                                                                        <button class="btn btn-danger" disabled>Belum
                                                                            Lunas</button>
                                                                    </div>
                                                                @else
                                                                    <div class="d-flex justify-content-beetwen">
                                                                        <button class="btn btn-success" disabled>Lunas</button>
                                                                    </div>
                                                                @endif
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
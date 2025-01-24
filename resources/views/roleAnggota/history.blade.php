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
                        <h3 class="fw-bold mb-3">Riwayat</h3>
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
                                <a href="#">Riwayat Pinjaman</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Besar Pinjaman</th>
                                                    <th>Bunga</th>
                                                    <th>Total Pinjaman</th>
                                                    <th>Iuran/Bulan</th>
                                                    <th>Sisa Pinjaman</th>
                                                    <th>Status Pinjaman</th>
                                                    <th style="width: 10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($history as $t)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->pinjaman->tgl_pengajuan }}</td>
                                                        <td>Rp{{ number_format($t->pinjaman->besar_pinjaman, 0, ',', '.') }}</td>
                                                        <td>Rp{{ number_format(ceil($t->bunga_pinjaman), 0, ',', '.') }}</td>
                                                        <td>Rp{{ number_format(ceil($t->total_pinjaman), 0, ',', '.') }}</td>
                                                        <td>Rp{{ number_format(ceil($t->iuran_perBulan), 0, ',', '.') }}</td>
                                                        <td>Rp{{ number_format(ceil($t->sisa_pinjaman), 0, ',', '.') }}</td>
                                                        <td>
                                                            @if ($t->sisa_pinjaman == 0)
                                                                Lunas
                                                            @else
                                                                {{ $t->status_pinjaman }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($t->status_pinjaman == 'Lunas')
                                                                <button class="btn btn-dark" disabled>Bayar</button>
                                                            @else
                                                                <button class="btn btn-primary" onclick="window.location.href='{{ route('tanggungan.view') }}'">Bayar</button>
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

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function() {
            // Add Row
            $("#add-row").DataTable({
                pageLength: 25,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';
        });
    </script>
</x-layout>
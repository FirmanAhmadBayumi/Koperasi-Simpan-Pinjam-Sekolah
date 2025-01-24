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
                        <h3 class="fw-bold mb-3">Data Transaksi Pinjaman</h3>
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
                                <a href="#">Transaksi Pinjaman</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ 'export-transaksi-pinjaman' }}" class="btn btn-success btn-round ms-auto">
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
                                                    <th>Keterangan</th>
                                                    <th>Iuran Cicilan</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th>Tanggal Pembayaran</th>
                                                    <th>Status Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $loanInstallmentCounts = []; // To track the installment counts for each loan
                                                @endphp
                                                @foreach ( $transaksiPinjaman as $tp)
                                                @php
                                                        $loanId = $tp->tanggungan->pinjaman->id;
                                                        $tenor = $tp->tanggungan->pinjaman->tenor_pinjaman;

                                                        // Initialize the count for this loan if not already set
                                                        if (!isset($loanInstallmentCounts[$loanId])) {
                                                            $loanInstallmentCounts[$loanId] = 0;
                                                        }

                                                        // Increment the installment count
                                                        $loanInstallmentCounts[$loanId]++;

                                                        // Calculate the installment number
                                                        $installmentNumber = $loanInstallmentCounts[$loanId] % $tenor;
                                                        if ($installmentNumber == 0) {
                                                            $installmentNumber = $tenor;
                                                        }

                                                        // Check if the tenor has changed and reset the count
                                                        if ($loop->first || $transaksiPinjaman[$loop->index - 1]->tanggungan->pinjaman->tenor_pinjaman != $tenor) {
                                                            $loanInstallmentCounts[$loanId] = 1;
                                                            $installmentNumber = 1;
                                                        }
                                                    @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $tp->tanggungan->pinjaman->user->nama }}</td>
                                                    <td>Cicilan ke-{{ $installmentNumber }}</td>
                                                    <td>{{ 'Rp. ' . number_format(ceil($tp->tanggungan->iuran_perBulan), 0, ',', '.') }}</td>
                                                    <td>{{ $tp->jatuh_tempo }}</td>
                                                    <td>{{ $tp->tanggal_pembayaran }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <button
                                                                class="btn btn-{{ $tp->keterangan == 'Lunas' ? 'success' : 'danger' }}"
                                                                disabled>
                                                                {{ str_replace('Bayar ', '', $tp->keterangan) }}                                                          
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
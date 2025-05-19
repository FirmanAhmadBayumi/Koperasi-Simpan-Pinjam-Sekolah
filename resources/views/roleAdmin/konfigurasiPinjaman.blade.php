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
                            <h3 class="fw-bold mb-3">Konfigurasi Pinjaman</h3>
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
                                    <a href="#">Konfigurasi Pinjaman</a>
                                </li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                                data-bs-target="#konfigurasiPinjamanModal">
                                                <i class="fa fa-plus"></i>
                                                Konfigurasi Pinjaman
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Bunga Pinjaman</th>
                                                        <th>Maksimal Pinjaman</th>
                                                        <th>Maksimal Tenor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>{{ $konfigurasiPinjaman->bunga_pinjaman }}</td>
                                                    <td>{{ $konfigurasiPinjaman->maks_pinjaman }}</td>
                                                    <td>{{ $konfigurasiPinjaman->maks_tenor }}</td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="konfigurasiPinjamanModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="form-konfigurasi" method="POST" action="{{ route('konfigPinjaman.update') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-group-default">
                                                <label>Bunga Pinjaman (%)</label>
                                                <input name="bunga_pinjaman" id="bungaPinjaman" type="text" class="form-control"
                                                value="{{ old('bunga_pinjaman', $konfigurasiPinjaman->bunga_pinjaman) }}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Maksimal Pinjaman (RP)</label>
                                                <input name="maks_pinjaman" id="maksPinjaman" type="text" class="form-control" min="0"
                                                value="{{ old('maks_pinjaman', $konfigurasiPinjaman->maks_pinjaman) }}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Maksimal Tenor (/Bulan)</label>
                                                <input name="maks_tenor" id="maksTenor" type="text" class="form-control" min="0"
                                                value="{{ old('maks_tenor', $konfigurasiPinjaman->maks_tenor) }}"/>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Footer -->
        <x-footer></x-footer>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Inisialisasi DataTable
            $("#basic-datatables").DataTable({});
            $("#add-row").DataTable({ pageLength: 1 });

            $('#form-konfigurasi').on('submit', function (e) {
                e.preventDefault();

                // const bungaInput = $('#bungaPinjaman').val();
                // const decimalRegex = /^(0(\.\d{1,2})?|1(\.0{1,2})?)$/;
                // const bungaValue = parseFloat(bungaInput);

                // if (!decimalRegex.test(bungaInput) || bungaValue < 0 || bungaValue > 1) {
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Format Bunga Salah',
                //         html: 'Bunga pinjaman harus dalam format desimal (0.00 - 1.00).<br>Contoh: <b>0.05</b> (5%) atau <b>0.15</b> (15%).',
                //         confirmButtonText: 'Mengerti'
                //     });
                //     return;
                // }
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Konfigurasi pinjaman berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#konfigurasiPinjamanModal').modal('hide');
                            location.reload(); // Optional: reload page if needed
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Validation error
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (let field in errors) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                html: errorMessages,
                                confirmButtonText: 'Mengerti'
                            });
                        } else {
                            // Other errors
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat menyimpan data',
                                confirmButtonText: 'Mengerti'
                            });
                        }
                    }
                });
            });
        });
    </script>
</x-layout>
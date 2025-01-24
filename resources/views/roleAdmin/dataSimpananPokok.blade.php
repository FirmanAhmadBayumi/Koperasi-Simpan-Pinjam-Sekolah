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
                        <h3 class="fw-bold mb-3">Simpanan Pokok</h3>
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
                                <a href="#">Data Simpanan Pokok</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <button id="create-all-transactions" class="btn btn-primary btn-round">Buat
                                        Transaksi Simpanan</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>Iuran</th>
                                                    <th>Total Simpanan</th>
                                                    <th>Status Simpanan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($simpanan as $simp)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $simp->user->nama }}</td>
                                                        <td>{{ 'Rp. ' . number_format($simp->iuran, 0, ',', '.') }}</td>
                                                        <td>{{ 'Rp. ' . number_format($simp->total_simpanan, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-between">
                                                                <button
                                                                    class="btn btn-{{ $simp->status_simpanan == 'Belum Lunas' ? 'danger' : 'success' }}"
                                                                    disabled>
                                                                    {{ $simp->status_simpanan }}
                                                                </button>
                                                            </div>
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
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updateTransaksi(id, keterangan) {
            $.ajax({
                url: '/transaksi/' + id + '/update',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    keterangan: keterangan
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Keterangan berhasil diperbarui',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(response) {
                    Swal.fire({
                        title: "Gagal memperbarui keterangan",
                        text: response.responseJSON.message ||
                            'Terjadi kesalahan saat memproses permintaan.',
                        icon: 'error'
                    });
                }
            });
        }

        $(document).ready(function() {
            $("#add-row").DataTable({
                pageLength: 25,
            });

            // Fungsi untuk memeriksa status simpanan
            function checkButtonStatus() {
                $.ajax({
                    url: '/checkSimpananStatus',
                    method: 'GET',
                    success: function(response) {
                        if (response.disableButton) {
                            $('#create-all-transactions').prop('disabled', true);
                        } else {
                            $('#create-all-transactions').prop('disabled', false);
                        }
                    }
                });
            }

            // Pengecekan awal saat halaman dimuat
            checkButtonStatus();

            $('#create-all-transactions').on('click', function() {
                // Tampilkan loading saat tombol diklik
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu beberapa saat.',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/buatTransaksiSimpanan',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Success:', response); // Debug log
                        Swal.close(); // Sembunyikan loading
                        Swal.fire({
                            title: 'Data Transaksi Simpanan Pokok Berhasil Dibuat',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(response) {
                        console.log('Error:', response); // Debug log
                        Swal.close(); // Sembunyikan loading
                        Swal.fire({
                            title: "Gagal memperbarui status",
                            text: response.responseJSON.message ||
                                'Terjadi kesalahan saat memproses permintaan.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
</x-layout>

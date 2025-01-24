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
                            <h3 class="fw-bold mb-3">Pinjaman</h3>
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
                                    <a href="#">Data Pinjaman</a>
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
                                                        <th>Nama</th>
                                                        <th>Tanggal Pengajuan</th>
                                                        <th>Besar Pinjaman</th>
                                                        <th>Tenor Pinjaman</th>
                                                        <th style="width: 10%">Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($pinjaman as $p)
                                                        <tr id="row_{{ $p->id_pinjaman }}">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $p->user->nama }}</td>
                                                            <td>{{ $p->tgl_pengajuan }}</td>
                                                            <td>{{ 'Rp. ' . number_format($p->besar_pinjaman, 0, ',', '.') }}
                                                            </td>
                                                            <td>{{ $p->tenor_pinjaman }}x</td>
                                                            <td id="aksi_{{ $p->id_pinjaman }}">
                                                                @if ($p->keterangan == 'Diproses')
                                                                    <div class="d-flex justify-content-between">
                                                                        <form class="form-update-status"
                                                                            action="{{ route('pinjaman.updateStatus', $p->id_pinjaman) }}"
                                                                            method="POST"
                                                                            data-id="{{ $p->id_pinjaman }}">
                                                                            @csrf
                                                                            <input type="hidden" name="status"
                                                                                value="Disetujui">
                                                                            <button type="submit"
                                                                                class="btn btn-success btn-setujui me-3">Disetujui</button>
                                                                        </form>
                                                                        <form class="form-update-status"
                                                                            action="{{ route('pinjaman.updateStatus', $p->id_pinjaman) }}"
                                                                            method="POST"
                                                                            data-id="{{ $p->id_pinjaman }}">
                                                                            @csrf
                                                                            <input type="hidden" name="status"
                                                                                value="Ditolak">
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-tolak">Ditolak</button>
                                                                        </form>
                                                                    </div>
                                                                @elseif ($p->keterangan == 'Disetujui')
                                                                    <button class="btn btn-success"
                                                                        disabled>Disetujui</button>
                                                                @elseif ($p->keterangan == 'Ditolak')
                                                                    <button class="btn btn-danger"
                                                                        disabled>Ditolak</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            // DataTable initialization
            $("#basic-datatables").DataTable({});

            $("#add-row").DataTable({
                pageLength: 25,
            });

            // Handle form submission and update button status
            $(document).on('submit', '.form-update-status', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');
                var status = form.find('input[name="status"]').val();

                if (status === 'Disetujui') {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu beberapa saat.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                }

                // Disable the submit button to prevent multiple clicks
                form.find('button[type="submit"]').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        Swal.close(); // Close the loading alert

                        // Check the status to determine the title and icon
                        let title, icon;
                        if (status === "Disetujui") {
                            title = "Pinjaman disetujui";
                            icon = 'success';
                        } else {
                            title = "Pinjaman ditolak";
                            icon = 'error'; // Use 'error' icon for rejection
                        }

                        Swal.fire({
                            title: title,
                            icon: icon,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(response) {
                        Swal.close(); // Close the loading alert
                        Swal.fire({
                            title: "Gagal memperbarui status",
                            text: response.responseJSON.message ||
                                'Terjadi kesalahan saat memproses permintaan.',
                            icon: 'error'
                        });
                        form.find('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</x-layout>

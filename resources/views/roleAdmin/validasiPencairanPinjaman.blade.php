<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="wrapper">
        <!-- Sidebar -->
        <x-sidebar-admin></x-sidebar-admin>

        <div class="main-panel">
            <!-- Navbar -->
            <x-main-header></x-main-header>

            <!-- Content -->
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Pengajuan</h3>
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
                                <a href="#">Pengajuan Pinjaman</a>
                            </li>
                        </ul>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-warning">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal Pengajuan Pencairan</th>
                                                    <th>Besar Pinjaman</th>
                                                    <th>Tenor Pinjaman</th>
                                                    <th>Status Pinjaman</th>
                                                    <th>Metode Pencairan</th>
                                                    <th>Validasi Pencairan Pinjaman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>2024-12-11</td>
                                                    <td>Rp.5.000.0000</td>
                                                    <td>10</td>
                                                    <td><button class="btn btn-success" disabled>Disetujui</button></td>
                                                    <td>Transfer Rekening(BCA 7268762829)</td>
                                                    <td>
                                                        {{-- <button class="btn btn-success" disabled>Disetujui</button>
                                                        --}}
                                                        <button class="btn btn-danger" disabled>Ditolak</button>
                                                    </td>
                                                </tr>
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

    <!-- Sweet Alert -->
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>

    <script src="../assets/js/setting-demo2.js"></script>
    <script>
        var SweetAlert2Demo = (function () {
            var initDemos = function () {

                $("#addBtn").click(function (e) {
                    e.preventDefault(); // Prevent form submission
                    var form = $('#form-pengajuan');
                    var formData = form.serialize();

                    var besarPinjaman = parseInt($('#addBesar').val());
                    var tenorPinjaman = parseInt($('#addTenor').val());

                    if (besarPinjaman > 100000000) {
                        swal({
                            title: "Error!",
                            text: "Besar pinjaman tidak boleh lebih dari 100 juta.",
                            icon: "error",
                            buttons: {
                                confirm: {
                                    className: "btn btn-danger",
                                },
                            },
                        });
                        return;
                    }

                    if (tenorPinjaman > 50) {
                        swal({
                            title: "Error!",
                            text: "Tenor pinjaman tidak boleh lebih dari 50 bulan.",
                            icon: "error",
                            buttons: {
                                confirm: {
                                    className: "btn btn-danger",
                                },
                            },
                        });
                        return;
                    }

                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: formData,
                        success: function (response) {
                            if (response.status === 'warning') {
                                swal({
                                    title: "Peringatan!",
                                    content: $('<div>').html(response.message)[0],
                                    icon: "warning",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-warning",
                                        },
                                    },
                                });
                            } else {
                                swal({
                                    title: "Pengajuan Diproses!",
                                    text: "Cek secara berkala pengajuan peminjaman Anda.",
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-success",
                                        },
                                    },
                                }).then((willReload) => {
                                    if (willReload) {
                                        location.reload();
                                    }
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: "Error!",
                                text: "Terjadi kesalahan, silakan coba lagi.",
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-danger",
                                    },
                                },
                            });
                        }
                    });
                });
            };
            return {
                //== Init
                init: function () {
                    initDemos();
                },
            };
        })();

        //== Class Initialization
        jQuery(document).ready(function () {
            SweetAlert2Demo.init();
        });

        $(document).ready(function () {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                                .appendTo($(column.footer()).empty())
                                .on("change", function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });

            $("#add-row").DataTable({
                pageLength: 25,
                columns: [{
                    title: "No."
                },
                {
                    title: "Tanggal Pengajuan Pencairan"
                },
                {
                    title: "Besar Pinjaman"
                },
                {
                    title: "Tenor Pinjaman"
                },
                {
                    title: "Status Pinjaman",
                },
                {
                    title: "Metode Pencairan",
                },
                {
                    title: "Validasi Pencairan Pinjaman",
                    orderable: false
                },
                ]
            });

            $('#addRowModal').on('show.bs.modal', function () {
                var currentDateTime = new Date();
                currentDateTime.setHours(currentDateTime.getHours() + 7); // Adjust to WIB (UTC+7)
                var formattedDateTime = currentDateTime.toISOString().slice(0, 19).replace('T', ' ');
                $('#addTgl').val(formattedDateTime);
            });
        });

        function toggleMetode() {
            const transferDiv = document.getElementById('transferRekeningDiv');
            const tunaiDiv = document.getElementById('tunaiDiv');

            if (document.getElementById('metodeTransfer').checked) {
                transferDiv.style.display = 'block';
                tunaiDiv.style.display = 'none';
            } else if (document.getElementById('metodeTunai').checked) {
                transferDiv.style.display = 'none';
                tunaiDiv.style.display = 'block';
            }
        }
    </script>
</x-layout>
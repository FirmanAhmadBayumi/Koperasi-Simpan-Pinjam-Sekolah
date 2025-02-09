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
                        <h3 class="fw-bold mb-3">Pencairan Pinjaman</h3>
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
                                <a href="#">Pengajuan Pencairan Pinjaman</a>
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
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                            data-bs-target="#addRowModal"
                                            {{ $statusTombol == 'disabled' ? 'disabled' : '' }}>
                                            <i class="fa fa-plus"></i>
                                            Ajukan Pencairan
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Modal -->
                                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">
                                                        <span class="fw-mediumbold"> Pengajuan Pencairan Pinjaman</span>
                                                    </h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small">
                                                        Ajukan Pencairan Pinjaman
                                                    </p>
                                                    <form id="form-pengajuan" method="POST"
                                                        action="{{ route('pencairanPinjaman.create') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Tanggal Pengajuan</label>
                                                                    <input name="tgl_pengajuan" id="addTgl"
                                                                        type="text" class="form-control" readonly />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 pe-0">
                                                                <div class="form-group form-group-default">
                                                                    <label>Besar Pinjaman</label>
                                                                    <input name="besar_pinjaman" id="addBesar"
                                                                        type="text" class="form-control"
                                                                        value="{{ $pinjamanAktif->besar_pinjaman ?? '' }}"
                                                                        readonly />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 pe-0">
                                                                <div class="form-group form-group-default">
                                                                    <label>Tenor Pinjaman</label>
                                                                    <input name="besar_pinjaman" id="addBesar"
                                                                        type="text" class="form-control"
                                                                        value="{{ $pinjamanAktif->tenor_pinjaman ?? '' }}"
                                                                        readonly />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Metode Pencairan</label>
                                                                <div>
                                                                    <input type="radio" id="metodeTransfer"
                                                                        name="metode_pengiriman_pinjaman"
                                                                        value="Transfer Rekening"
                                                                        onchange="toggleMetode()">
                                                                    Transfer Rekening
                                                                    <input type="radio" id="metodeTunai"
                                                                        name="metode_pengiriman_pinjaman" value="Tunai"
                                                                        onchange="toggleMetode()">
                                                                    Tunai
                                                                </div>
                                                            </div>
                                                            <div id="transferRekeningDiv" style="display: none;">
                                                                <span class="fw-mediumbold"> Pastikan Nomor Rekening
                                                                    BENAR!</span>
                                                                <div class="form-group">
                                                                    <label>Nomor Rekening</label>
                                                                    <input type="text" name="nomor_rekening"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Bank</label>
                                                                    <input type="text" name="nama_bank"
                                                                        class="form-control">
                                                                </div>
                                                            </div>

                                                            <div id="tunaiDiv" style="display: none;">
                                                                <div class="form-group">
                                                                    <span class="fw-mediumbold">Silahkan Mendatangi
                                                                        Pihak Koperasi!</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-primary"
                                                                id="addBtn">
                                                                Simpan
                                                            </button>

                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">
                                                                Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal Pengajuan Pencairan</th>
                                                    <th>Besar Pinjaman</th>
                                                    <th>Tenor Pinjaman</th>
                                                    <th>Metode Pencairan</th>
                                                    <th>Nomor Rekening</th>
                                                    <th>Nama Bank</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($riwayatPencairanPinjaman as $r)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $r->tgl_pengajuan }}</td>
                                                        <td>{{ $r->pinjaman->besar_pinjaman }}</td>
                                                        <td>{{ $r->pinjaman->tenor_pinjaman }}</td>
                                                        <td>{{ $r->metode_pengiriman_pinjaman }}</td>
                                                        <td>{{ $r->nomor_rekening }}</td>
                                                        <td>{{ $r->nama_bank }}</td>
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
        var SweetAlert2Demo = (function() {
            var initDemos = function() {
                $("#addBtn").click(function(e) {
                    e.preventDefault(); // Mencegah pengiriman form langsung
                    var form = $('#form-pengajuan');
                    var formData = form.serialize();
                    var metodePengiriman = $('input[name="metode_pengiriman_pinjaman"]:checked').val();
                    var nomorRekening = $('input[name="nomor_rekening"]').val();
                    var namaBank = $('input[name="nama_bank"]').val();

                    // Validasi input di sisi frontend
                    if (!metodePengiriman) {
                        swal({
                            title: "Error!",
                            text: "Silakan pilih metode pencairan.",
                            icon: "error",
                            buttons: {
                                confirm: {
                                    className: "btn btn-danger"
                                },
                            },
                        });
                        return;
                    }

                    if (metodePengiriman === "Transfer Rekening") {
                        if (!nomorRekening || isNaN(nomorRekening)) {
                            swal({
                                title: "Error!",
                                text: "Nomor rekening harus diisi dan berupa angka.",
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-danger"
                                    },
                                },
                            });
                            return;
                        }

                        if (!namaBank.trim()) {
                            swal({
                                title: "Error!",
                                text: "Nama bank harus diisi.",
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-danger"
                                    },
                                },
                            });
                            return;
                        }
                    }

                    // Kirim data via AJAX
                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: formData,
                        success: function(response) {
                            if (response.status === 'warning') {
                                swal({
                                    title: "Peringatan!",
                                    text: response.message,
                                    icon: "warning",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-warning"
                                        },
                                    },
                                });
                            } else {
                                swal({
                                    title: "Pengajuan Pencairan Pinjaman Berhasil",
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-success"
                                        },
                                    },
                                }).then((willReload) => {
                                    if (willReload) {
                                        location.reload();
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "Terjadi kesalahan, silakan coba lagi.";

                            if (errors) {
                                errorMessage = Object.values(errors).map(msg => msg.join(
                                    '\n')).join('\n');
                            }

                            swal({
                                title: "Error!",
                                text: errorMessage,
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-danger"
                                    },
                                },
                            });
                        }
                    });
                });
            };

            return {
                init: function() {
                    initDemos();
                },
            };
        })();

        jQuery(document).ready(function() {
            SweetAlert2Demo.init();
        });

        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
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
                        title: "Metode Pencairan",
                    },
                    {
                        title: "Nomor Rekening",
                    },
                    {
                        title: "Nama Bank",
                        orderable: false
                    },
                ]
            });

            $('#addRowModal').on('show.bs.modal', function() {
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

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
                            <h3 class="fw-bold mb-3">Anggota</h3>
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
                                    <a href="#">Data Anggota</a>
                                </li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-primary btn-round justify-content-start"
                                                data-bs-toggle="modal" data-bs-target="#addAnggotaModal">
                                                <i class="fa fa-plus"></i>
                                                Tambah Anggota
                                            </button>
                                            <a href="{{ 'export-anggota' }}" class="btn btn-success btn-round ms-auto">
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
                                                        <th>E-mail</th>
                                                        <th>NIP</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Alamat</th>
                                                        <th>No.Telpon</th>
                                                        <th>SHU</th>
                                                        <th style="width: 10%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($users as $user)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $user->nama }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->NIP }}</td>
                                                            <td>{{ $user->jenis_kelamin }}</td>
                                                            <td>{{ $user->alamat }}</td>
                                                            <td>{{ $user->no_tlp }}</td>
                                                            <td>{{ $user->shu }}</td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <a href="#"
                                                                        class="btn btn-link btn-primary edit-user"
                                                                        data-id="{{ $user->id_user }}"
                                                                        data-nama="{{ $user->nama }}"
                                                                        data-email="{{ $user->email }}"
                                                                        data-nip="{{ $user->NIP }}"
                                                                        data-jenis_kelamin="{{ $user->jenis_kelamin }}"
                                                                        data-alamat="{{ $user->alamat }}"
                                                                        data-no_tlp="{{ $user->no_tlp }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editUserModal">
                                                                        <i class="fas fa-user-edit"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('dataAnggota.delete', ['id_user' => $user->id_user]) }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" data-bs-toggle="modal"
                                                                            data-bs-target="#hapusUserModal"
                                                                            class="btn btn-link btn-danger alert_notif">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
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

                <!-- Modal Tambah Anggota -->
                <div class="modal fade" id="addAnggotaModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold">Tambah</span>
                                    <span class="fw-light">Anggota</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="form-tambahanggota" method="POST" action="{{ route('register.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-group-default">
                                                <label>Nama</label>
                                                <input name="nama" id="add-nama" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Role</label>
                                                <select class="form-select" name="usertype" id="add-usertype">
                                                    <option value="user">Anggota</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Email</label>
                                                <input name="email" id="add-email" type="email"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>NIP</label>
                                                <input name="NIP" id="add-NIP" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-select" name="jenis_kelamin"
                                                    id="add-jenis_kelamin">
                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>NO. HP</label>
                                                <input name="no_tlp" id="add-no_tlp" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label>Alamat</label>
                                                <textarea name="alamat" id="add-alamat" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Password</label>
                                                <input name="password" id="add-password" type="password"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Konfirmasi Password</label>
                                                <input name="password_confirmation" id="password_confirmation"
                                                    type="password" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary"
                                            id="addAnggota">Tambah</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal Edit Anggota-->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold">Edit</span>
                                    <span class="fw-light">Anggota</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="form-edit" method="POST" action="">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label>Nama</label>
                                                <input name="nama" id="nama" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label>Email</label>
                                                <input name="email" id="email" type="email"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>NIP</label>
                                                <input name="NIP" id="NIP" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-select" name="jenis_kelamin" id="jenis_kelamin">
                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>NO. HP</label>
                                                <input name="no_tlp" id="no_tlp" type="text"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label>Alamat</label>
                                                <textarea name="alamat" id="alamat" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary" id="addBtn">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                    </div>
                                </form>
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

    <!-- jQuery Sparkline -->
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize datatable
        $('#add-row').DataTable({
            "pageLength": 25,
        });

        // Handle add button click
        $('button[data-bs-target="#addUserModal"]').on('click', function(e) {
            e.preventDefault(); // Mencegah tindakan default dari klik
            $('#addUserModal').modal('show');
        });

        // Handle add anggota form submit
        $('#form-tambahanggota').on('submit', function(e) {
                e.preventDefault(); // Mencegah tindakan default dari klik
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Anggota Berhasil Ditambahkan",
                                icon: "success",
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location
                                        .reload(); // Reload the page to reflect the changes
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal Menambahkan Anggota",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            title: "Gagal Menambahkan Anggota",
                            text: response.responseJSON.message,
                            icon: "error"
                        });
                    }
                });
            });

        // Handle edit button click
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            var id_user = button.data('id');
            var nama = button.data('nama');
            var email = button.data('email');
            var nip = button.data('nip');
            var jenis_kelamin = button.data('jenis_kelamin');
            var alamat = button.data('alamat');
            var no_tlp = button.data('no_tlp');

            modal.find('.modal-body #nama').val(nama);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #NIP').val(nip);
            modal.find('.modal-body #jenis_kelamin').val(jenis_kelamin);
            modal.find('.modal-body #alamat').val(alamat);
            modal.find('.modal-body #no_tlp').val(no_tlp);

            $('#form-edit').attr('action', '/dataAnggota/update/' + id_user);

            // Menyimpan data asli di form untuk perbandingan
            var form = $('#form-edit');
            var originalData = {};
            form.serializeArray().forEach(function(item) {
                originalData[item.name] = item.value;
            });
            form.data('original', originalData);
        });

        $('#addUserModal').on('hidden.bs.modal', function() {
            $('#form-tambahanggota')[0].reset();
        });

        $('#editUserModal').on('hidden.bs.modal', function() {
            $('#form-edit')[0].reset();
        });

        // Handle edit button click inside modal
        $('#form-edit').on('submit', function(e) {
            e.preventDefault(); // Mencegah tindakan default dari klik

            var form = $(this);
            var formData = form.serializeArray();
            var originalData = form.data('original'); // Data asli yang disimpan sebelumnya

            var hasChanges = false;

            // Periksa perubahan data
            formData.forEach(function(item) {
                if (originalData[item.name] !== item.value) {
                    hasChanges = true;
                }
            });

            if (!hasChanges) {
                Swal.fire({
                    title: "Tidak ada perubahan data",
                    icon: "info",
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Lakukan validasi awal dengan AJAX
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    // Jika validasi server lulus, tampilkan konfirmasi SweetAlert
                    Swal.fire({
                        title: "Yakin mengubah data?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ya',
                        cancelButtonColor: '#d33',
                        cancelButtonText: "Batal"
                    }).then(result => {
                        // Jika pengguna mengklik "Ya"
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "PATCH",
                                url: form.attr('action'),
                                data: form.serialize(),
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            title: "Data Anggota Berhasil Diperbarui",
                                            icon: "success",
                                            confirmButtonText: 'OK',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload(); // Reload the page to reflect the changes
                                            }
                                        });
                                    } else {
                                        // Tampilkan pesan error jika gagal
                                        var errors = response.errors;
                                        for (var error in errors) {
                                            Swal.fire({
                                                title: "Gagal Memperbarui Data",
                                                text: errors[error][0],
                                                icon: "error"
                                            });
                                        }
                                    }
                                },
                                error: function(response) {
                                    // Tampilkan pesan error jika gagal
                                    var errors = response.responseJSON.errors;
                                    for (var error in errors) {
                                        Swal.fire({
                                            title: "Gagal Memperbarui Data",
                                            text: errors[error][0],
                                            icon: "error"
                                        });
                                    }
                                }
                            });
                        }
                    });
                },
                error: function(response) {
                    // Tampilkan pesan error validasi langsung
                    var errors = response.responseJSON.errors;
                    for (var error in errors) {
                        Swal.fire({
                            title: "Gagal Memperbarui Data",
                            text: errors[error][0],
                            icon: "error"
                        });
                    }
                }
            });
        });

        // Event delegation for delete buttons
        $(document).on('click', '.alert_notif', function(e) {
            e.preventDefault(); // Mencegah tindakan default dari klik
            var form = $(this).closest('form');
            Swal.fire({
                title: "Yakin hapus data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
                cancelButtonColor: '#d33',
                cancelButtonText: "Batal"
            }).then(result => {
                // Jika pengguna mengklik "Ya"
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Anggota Berhasil Dihapus",
                                    icon: "success",
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload(); // Reload the page to reflect the changes
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal Menghapus Anggota",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                title: "Gagal Menghapus Anggota",
                                text: response.responseJSON.message,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>

</x-layout>

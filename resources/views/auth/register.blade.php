<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="wrapper">
        <!-- Sidebar -->
        <x-sidebar-admin></x-sidebar-admin>

        <div class="main-panel">
            <!-- Navbar -->
            <x-navbar></x-navbar>

            <!-- Content -->
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Tambah Anggota</h3>
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
                                <a href="#">Tambah Anggota</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Tambah Anggota</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <div class="max-w-xl">
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="tambahUser" tabindex="-1"
                                                        role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">
                                                                        <span class="fw-mediumbold">Tambah</span>
                                                                        <span class="fw-light">Anggota</span>
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{-- <form method="POST"
                                                                        action="{{ route('register') }}">
                                                                        @csrf

                                                                        <!-- Name -->
                                                                        <div class="mb-3">
                                                                            <label for="nama">Nama</label>
                                                                            <x-text-input id="nama"
                                                                                class="form-control" type="text"
                                                                                name="nama" :value="old('nama')"
                                                                                required autofocus
                                                                                autocomplete="nama" />
                                                                            <x-input-error :messages="$errors->get('nama')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- Email Address -->
                                                                        <div class="mb-3">
                                                                            <label for="email">Email</label>
                                                                            <x-text-input id="email"
                                                                                class="form-control" type="text"
                                                                                name="email" :value="old('email')"
                                                                                required autofocus
                                                                                autocomplete="email" />
                                                                            <x-input-error :messages="$errors->get('email')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- NIP -->
                                                                        <div class="mb-3">
                                                                            <label for="NIP">NIP</label>
                                                                            <x-text-input id="NIP"
                                                                                class="form-control" type="NIP"
                                                                                name="NIP" :value="old('NIP')"
                                                                                required autocomplete="NIP" />
                                                                            <x-input-error :messages="$errors->get('NIP')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- JK -->
                                                                        <div class="mb-3">
                                                                            <label for="jenis_kelamin">Jenis
                                                                                Kelamin</label>
                                                                            <select id="jenis_kelamin"
                                                                                class="form-control"
                                                                                type="jenis_kelamin"
                                                                                name="jenis_kelamin"
                                                                                :value="old('jenis_kelamin')" required
                                                                                autocomplete="jenis_kelamin">
                                                                                <option value="Laki-Laki">Laki-Laki
                                                                                </option>
                                                                                <option value="Perempuan">Perempuan
                                                                                </option>
                                                                            </select>
                                                                            <x-input-error :messages="$errors->get('jenis_kelamin')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- Alamat -->
                                                                        <div class="mb-3">
                                                                            <label for="alamat">Alamat</label>
                                                                            <x-text-input id="alamat"
                                                                                class="form-control" type="alamat"
                                                                                name="alamat" :value="old('alamat')"
                                                                                required autocomplete="alamat" />
                                                                            <x-input-error :messages="$errors->get('alamat')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- No Telp -->
                                                                        <div class="mb-3">
                                                                            <label for="no_tlp">No. Telpon</label>
                                                                            <x-text-input id="no_tlp"
                                                                                class="form-control" type="no_tlp"
                                                                                name="no_tlp" :value="old('no_tlp')"
                                                                                required autocomplete="no_tlp" />
                                                                            <x-input-error :messages="$errors->get('no_tlp')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- Password -->
                                                                        <div class="mb-3">
                                                                            <label for="password">Password</label>
                                                                            <x-text-input id="password"
                                                                                class="form-control" type="password"
                                                                                name="password" required
                                                                                autocomplete="new-password" />

                                                                            <x-input-error :messages="$errors->get('password')"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <!-- Confirm Password -->
                                                                        <div class="mb-3">
                                                                            <label for="password_confirmation">Confirm
                                                                                Password</label>
                                                                            <x-text-input id="password_confirmation"
                                                                                class="form-control" type="password"
                                                                                name="password_confirmation" required
                                                                                autocomplete="new-password" />

                                                                            <x-input-error :messages="$errors->get(
                                                                                'password_confirmation',
                                                                            )"
                                                                                class="mt-2" />
                                                                        </div>

                                                                        <div
                                                                            class="flex items-center justify-end mt-4">
                                                                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                                href="{{ route('login') }}">
                                                                                {{ __('Already registered?') }}
                                                                            </a>

                                                                            <x-primary-button
                                                                                class="ms-4 btn btn-primary">
                                                                                {{ __('Register') }}
                                                                            </x-primary-button>
                                                                        </div>
                                                                    </form> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</x-layout>

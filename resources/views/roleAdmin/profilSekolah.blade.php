<div class="modal fade" id="kelolaProfilSekolah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg optional -->
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Kelola Profil Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-profilSekolah" method="POST" action="{{ route('profilSekolah.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <!-- Logo Sekolah -->
                        <div class="col-md-6">
                            <label for="logo" class="form-label">Unggah Logo Sekolah</label>
                            <input type="file" class="form-control" id="logo" name="logo_sekolah" required>
                        </div>

                        <!-- Nama Sekolah -->
                        <div class="col-md-12">
                            <label for="nama" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" id="nama" name="nama_sekolah" required>
                        </div>

                        <!-- Alamat Sekolah -->
                        <div class="col-md-12">
                            <label for="alamat" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control" id="alamat" name="alamat_sekolah" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="modal-footer border-0 mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
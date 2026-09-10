<!-- Tambahkan sedikit CSS (bisa diletakkan di tag <style> atau file CSS) -->
<style>
    .image-upload-wrapper {
        position: relative;
        display: inline-block;
    }
    .img-preview-custom {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border: 2px dashed #ccc;
    }
    .btn-edit-photo {
        position: absolute;
        bottom: 0;
        right: -10px;
        transform: translateY(-20%);
    }
</style>

<!-- Modal Bibit -->
<div class="modal fade" id="modalBibit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- modal-dialog-centered agar posisi modal di tengah layar -->
        <div class="modal-content border-0 shadow">
            
            <!-- Header Modal -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fa-solid fa-leaf me-2"></i> Tambah Data Bibit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-bibit" class="needs-validation" data-target-modal="modalBibit" novalidate>
                <div class="modal-body p-4">
                    <input type="hidden" id="action" name="action" value="create">
                    <input type="hidden" id="id_bibit" name="id_bibit"> 

                    <!-- Bagian Upload Foto (Di-center) -->
                    <div class="text-center mb-4">
                        <div class="image-upload-wrapper">
                            <img id="profilePreview" src="img/bibit/default.jpg" alt="Foto Bibit" class="rounded shadow-sm img-preview-custom">
                            
                            <!-- Tombol Floating untuk Ganti Foto -->
                            <label class="btn btn-primary btn-sm rounded-circle shadow btn-edit-photo" for="foto" title="Ganti Foto">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            
                            <!-- d-none adalah bawaan bootstrap untuk menyembunyikan elemen -->
                            <input type="file" class="d-none" id="foto" name="foto" accept="image/*">
                        </div>
                        <div class="form-text mt-2">Format: JPG, PNG (Maks: 2MB)</div>
                    </div>

                    <!-- Input Nama Bibit -->
                    <div class="row mb-3 align-items-center">
                        <label for="nama_bibit" class="col-sm-3 col-form-label fw-semibold">Nama Bibit</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_bibit" name="nama_bibit" placeholder="Contoh: Bibit Kakao" required autocomplete="off">
                            <div class="invalid-feedback">Nama Bibit tidak boleh kosong.</div>
                        </div>
                    </div>

                    <!-- Input Deskripsi -->
                    <div class="row mb-3">
                        <label for="desk" class="col-sm-3 col-form-label fw-semibold">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="desk" name="desk" rows="3" placeholder="Masukkan deskripsi atau spesifikasi bibit..."></textarea>
                        </div>
                    </div>

                    <!-- Input Status -->
                    <div class="row mb-3 align-items-center">
                        <label for="status" class="col-sm-3 col-form-label fw-semibold">Status</label>
                        <div class="col-sm-9">
                            <!-- Menghapus form-select-sm agar tingginya sama dengan input teks -->
                            <select class="form-select" id="status" name="status" aria-describedby="Status">
                                <option value="1" selected>Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="submitButton">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
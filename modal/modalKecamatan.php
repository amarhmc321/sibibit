<div class="modal fade" id="modalKecamatan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Kecamatan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Data Kecamatan</h5> <!-- Judul dinamis -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-kecamatan" class="needs-validation" data-target-modal="modalKecamatan" novalidate>
                <div class="modal-body row">
                    <input type="hidden" id="action" name="action" value="create"> <!-- Aksi dinamis -->
                    <input type="hidden" id="id_kecamatan" name="id_kecamatan"> <!-- ID Kecamatan untuk edit -->

                    <!-- Input Nama Kecamatan -->
                    <div class="row mb-3">
                        <label for="nama_kecamatan" class="col-sm-3 col-form-label">Kecamatan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-nama" id="nama_kecamatan" data-kode-target="#kd_kecamatan" placeholder="Tambah Kecamatan" name="nama_kecamatan" required autocomplete="off">
                            <div class="invalid-feedback">Kecamatan Tidak Boleh Kosong</div>
                        </div>
                    </div>
<!-- 
                    <div class="row mb-3">
                        <label for="kd_lokasi" class="col-sm-3 col-form-label">Kode Kecamatan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kd_kecamatan" id="kd_kecamatan" oninput="this.value = this.value.toUpperCase()" maxlength="3" required="" placeholder="XXX">
                            <div class="invalid-feedback">idak Boleh Kosong</div>
                        </div>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitButton">Simpan</button> <!-- Tombol dinamis -->
                </div>
            </form>
        </div>
    </div>
</div>


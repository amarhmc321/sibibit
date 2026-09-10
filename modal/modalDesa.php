<div class="modal fade" id="modalDesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDesaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDesaLabel">Tambah Data Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-desa" class="needs-validation" data-target-modal="modalDesa" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">

                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="mb-2">
                            <div class="card p-3 ">
                               

                                <div class="mb-2 col-md-12">
                                    <label for="nama-obat" class="form-label fw-bold">Nama Desa</label>
                                    <input type="text" class="form-control form-control-sm" id="nama-desa" name="nama_desa">
                                    <input type="hidden" class="form-control" id="action" name="action" value="create">
                                    <input type="hidden" class="form-control" id="id-desa" name="id_desa">
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="id-kategori" class="form-label fw-bold">Pilih Kecamatan</label>
                                    <select class="form-select form-select-sm " id="id-kecamatan" name="id_kecamatan" aria-describedby="kecamatan"></select>
                                    
                                </div>

                                

                                

                                     
                            </div>
                        </div>

                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
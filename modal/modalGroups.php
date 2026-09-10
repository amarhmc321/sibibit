<div class="modal fade" id="modalGroups" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalGroupsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalGroupsLabel">Tambah Data Lahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-groups" class="needs-validation" data-target-modal="modalGroups" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">

                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="mb-3 col-md-4">
                            <div class="card p-3 ">

                            
                                    <input type="hidden" class="form-control" id="action" name="action" value="create">
                                    <input type="hidden" class="form-control readonly-style" id="id-group" name="id_group" readonly>


                                <div class="mb-2 col-md-12">
                                    <label for="luas-lahan" class="form-label fw-bold">Nama Kelompok</label>
                                    <input type="text" class="form-control" id="nama-kelompok" name="nama_kelompok" placeholder="Nama Kelompok" required>
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="komoditas" class="form-label fw-bold">Komoditas</label>
                                    <input type="text" class="form-control" id="komoditas" name="komoditas" placeholder="Komoditas" required>
                                </div>


                                <div class="mb-2 col-md-12">
                                    <label for="id_desa" class="form-label fw-bold">Ketua</label>
                                    <select class="form-select " id="id-petani" name="id_petani" aria-describedby="Petani" required>
                                    </select>
                                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                   
                                </div>
                               


                            </div>
                        </div>

                        <div class=" col-md-8">
                            <h6 class="section-title fw-bold text-primary mb-3">Lokasi Lahan</h6>
                            <div class="row">

                             

                                
                                <div class="mb-3 col-md-6">
                                    <label for="id_desa" class="form-label fw-bold">Kecamatan</label>
                                    <select class="form-select " id="id-kecamatan" name="id_kecamatan" aria-describedby="Kecamatan" required>
                                    </select>
                                   <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="id_desa" class="form-label fw-bold">Desa</label>
                                    <select class="form-select " id="id-desa" name="id_desa" aria-describedby="Desa" required>
                                        <option selected disabled value="">Pilih Kecamatan dulu</option>
                                    </select>
                                    <div id="invalid-feedback" class="invalid-feedback">
                                        Desa harus dipilih
                                    </div>
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
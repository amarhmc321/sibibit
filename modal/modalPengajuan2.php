<div class="modal fade" id="modalPengajuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalPengajuanLabel">Tambah Data Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-pengajuan" class="needs-validation" data-target-modal="modalPengajuan" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">

                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="mb-3 col-md-6">
                            <div class="card p-3 ">

                              
                               
                                    <input type="hidden" class="form-control" id="action" name="action" value="create">
                                    <input type="hidden" class="form-control readonly-style" id="id-pengajuan" name="id_pengajuan" readonly required>
                               

                                <div class="mb-2 col-md-12">
                                    <label for="judul" class="form-label fw-bold">Judul/Kegiatan</label>
                                    <input type="text" class="form-control" id="judul" name="judul" placeholder="judul" required>
                                </div>

                                 <div class="mb-2 col-md-12">
                                    <label for="id-group" class="form-label fw-bold">Kelompok</label>
                                    <select class="form-select form-select-sm " id="id-group" name="id_group" aria-describedby="Groups"></select>
                                    
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="jns-bantuan" class="form-label fw-bold">Jenis Bantuan</label>
                                    <select class="form-select form-select-sm " id="jns-bantuan" name="jns_bantuan" aria-describedby="Jenis Bantuan">
                                        <option value="Benih">Benih/Bibit</option>
                                        <option value="Pesnab">Pesnab</option>
                                    </select>
                                </div>

                               <div class="mb-2 col-md-12 d-none">
                                    <label for="jml-bantuan" class="form-label fw-bold">Nominal Benih (PHN)/ Pesnab (LTR/KG)</label>
                                    <input type="number" class="form-control" id="jml-bantuan" name="jml_bantuan" placeholder="Contoh: 3000 (PHN) / 125 (LTR/KG)" >
                                    
                                </div> 
                            
                               


                            </div>
                        </div>

                        <div class=" col-md-6">
                          
                            <div class="row">

                        

                    <div class="card p-3 mb-2" id="Proposal">
    <div class="profile-container text-center">
        <h6 class="fw-bold">Proposal Permohonan</h6>
        
        <!-- Container untuk Iframe Preview PDF -->
        <div id="pdf-container" style="display: none; margin: 10px 0;">
            <iframe id="pdf-preview" src="" width="100%" height="360px" style="border: 1px solid #ccc; border-radius: 5px;"></iframe>
        </div>
        
        <!-- Placeholder jika PDF belum ada -->
        <div id="empty-preview" style="height: 360px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border: 1px dashed #ccc; border-radius: 5px; margin: 10px 0;">
            <span class="text-muted">Preview PDF Kosong</span>
        </div>

        <div class="mt-2">
            <!-- Atribut for diubah agar sama dengan id input -->
            <label class="btn btn-secondary btn-sm" for="file-proposal">
                <i class="fa-solid fa-file"></i> Upload Proposal Permohonan
            </label>
            <!-- accept diperbaiki menjadi .pdf -->
            <input type="file" class="d-none" id="file-proposal" name="file-proposal" accept=".pdf, application/pdf" required>
            <div class="invalid-feedback">Tidak Boleh Kosong</div>
        </div>
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
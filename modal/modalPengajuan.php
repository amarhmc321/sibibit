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
                               

                                <!-- <div class="mb-2 col-md-12">
                                    <label for="judul" class="form-label fw-bold">Judul/Kegiatan</label>
                                    <input type="text" class="form-control" id="judul" name="judul" placeholder="judul" required>
                                </div> -->

                                 <div class="mb-2 col-md-12">
                                    <label for="judul" class="form-label fw-bold">Judul/Kegiatan</label>
                                    <select class="form-select form-select-sm " id="judul" name="judul" aria-describedby="Judul">
                                        <option value="Pengajuan bantuan bibit Kakao">Pengajuan bantuan Bibit Kakao</option>
                                         <option value="Pengajuan bantuan bibit Kelapa Genjah">Pengajuan bantuan Bibit Kelapa Genjah</option>
                                          <option value="Pengajuan bantuan bibit Pala">Pengajuan bantuan Bibit Pala</option>
                                          <option value="Pengajuan bantuan bibit Kelapa Sawit">Pengajuan bantuan Bibit Kelapa Sawit</option>
                                          <option value="Pengajuan bantuan bibit Cengkeh">Pengajuan bantuan Bibit Cengkeh</option>
                                    </select>
                                   

                                </div>

                                 <div class="mb-2 col-md-12">
                                    <label for="id-group" class="form-label fw-bold">Kelompok</label>
                                    <select class="form-select form-select-sm " id="id-group" name="id_group" aria-describedby="Groups"></select>
                                    
                                </div>

                                <!-- <div class="mb-2 col-md-12">
                                    <label for="jns-bantuan" class="form-label fw-bold">Jenis Bantuan</label>
                                    <input type="text" class="form-control readonly-style" id="bib" name="id_pengajuan" readonly required>
                                </div> -->

                                <div class="mb-2 col-md-12">
                                    <label for="nama_bibit" class="form-label fw-bold">Jenis Bibit</label>
                                    <input type="hidden" class="form-control readonly-style" id="id_bibit" name="id_bibit" readonly required>
                                    <input type="hidden" id="jml-per-hektar" value="100">
                                    <input type="hidden" id="satuan-bibit" value="pohon">
                                    <input type="text" class="form-control readonly-style" id="nama_bibit" name="nama_bibit" readonly>
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="tot-luas-lahan" class="form-label fw-bold">Total Luas Lahan Kelompok</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
                                        <input type="text" class="form-control readonly-style fw-bold" id="tot-luas-lahan" placeholder="Pilih kelompok terlebih dahulu" readonly>
                                        <span class="input-group-text">Ha</span>
                                    </div>
                                    <div id="estimasi-bibit" class="form-text mt-1"></div>
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="jml-bantuan" class="form-label fw-bold">Estimasi Bantuan Bibit</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-leaf"></i></span>
                                        <input type="number" class="form-control readonly-style fw-bold text-success" id="jml-bantuan" name="jml_bantuan" placeholder="Otomatis terhitung" readonly>
                                        <span class="input-group-text" id="label-satuan-bantuan">bibit</span>
                                    </div>
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="tgl-penyaluran" class="form-label fw-bold">Tanggal Penyaluran (Diisi Admin)</label>
                                    <input type="date" class="form-control readonly-style" id="tgl-penyaluran" name="tgl_penyaluran" readonly>
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
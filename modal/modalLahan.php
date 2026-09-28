<div class="modal fade" id="modalLahan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLahanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalLahanLabel">Tambah Data Lahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-lahan" class="needs-validation" data-target-modal="modalLahan" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">

                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="mb-3 col-md-4">
                            <div class="card p-3 ">

                            
                                    <input type="hidden" class="form-control" id="action" name="action" value="create">
                                    <input type="hidden" class="form-control readonly-style" id="id-lahan" name="id_lahan" readonly>


                                <div class="mb-2 col-md-12">
                                    <label for="id_desa" class="form-label fw-bold">Pemilik Lahan</label>
                                    <select class="form-select " id="id-petani" name="id_petani" aria-describedby="Petani" required>
                                    </select>
                                    <div class="invalid-feedback">Tidak Boleh Kosong</div>
                                   
                                </div>

                                <div class="mb-2 col-md-12">
                                    <label for="id_group" class="form-label fw-bold">Kelompok Tani</label>
                                    <select class="form-select " id="id-group" name="id_group" aria-describedby="Kelompok">
                                    </select>
                                   
                                </div>
                               

                                <div class="mb-2 col-md-12">
                                    <label for="luas-lahan" class="form-label fw-bold">Luas Lahan</label>
                    <input type="number" class="form-control" id="luas-lahan" name="luas_lahan" placeholder="Luas Lahan" step="0.5" min="0" required>
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


                            <!-- Input Koordinat -->
<!-- <div class="mb-3 col-md-6">
    <label for="latitude" class="form-label fw-bold">Latitude</label>
    <input type="text" class="form-control readonly-style" id="latitude" name="latitude" readonly required>
</div>
<div class="mb-3 col-md-6">
    <label for="longitude" class="form-label fw-bold">Longitude</label>
    <input type="text" class="form-control readonly-style" id="longitude" name="longitude" readonly required>
</div> -->

<!-- Container Map -->
<!-- <div class="mb-3 col-md-12">
    <label class="form-label fw-bold">Pilih Titik Lokasi di Peta</label>
    <div id="map-lahan" style="height: 300px; width: 100%; border: 1px solid #ccc; border-radius: 5px z-index: 1;"></div>
    <small class="text-muted">Klik pada peta atau geser marker untuk menentukan koordinat lahan.</small>
</div> -->


<!-- Input Koordinat -->
<div class="mb-3 col-md-6">
    <label for="latitude" class="form-label fw-bold">Latitude</label>
    <!-- Hilangkan readonly, tambahkan placeholder -->
    <input type="number" step="any" class="form-control" id="latitude" name="latitude" required placeholder="Contoh: -4.0435">
</div>
<div class="mb-3 col-md-6">
    <label for="longitude" class="form-label fw-bold">Longitude</label>
    <!-- Hilangkan readonly, tambahkan placeholder -->
    <input type="number" step="any" class="form-control" id="longitude" name="longitude" required placeholder="Contoh: 121.5815">
</div>

<!-- Container Map -->
<div class="mb-3 col-md-12">
    <label class="form-label fw-bold mb-0">Lokasi pada Peta</label>
    <div id="map-lahan" style="height: 350px; width: 100%; border: 1px solid #ced4da; border-radius: 5px; z-index: 1;"></div>
    <small class="text-muted">Peta akan otomatis mengarah ke Kecamatan/Desa yang dipilih. Anda juga bisa mengetik koordinat manual atau menggeser marker.</small>
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
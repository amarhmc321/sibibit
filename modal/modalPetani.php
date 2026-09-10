<div class="modal fade" id="modalPetani" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPetaniLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalPetaniLabel">Tambah Data Petani</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-petani" class="needs-validation" data-target-modal="modalPetani" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">

                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="mb-3 col-md-4">
                            <div class="card p-3 ">

                               <!--  <div class="profile-container">
                                    <img id="profilePreview" src="img/karyawan/default.png" alt="Foto Profil" class="profile-img rounded-circle">
                                    <div class="mb-2">
                                        <label class="btn btn-primary btn-sm" for="foto">
                                            <i class="fa-solid fa-camera"></i> Ganti Foto
                                        </label>
                                        <input type="file" class="hidden-input" id="foto" name="foto" accept="image/*">
                                    </div>
                                </div>
 -->
                               
                                    <input type="hidden" class="form-control" id="action" name="action" value="create">
                                    <input type="hidden" class="form-control readonly-style" id="id-petani" name="id_petani" readonly required>
                               

                                <div class="mb-2 col-md-12">
                                    <label for="nik" class="form-label fw-bold">NIK</label>
                                    <input type="tel" class="form-control" id="nik" name="nik">
                                </div>
                               
                                <div class="mb-2 col-md-12">
                                    <label for="username2" class="form-label fw-bold">Username</label>
                                    <input type="text" class="form-control" id="username2" name="username">
                                </div>
                                <div class="mb-2 col-md-12">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <div class="input-group">

                                        <input type="password"
                                            class="form-control rounded-end myInput"
                                            id="password"
                                            placeholder="Minimal 8 karakter"
                                            name="password"
                                            required
                                            autocomplete="off">
                                        <div class="invalid-feedback fst-italic">
                                            <i class="fas fa-exclamation-circle me-2"></i>Password wajib diisi
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class=" col-md-8">
                           <!--  <h6 class="section-title fw-bold text-primary mb-3">Informasi Personal</h6> -->
                            <div class="row">

                                <div class="mb-3 col-md-12">
                                    <label for="nama-petani" class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama-petani" name="nama_petani" placeholder="Nama Lengkap" required autocomplete="off">
                                    <div class="invalid-feedback">Nama tidak boleh kosong</div>
                                </div>

                                

                               

                                <div class="col-md-6">
                                    <label for="jekelL" class="form-label fw-bold">Jenis Kelamin</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jekel" id="jekelL" value="L" checked>
                                            <label class="form-check-label" for="jekelL">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jekel" id="jekelP" value="P">
                                            <label class="form-check-label" for="jekelP">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="kontak" class="form-label fw-bold">Kontak</label>
                                    <input type="tel" class="form-control" id="kontak" name="kontak" placeholder="08XXX">
                                    <div class="invalid-feedback">Kontak tidak boleh kosong</div>
                                </div>

                                

                               

                                
                                <div class="mb-3 col-md-6">
                                    <label for="id_desa" class="form-label fw-bold">Kecamatan</label>
                                    <select class="form-select " id="id-kecamatan" name="id_kecamatan" aria-describedby="Kecamatan">
                                    </select>
                                   
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
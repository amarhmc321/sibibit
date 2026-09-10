<?php session_start(); ?>

<div class="card shadow-lg border-0">
    <!-- Form Start -->
    <form class="needs-validation" novalidate enctype="multipart/form-data">

        <div class="card-body p-3">
            <div class="row">
                <!-- Foto Profil Section -->
                <div class="col-md-4 text-center border-end-md">
                    <div class="sticky-top" style="top: 20px;">
                        <h5 class="mb-3 fw-bold">Foto Profil</h5>

                        <!-- Preview Gambar dengan efek hover -->
                        <div class="position-relative d-inline-block mb-3">
                            <div class="avatar-upload-wrapper">
                                <img id="previewFoto" src="img/karyawan/<?= $_SESSION['foto']; ?>"
                                    class="rounded-circle shadow profile-avatar" width="160" height="160">
                                <label for="foto" class="avatar-upload-label btn btn-primary rounded-circle">
                                    <i class="fa fa-camera"></i>
                                </label>
                                <div class="avatar-overlay">
                                    <span class="text-white small">Ubah Foto</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Pengguna -->
                        <div class="user-info mt-3">
                            <span class="badge bg-success mb-2 p-2 fs-6"><?= $_SESSION['nama']; ?></span>
                            
                        </div>

                        <!-- Progress bar (akan muncul saat upload) -->
                        <div class="progress mt-3 d-none" id="uploadProgress" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>

                        <!-- Input File (Hidden) -->
                        <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                    </div>
                </div>

                <!-- Form Input Section -->
                <div class="col-md-8 ps-md-4">


                    <input type="hidden" id="action" name="action" value="profile">
                    <input type="hidden" id="id_user" name="id_user" value="<?= $_SESSION['id_user']; ?>">


                    <div class="mb-4">
                        <label for="user" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" value="<?= $_SESSION['username']; ?>" class="form-control" id="user" required>
                        </div>
                        <div class="invalid-feedback">Username tidak boleh kosong</div>
                        <div class="form-text">Gunakan username yang mudah diingat</div>
                    </div>

                    <!-- Password dengan Toggle Visibility -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Minimal 8 Karakter">
                            <button class="btn btn-outline-primary" type="button" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Minimal 8 Karakter</div>
                        <div class="form-text">Kosongkan jika tidak ingin Dirubah</div>

                        <!-- Password strength meter -->
                        <div class="password-strength mt-2 d-none">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="password-strength-text"></small>
                        </div>
                    </div>

                   
                </div>
            </div>
        </div>



        <!-- Tombol Simpan -->
        <div class="card-footer p-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- <div class="form-text">Terakhir update: -</div> -->
                <button type="submit" name="simpan" class="btn btn-primary px-4 py-2 rounded-pill">
                    <i class="fa fa-save me-2"></i>Update Profil
                </button>

            </div>
        </div>
    </form>
</div>
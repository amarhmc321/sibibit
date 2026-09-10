<div class="modal fade" id="modalUsers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content" style=" box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Data Pengguna</span>
                </h5>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-users" class="needs-validation" data-target-modal="modalUsers" novalidate enctype="multipart/form-data">
                <div class="modal-body p-3">
                    <!-- Nama Input -->
                    <input type="hidden" name="action" id="action" value="create">
                    <input type="hidden" name="id_user" id="id_user">
                    

                     <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="id_penulis" class="form-label fw-bold">Nama Pengguna</label>
                               <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukan Naman Pengguna">
                            </div>
                        </div>



                    <!-- username Input -->
                    <!-- username dan Password -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="card p-3">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-at text-secondary"></i>
                                            </span>
                                            <input type="username"
                                                class="form-control rounded-end"
                                                id="username"
                                                placeholder="Buat username unik"
                                                name="username"
                                                required
                                                autocomplete="off">
                                            <div class="invalid-feedback fst-italic">
                                                <i class="fas fa-exclamation-circle me-2"></i>username harus diisi
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label fw-bold">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-lock text-secondary"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control rounded-end"
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
                        </div>
                    </div>


                    <!-- Level Selection -->
                    
                        <div class="col-12">
                            <label for="karyawan" class="form-label fw-bold">Level Akses</label>
                            <div class="d-flex gap-2 flex-wrap">


                                <div class="form-check-card col-3">
                                    <input class="form-check-input" type="radio" name="level" id="Admin" value="1" checked="">
                                    <label class="form-check-label card p-3" for="Admin">
                                        <i class="fas fa-user-tie mb-2 text-secondary"></i>
                                        <span>Admin</span>
                                    </label>
                                </div>                        
                                 
                                <div class="form-check-card col-3">
                                    <input class="form-check-input" type="radio" name="level" id="Manager" value="2">
                                    <label class="form-check-label card p-3" for="Manager">
                                        <i class="fas fa-user mb-2 text-secondary"></i>
                                        <span>Manager</span>
                                    </label>
                                </div>
                                


                            </div>
                        </div>
                   

                </div>

                <div class="modal-footer pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light mt-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 mt-2">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
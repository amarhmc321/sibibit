<!-- Tambahkan link ini di tag <head> Anda jika belum menggunakan Bootstrap Icons -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> -->

<div class="modal fade" id="modalPengajuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Header Modal -->
            <div class="modal-header border-bottom-1 pb-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalPengajuanLabel">
                    <i class="bi bi-ui-checks"></i> Detail & Tindak Lanjut Pengajuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-pengajuan" class="needs-validation" data-target-modal="modalPengajuan" novalidate enctype="multipart/form-data">
                <!-- Input Hidden dipindah ke atas agar rapi -->
                <input type="hidden" class="form-control" id="action" name="action" value="create">
                <input type="hidden" class="form-control readonly-style" id="id-pengajuan" name="id_pengajuan" readonly required>

                <!-- Body Modal dengan background abu-abu sangat muda -->
                <div class="modal-body p-4">
                    <div class="row g-4">
                        
                        <!-- Kolom Kiri: Informasi Bantuan -->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm h-100 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Informasi Data</h6>
                                    
                                    <div class="mb-3">
                                        <label for="nama-kelompok" class="form-label text-secondary small fw-bold mb-1">Kelompok</label>
                                        <input type="text" class="form-control text-muted" id="nama-kelompok" name="nama_kelompok" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="jns-bantuan" class="form-label text-secondary small fw-bold mb-1">Jenis Bantuan</label>
                                        <input type="text" class="form-control text-muted" id="jns-bantuan" name="jns_bantuan" readonly>
                                    </div>


                                    <div class="mb-2">
                                        <label for="jns-bantuan" class="form-label text-secondary small fw-bold mb-1">Tanggal Penyaluran (Estimasi)</label>
                                        <input type="date" class="form-control" id="tgl-penyaluran" name="tgl_penyaluran">
                                    </div> 

                                    <div class="mb-2">
                                        <label for="jml-bantuan" class="form-label text-secondary small fw-bold mb-1">Nominal Benih</label>
                                        <div class="input-group">
                                            <span class="input-group-text "><i class="fa fa-leaf"></i></span>
                                            <input type="number" class="form-control fw-bold" id="jml-bantuan" name="jml_bantuan" placeholder="Contoh: 3000" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Form Tindak Lanjut -->
                        <div class="col-md-7">
                            <div class="card border-0 shadow-sm h-100 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Form Keputusan</h6>

                                    <div class="mb-3">
                                        <label for="s-pengajuan" class="form-label fw-bold">Status Pengajuan</label>
                                        <select class="form-select" id="s-pengajuan" name="s_pengajuan" required>
                                            <option value="" disabled selected>Pilih Status...</option>
                                            <option value="0">⏳ Pending</option>
                                            <option value="1">✅ Approve</option>
                                            <option value="2">❌ Tolak</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label for="catatan" class="form-label fw-bold">Catatan</label>
                                        <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Berikan catatan atau alasan penolakan/persetujuan..." required></textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer border-top-1 px-4 py-3">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Bibit -->
<div class="modal fade" id="modalTimeline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- modal-dialog-centered agar posisi modal di tengah layar -->
        <div class="modal-content border-0 shadow">
            
            <!-- Header Modal -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">
                   Update Time Line
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-timeline" class="needs-validation" data-target-modal="modalTimeline" novalidate>
                <div class="modal-body p-4">
                    <input type="hidden" id="action" name="action" value="update">

                    

                    <!-- Step 1 -->
                    <div class="row mb-3 align-items-center">
                        <label for="step1_start" class="col-sm-4 col-form-label fw-semibold">Step-1 (Persiapan Berkas)</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step1_start" name="step1_start" required>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step1_end" name="step1_end" required>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="row mb-3 align-items-center">
                        <label for="step2_start" class="col-sm-4 col-form-label fw-semibold">Step-2 (Pengajuan Berkas)</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step2_start" name="step2_start" required>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step2_end" name="step2_end" required>
                        </div>
                    </div>


                    <!-- Step 3 -->
                    <div class="row mb-3 align-items-center">
                        <label for="step3_start" class="col-sm-4 col-form-label fw-semibold">Step-3 (Pengumuman)</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step3_start" name="step3_start" required>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step3_end" name="step3_end" required>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="row mb-3 align-items-center">
                        <label for="step4_start" class="col-sm-4 col-form-label fw-semibold">Step-4 (Penyaluran)</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step4_start" name="step4_start" required>
                        </div>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="step4_end" name="step4_end" required>
                        </div>
                    </div>


                </div>

                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="submitButton">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
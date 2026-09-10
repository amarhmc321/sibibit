<!-- Content -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="judul"><i class="fas fa-envelope-circle-check"></i> Data Pengajuan</h3>

               <!--  <div class="col-md-2" data-bs-toggle="tooltip2" title="Filter kategori">
                    <select class="form-select" id="filter-kecamatan"></select>
                </div> -->

<!-- 
                <div class="ms-2" data-bs-toggle="tooltip2" title="Tambah Data">
                    <button class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target="#modalPengajuan"><i class="fas fa-plus"></i></button>
                </div> -->


            </div>
            <div class="">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="rowsPerPage">Tampilkan</label>
                        <select id="rowsPerPage" class="form-select form-select-sm mb-3" style="width: auto; display: inline-block;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select> Data
                    </div>
                </div>

                 <div class="row mb-3">
                   
                    <div class="col-md-4">
                        <label for="rowsPerPage">Kelompok</label>
                       <select class="form-select" id="filter-group"></select>
                    </div>
                    <div class="col-md-4">
                        <label for="rowsPerPage">Status</label>
                       <select class="form-select" id="filter-status">
                        <option value="0">Pending</option>
                        <option value="1">ACC</option>
                        <option value="2">Ditolak</option>
                       </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="Pengajuan">
                        <thead class="table-success">
                            <tr>
                                <th class="ph-full">No</th>
                                <th>Judul/Kegiatan</th>
                                <th>Kelompok</th>
                                <th>Kecamatan/Desa</th>
                                <th>ketua</th>
                                <th>Jenis Bibit</th>
                                <th>status</th>
                                <th>TGL Pengajuan</th>
                                <th>TGL Penyaluran</th>
                                <th>catatan</th>
                                <th>File</th>
                                <th class="ph-actions-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class=" justify-content-between align-items-center mt-2 mb-3">
                    <div class="text-muted mb-3" id="info"></div>
                    <ul class="pagination pagination-sm mb-3 gap-1" id="pagination"></ul>
                </div>


            </div>
        </div>
    </div>
</div>


<!-- Modal load edit dan tambah Pengajuan -->
<?php
require "../modal/modalPengajuan.php";
?>
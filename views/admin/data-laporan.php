<?php
date_default_timezone_set('Asia/Makassar');
$dari = date('Y-m-d', strtotime('-1 year'));
$hingga  = date('Y-m-d');
?>
<!-- Content -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="judul"><i class="fas fa-file"></i> Data Laporan</h3>

                <div class="ms-2" data-bs-toggle="tooltip2" title="Buat Laporan">
                    <button class="btn btn-sm btn-success report" id="btn-export"><i class="fas fa-print"></i></button>
                </div>

            </div>
            <div class="">
                <div class="row mb-2">
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

                 <div class="row mb-4">
                   
                    <div class="col-md-3">
                        <label for="rowsPerPage">Kecamatan</label>
                       <select class="form-select" id="filter-kecamatan"></select>
                    </div>

                     <div class="col-md-3">
                        <label for="rowsPerPage">Jenis Bibit</label>
                       <select class="form-select" id="filter-bibit"></select>
                    </div>

                    <div class="col-md-3">
                       <label for="tanggal_mulai">Dari</label>
                     <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= $dari ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                      <label for="tanggal_sampai">Sampai</label>
            <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="<?= $hingga ?>" class="form-control">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="Pengajuan">
                        <thead class="table-success">
                            <tr>
                                <th class="ph-full">No</th>
                                <th>Tahun</th>
                                <th>Uraian/Kegiatan</th>
                                <th>Kecamatan/Desa</th>
                                <th>Kelompok Tani</th>
                                <th>ketua</th>
                                <th>Luas Lahan</th>
                                <th>Jenis Bantuan</th>
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


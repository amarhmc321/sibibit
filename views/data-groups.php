<!-- Content -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="judul"><i class="fas fa-people-group"></i> Data Kelompok</h3>



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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="Groups">
                        <thead class="table-success">
                            <tr>
                                <th class="ph-full">No</th>
                                <th>Nama Groups</th>
                                <th>Kecamatan/Desa</th>
                                <th>Ketua</th>
                                <th>Total Anggota</th>
                                <th>Komoditas</th>
                               
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


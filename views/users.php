<?php
session_start();
if (!isset($_SESSION['id_user']) || ($_SESSION['level'] != 1 && $_SESSION['level'] != 2 && $_SESSION['level'] != 0)) {
    header("Location: views/404.html");
    exit;
}
?>


<!-- Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="judul"><i class="fas fa-user-cog"></i> Data Users</h3>
                <?php if ($_SESSION['level'] == 2 || $_SESSION['level'] == 0) : ?>
                    <div data-bs-toggle="tooltip" title="Tambahkan data">
                        <button class="btn btn-sm btn-primary add" data-bs-toggle="modal" data-bs-target="#modalUsers"><i class="fas fa-plus"></i></button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="">


                <div class="row mb-3">

                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <select id="rowsPerPage" class="form-select form-select-sm" style="width: 80px;">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <span class="ms-2">entri</span>
                        </div>
                    </div>



                   

                </div>

                <div class="table-responsive" style="white-space:nowrap;">
                    <table class="table table-hover table-striped table-bordered table-custom " id="Users">
                        <thead class="table-danger">
                            <tr>
                                <th class="ph-full">No</th>
                                <th class="ph-avatar">Nama</th>
                                <th>Username</th>
                                <th class="ph-short">Level</th>
                                <th class="ph-long">Last ON</th>
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




<!-- Modal tambah dan edit data Users-->
<?php require "../modal/modalUsers.php"; ?>
<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── PAGINATION ── */
$limit    = 5;
$halaman  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman  = ($halaman < 1) ? 1 : $halaman;
$offset   = ($halaman - 1) * $limit;
$totalData    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM kontak"));
$totalHalaman = ceil($totalData / $limit);
$data = mysqli_query($koneksi, "SELECT * FROM kontak ORDER BY tanggal_kirim DESC LIMIT $limit OFFSET $offset");
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Data Kontak</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item active">Data Kontak</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Daftar Pesan Kontak</h5>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th width="150">Tanggal Kirim</th>
                <th width="100" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = $offset + 1;
              if (mysqli_num_rows($data) > 0) {
                  while ($d = mysqli_fetch_assoc($data)) {
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['nama']) ?></td>
                <td><?= htmlspecialchars($d['email']) ?></td>
                <td><?= htmlspecialchars(mb_substr($d['pesan'], 0, 80)) ?>...</td>
                <td><?= $d['tanggal_kirim'] ?></td>
                <td class="text-center">
                  <a href="edit_kontak.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="hapus_kontak.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" title="Hapus"
                     onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
              <?php
                  }
              } else {
                  echo "<tr><td colspan='6' class='text-center'>Data kosong</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
          <ul class="pagination pagination-sm m-0 float-end">
            <?php if ($halaman > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $halaman - 1 ?>">&laquo;</a>
              </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
              <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <?php if ($halaman < $totalHalaman): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $halaman + 1 ?>">&raquo;</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "template/footer.php"; ?>
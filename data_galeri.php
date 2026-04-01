<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── HAPUS (admin only) ── */
if (isset($_GET['hapus'])) {
    require_admin('data_galeri.php');
    $id = (int)$_GET['hapus'];
    $q = mysqli_query($koneksi, "SELECT gambar FROM galeri WHERE id='$id'");
    $d = mysqli_fetch_assoc($q);
    if ($d) {
        if (!empty($d['gambar']) && file_exists("upload/".$d['gambar'])) {
            unlink("upload/".$d['gambar']);
        }
        mysqli_query($koneksi, "DELETE FROM galeri WHERE id='$id'");
    }
    echo "<script>alert('Data berhasil dihapus');window.location='data_galeri.php';</script>";
    exit;
}

/* ── PAGINATION ── */
$limit        = 5;
$halaman      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman      = ($halaman < 1) ? 1 : $halaman;
$offset       = ($halaman - 1) * $limit;
$totalData    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM galeri"));
$totalHalaman = ceil($totalData / $limit);
$data = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Data Galeri</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item active">Data Galeri</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Daftar Galeri</h5>
          <?php if (can_write()): ?>
          <a href="input_galeri.php" class="btn btn-sm btn-primary" style="border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i>Tambah Galeri
          </a>
          <?php else: ?>
          <span class="badge" style="background:#FFF7ED;color:#C2410C;border:1px solid #FED7AA;font-size:11px;padding:6px 10px;border-radius:8px;">
            <i class="bi bi-eye me-1"></i>Mode Lihat Saja
          </span>
          <?php endif; ?>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th width="120">Gambar</th>
                <th width="130">Tanggal Upload</th>
                <th width="100" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = $offset + 1;
              if (mysqli_num_rows($data) > 0) {
                  while ($d = mysqli_fetch_array($data)) {
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['judul']) ?></td>
                <td><?= htmlspecialchars($d['deskripsi']) ?></td>
                <td>
                  <?php if (!empty($d['gambar'])): ?>
                    <img src="upload/<?= $d['gambar'] ?>" width="90" class="img-thumbnail">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td><?= $d['tanggal_upload'] ?></td>
                <td class="text-center">
                  <?php if (can_write()): ?>
                    <a href="edit_galeri.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  <?php else: ?>
                    <span style="font-size:11px;color:#94A3B8;">—</span>
                  <?php endif; ?>
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
              <li class="page-item"><a class="page-link" href="?page=<?= $halaman - 1 ?>">&laquo;</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
              <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <?php if ($halaman < $totalHalaman): ?>
              <li class="page-item"><a class="page-link" href="?page=<?= $halaman + 1 ?>">&raquo;</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "template/footer.php"; ?>
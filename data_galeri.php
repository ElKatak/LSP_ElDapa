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

                    <button class="btn btn-sm btn-info me-1" title="Lihat Detail"
                    onclick="viewGaleri({
                        judul:   '<?= htmlspecialchars($d['judul'], ENT_QUOTES) ?>',
                        desk:    <?= json_encode(strip_tags($d['deskripsi'])) ?>,
                        tanggal: '<?= $d['tanggal_upload'] ?>',
                        gambar:  '<?= $d['gambar'] ?>'
                    })">
                    <i class="bi bi-eye-fill"></i>
                    </button>   

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
  <!-- MODAL VIEW GALERI -->
<div class="modal fade" id="modalViewGaleri" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">

      <div class="modal-header border-0 px-4 pt-4 pb-0">
        <h5 class="fw-bold mb-0" style="font-family:'Outfit',sans-serif;">
          <i class="bi bi-images text-purple me-2"></i>Detail Galeri
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4 pb-4">
        <div id="vgal-gambar-wrap" class="mb-3"></div>
        <h5 id="vgal-judul" style="font-weight:800;color:#0F172A;font-family:'Outfit',sans-serif;"></h5>
        <div style="font-size:12px;color:#64748B;margin-bottom:12px;">
          <i class="bi bi-calendar3 me-1"></i><span id="vgal-tanggal"></span>
        </div>
        <p id="vgal-desk" style="font-size:14px;color:#475569;line-height:1.7;"></p>
      </div>

    </div>
  </div>
</div>

<script>
function viewGaleri(d) {
  document.getElementById('vgal-gambar-wrap').innerHTML = d.gambar
    ? `<img src="upload/${d.gambar}" style="width:100%;height:220px;object-fit:cover;border-radius:12px;">`
    : `<div style="width:100%;height:80px;background:#F1F5F9;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#94A3B8;"><i class="bi bi-image fs-3"></i></div>`;
  document.getElementById('vgal-judul').textContent   = d.judul;
  document.getElementById('vgal-tanggal').textContent = d.tanggal || '—';
  document.getElementById('vgal-desk').textContent    = d.desk || '—';
  new bootstrap.Modal(document.getElementById('modalViewGaleri')).show();
}
</script>
</main>
<?php include "template/footer.php"; ?>
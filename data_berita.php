<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── PAGINATION ── */
$limit        = 5;
$halaman      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman      = ($halaman < 1) ? 1 : $halaman;
$offset       = ($halaman - 1) * $limit;
$totalData    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM berita"));
$totalHalaman = ceil($totalData / $limit);
$data = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Data Berita</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item active">Data Berita</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Daftar Berita</h5>
          <?php if (can_write()): ?>
          <a href="input_berita.php" class="btn btn-sm btn-primary" style="border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i>Tambah Berita
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
                <th>Isi Berita</th>
                <th width="100">Gambar</th>
                <th width="110">Tanggal</th>
                <th>Penulis</th>
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
                <td><?= htmlspecialchars($d['judul']) ?></td>
                <td><?= htmlspecialchars(strip_tags(mb_substr($d['isi'], 0, 80))) ?>...</td>
                <td>
                  <?php if (!empty($d['gambar'])): ?>
                    <img src="upload/<?= $d['gambar'] ?>" width="80" class="img-thumbnail">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td><?= $d['tanggal'] ?></td>
                <td><?= htmlspecialchars($d['penulis']) ?></td>
                <td class="text-center">

                    <button class="btn btn-sm btn-info me-1" title="Lihat Detail"
                    onclick="viewBerita({
                        judul:   '<?= htmlspecialchars($d['judul'], ENT_QUOTES) ?>',
                        isi:     <?= json_encode(strip_tags($d['isi'])) ?>,
                        penulis: '<?= htmlspecialchars($d['penulis'], ENT_QUOTES) ?>',
                        tanggal: '<?= $d['tanggal'] ?>',
                        gambar:  '<?= $d['gambar'] ?>'
                    })">
                    <i class="bi bi-eye-fill"></i>
                    </button>

                  <?php if (can_write()): ?>
                    <a href="edit_berita.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="hapus_berita.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" title="Hapus"
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
                  echo "<tr><td colspan='7' class='text-center'>Data kosong</td></tr>";
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
  
  <!-- MODAL VIEW BERITA -->
<div class="modal fade" id="modalViewBerita" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="border-radius:16px;border:none;">

      <div class="modal-header border-0 px-4 pt-4 pb-2">
        <h5 class="fw-bold mb-0" style="font-family:'Outfit',sans-serif;">
          <i class="bi bi-newspaper text-primary me-2"></i>Detail Berita
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4 pb-4">
        <div id="vb-gambar-wrap" class="mb-3"></div>
        <h4 id="vb-judul" style="font-weight:800;color:#0F172A;font-family:'Outfit',sans-serif;"></h4>

        <div class="d-flex gap-3 mb-3" style="font-size:12px;color:#64748B;">
          <span><i class="bi bi-person-fill me-1"></i><span id="vb-penulis"></span></span>
          <span><i class="bi bi-calendar3 me-1"></i><span id="vb-tanggal"></span></span>
        </div>

        <div style="height:1px;background:#F1F5F9;margin-bottom:16px;"></div>

        <div id="vb-isi"
             style="font-size:14px;color:#334155;line-height:1.8;white-space:pre-wrap;max-height:300px;overflow-y:auto;">
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function viewBerita(d) {
  document.getElementById('vb-gambar-wrap').innerHTML = d.gambar
    ? `<img src="upload/${d.gambar}" style="width:100%;height:200px;object-fit:cover;border-radius:12px;">`
    : '';
  document.getElementById('vb-judul').textContent   = d.judul;
  document.getElementById('vb-penulis').textContent = d.penulis || '—';
  document.getElementById('vb-tanggal').textContent = d.tanggal || '—';
  document.getElementById('vb-isi').textContent     = d.isi || '—';
  new bootstrap.Modal(document.getElementById('modalViewBerita')).show();
}
</script>
</main>
<?php include "template/footer.php"; ?>
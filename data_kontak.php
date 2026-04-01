<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── PAGINATION ── */
$limit        = 5;
$halaman      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman      = ($halaman < 1) ? 1 : $halaman;
$offset       = ($halaman - 1) * $limit;
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
          <?php if (can_write()): ?>
          <a href="input_kontak.php" class="btn btn-sm btn-primary" style="border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kontak
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
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th width="150">Tanggal Kirim</th>
                <th width="160" class="text-center">Aksi</th>
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

                  <button class="btn btn-sm btn-info me-1 btn-view-kontak" title="Lihat Detail"
                    data-nama="<?= htmlspecialchars($d['nama'], ENT_QUOTES) ?>"
                    data-email="<?= htmlspecialchars($d['email'], ENT_QUOTES) ?>"
                    data-pesan="<?= htmlspecialchars($d['pesan'], ENT_QUOTES) ?>"
                    data-tanggal="<?= htmlspecialchars($d['tanggal_kirim'], ENT_QUOTES) ?>">
                    <i class="bi bi-eye-fill"></i>
                  </button>

                  <?php if (can_write()): ?>
                    <a href="edit_kontak.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="hapus_kontak.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" title="Hapus"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
  <!-- MODAL VIEW KONTAK — wajib ada, jangan dihapus -->
<div class="modal fade" id="modalViewKontak" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">

      <div class="modal-header border-0 px-4 pt-4 pb-0">
        <h5 class="fw-bold mb-0" style="font-family:'Outfit',sans-serif;">
          <i class="bi bi-envelope-fill text-warning me-2"></i>Detail Pesan Kontak
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4 pb-4">
        <div class="d-flex align-items-center gap-3 p-3 mb-3"
             style="background:#F8FAFC;border-radius:12px;">
          <div id="vk-avatar"></div>
          <div>
            <div id="vk-nama"    style="font-weight:700;font-size:15px;color:#0F172A;"></div>
            <div id="vk-email"   style="font-size:12px;color:#3B82F6;"></div>
            <div id="vk-tanggal" style="font-size:11px;color:#94A3B8;margin-top:2px;"></div>
          </div>
        </div>
        <div style="background:#F8FAFC;border-radius:12px;padding:16px;">
          <div style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Isi Pesan</div>
          <p id="vk-pesan"
             style="font-size:14px;color:#334155;line-height:1.8;margin:0;white-space:pre-wrap;max-height:200px;overflow-y:auto;"></p>
        </div>
      </div>

    </div>
  </div>
</div>
  <script>
// Pakai event delegation — aman untuk konten dinamis/pagination
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-view-kontak');
  if (!btn) return;

  const nama    = btn.dataset.nama    || '—';
  const email   = btn.dataset.email   || '—';
  const pesan   = btn.dataset.pesan   || '—';
  const tanggal = btn.dataset.tanggal || '—';

  // Avatar inisial
  const initials = nama.trim().split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
  document.getElementById('vk-avatar').innerHTML =
    `<div style="width:46px;height:46px;border-radius:12px;
                 background:linear-gradient(135deg,#F97316,#EAB308);
                 display:flex;align-items:center;justify-content:center;
                 font-size:18px;font-weight:800;color:#fff;">${initials}</div>`;

  document.getElementById('vk-nama').textContent    = nama;
  document.getElementById('vk-email').textContent   = email;
  document.getElementById('vk-tanggal').textContent = tanggal;
  document.getElementById('vk-pesan').textContent   = pesan;

  new bootstrap.Modal(document.getElementById('modalViewKontak')).show();
});
</script>
</main>
<?php include "template/footer.php"; ?>
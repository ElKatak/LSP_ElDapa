<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── HAPUS (admin only) ── */
if (isset($_GET['hapus'])) {
    require_admin('data_guru.php');
    $id = (int)$_GET['hapus'];
    $q  = mysqli_query($koneksi, "SELECT foto FROM guru WHERE id='$id'");
    $d  = mysqli_fetch_assoc($q);
    if ($d) {
        if (!empty($d['foto']) && file_exists("upload/" . $d['foto'])) {
            unlink("upload/" . $d['foto']);
        }
        mysqli_query($koneksi, "DELETE FROM guru WHERE id='$id'");
    }
    echo "<script>alert('Data guru berhasil dihapus');window.location='data_guru.php';</script>";
    exit;
}

/* ── EDIT (admin only) ── */
if (isset($_POST['edit'])) {
    require_admin('data_guru.php');
    $id            = (int)$_POST['id'];
    $nama_guru     = $_POST['nama_guru'];
    $nip           = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $mapel         = $_POST['mapel'];
    $email         = $_POST['email'];
    $no_hp         = $_POST['no_hp'];
    $foto_lama     = $_POST['foto_lama'];

    if (!empty($_FILES['foto']['name'])) {
        $foto_baru = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $foto_baru);
        if (!empty($foto_lama) && file_exists("upload/" . $foto_lama)) {
            unlink("upload/" . $foto_lama);
        }
    } else {
        $foto_baru = $foto_lama;
    }

    $ng = mysqli_real_escape_string($koneksi, $nama_guru);
    $np = mysqli_real_escape_string($koneksi, $nip);
    $jk = mysqli_real_escape_string($koneksi, $jenis_kelamin);
    $mp = mysqli_real_escape_string($koneksi, $mapel);
    $em = mysqli_real_escape_string($koneksi, $email);
    $hp = mysqli_real_escape_string($koneksi, $no_hp);
    $ft = mysqli_real_escape_string($koneksi, $foto_baru);

    mysqli_query($koneksi, "UPDATE guru SET nama_guru='$ng', nip='$np', jenis_kelamin='$jk', mapel='$mp', foto='$ft', email='$em', no_hp='$hp' WHERE id='$id'");
    echo "<script>alert('Data guru berhasil diupdate');window.location='data_guru.php';</script>";
    exit;
}

/* ── PAGINATION ── */
$limit        = 5;
$halaman      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$halaman      = ($halaman < 1) ? 1 : $halaman;
$offset       = ($halaman - 1) * $limit;
$totalData    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM guru"));
$totalHalaman = ceil($totalData / $limit);
$data = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Data Guru</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item active">Data Guru</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Daftar Guru</h5>
          <?php if (can_write()): ?>
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
                <th>Nama Guru</th>
                <th>NIP</th>
                <th>JK</th>
                <th>Mapel</th>
                <th width="80">Foto</th>
                <th>Email</th>
                <th>No HP</th>
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
                <td><?= htmlspecialchars($d['nama_guru']) ?></td>
                <td><?= $d['nip'] ?></td>
                <td><?= $d['jenis_kelamin'] ?></td>
                <td><?= $d['mapel'] ?></td>
                <td>
                  <?php if ($d['foto']): ?>
                    <img src="upload/<?= $d['foto'] ?>" width="60" class="img-thumbnail">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td><?= htmlspecialchars($d['email']) ?></td>
                <td><?= $d['no_hp'] ?></td>
                <td class="text-center">

                    <button class="btn btn-sm btn-info me-1 btn-view-guru" title="Lihat Detail"
  data-nama="<?= htmlspecialchars($d['nama_guru'], ENT_QUOTES) ?>"
  data-nip="<?= htmlspecialchars($d['nip'], ENT_QUOTES) ?>"
  data-jk="<?= htmlspecialchars($d['jenis_kelamin'], ENT_QUOTES) ?>"
  data-mapel="<?= htmlspecialchars($d['mapel'], ENT_QUOTES) ?>"
  data-email="<?= htmlspecialchars($d['email'], ENT_QUOTES) ?>"
  data-hp="<?= htmlspecialchars($d['no_hp'], ENT_QUOTES) ?>"
  data-foto="<?= htmlspecialchars($d['foto'], ENT_QUOTES) ?>">
  <i class="bi bi-eye-fill"></i>
</button>

                  <?php if (can_write()): ?>
                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#edit<?= $d['id'] ?>" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger" title="Hapus"
                       onclick="return confirm('Yakin hapus data ini?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  <?php else: ?>
                    <span style="font-size:11px;color:#94A3B8;">—</span>
                  <?php endif; ?>
                </td>
              </tr>

              <?php if (can_write()): ?>
              <!-- MODAL EDIT (hanya untuk admin) -->
              <div class="modal fade" id="edit<?= $d['id'] ?>">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                        <input type="hidden" name="foto_lama" value="<?= $d['foto'] ?>">
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">Nama Guru</label>
                            <input type="text" name="nama_guru" class="form-control" value="<?= htmlspecialchars($d['nama_guru']) ?>" required>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control" value="<?= $d['nip'] ?>">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                              <option value="Laki-Laki" <?= ($d['jenis_kelamin']=='Laki-Laki')?'selected':'' ?>>Laki-Laki</option>
                              <option value="Perempuan" <?= ($d['jenis_kelamin']=='Perempuan')?'selected':'' ?>>Perempuan</option>
                            </select>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">Mata Pelajaran</label>
                            <input type="text" name="mapel" class="form-control" value="<?= htmlspecialchars($d['mapel']) ?>">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($d['email']) ?>">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="fw-semibold">No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= $d['no_hp'] ?>">
                          </div>
                          <div class="col-12 mb-3">
                            <label class="fw-semibold">Foto</label><br>
                            <?php if ($d['foto']): ?>
                              <img src="upload/<?= $d['foto'] ?>" width="80" class="mb-2 rounded"><br>
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak diganti</small>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <?php endif; // can_write() ?>

              <?php
                  }
              } else {
                  echo "<tr><td colspan='9' class='text-center'>Data kosong</td></tr>";
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
  <!-- MODAL VIEW GURU -->
<div class="modal fade" id="modalViewGuru" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">

      <div class="modal-header border-0 pb-0 px-4 pt-4">
        <h5 class="fw-bold mb-0" style="font-family:'Outfit',sans-serif;">
          <i class="bi bi-person-badge-fill text-primary me-2"></i>Detail Guru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4 pb-4">
        <div class="d-flex align-items-center gap-4 mb-4 p-3"
             style="background:#F8FAFC;border-radius:12px;">
          <div id="vg-foto-wrap"></div>
          <div>
            <div id="vg-nama" style="font-size:20px;font-weight:800;color:#0F172A;font-family:'Outfit',sans-serif;"></div>
            <div id="vg-mapel" style="font-size:13px;color:#64748B;margin-top:3px;"></div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div style="background:#F8FAFC;border-radius:10px;padding:14px 16px;">
              <div style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">NIP</div>
              <div id="vg-nip" style="font-size:14px;font-weight:600;color:#1E293B;"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="background:#F8FAFC;border-radius:10px;padding:14px 16px;">
              <div style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Jenis Kelamin</div>
              <div id="vg-jk" style="font-size:14px;font-weight:600;color:#1E293B;"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="background:#F8FAFC;border-radius:10px;padding:14px 16px;">
              <div style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Email</div>
              <div id="vg-email" style="font-size:14px;font-weight:600;color:#1E293B;"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="background:#F8FAFC;border-radius:10px;padding:14px 16px;">
              <div style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">No. HP</div>
              <div id="vg-hp" style="font-size:14px;font-weight:600;color:#1E293B;"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-view-guru');
  if (!btn) return;

  const nama  = btn.dataset.nama  || '—';
  const nip   = btn.dataset.nip   || '—';
  const jk    = btn.dataset.jk    || '—';
  const mapel = btn.dataset.mapel || '—';
  const email = btn.dataset.email || '—';
  const hp    = btn.dataset.hp    || '—';
  const foto  = btn.dataset.foto  || '';

  const initials = nama.trim().split(' ').map(w => w[0] || '').join('').substring(0, 2).toUpperCase();
  document.getElementById('vg-foto-wrap').innerHTML = foto
    ? `<img src="upload/${foto}" style="width:70px;height:70px;border-radius:14px;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,.12);">`
    : `<div style="width:70px;height:70px;border-radius:14px;background:linear-gradient(135deg,#3B82F6,#06B6D4);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;">${initials}</div>`;

  document.getElementById('vg-nama').textContent  = nama;
  document.getElementById('vg-mapel').textContent = mapel ? '📚 ' + mapel : '—';
  document.getElementById('vg-nip').textContent   = nip;
  document.getElementById('vg-jk').textContent    = jk;
  document.getElementById('vg-email').textContent = email;
  document.getElementById('vg-hp').textContent    = hp;

  new bootstrap.Modal(document.getElementById('modalViewGuru')).show();
});
</script>
</main>
<?php include "template/footer.php"; ?>
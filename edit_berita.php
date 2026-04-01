<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";
require_admin('data_berita.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id='$id'");
$data  = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan');window.location='data_berita.php';</script>";
    exit;
}
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Edit Berita</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item"><a href="data_berita.php">Data Berita</a></li>
            <li class="breadcrumb-item active">Edit Berita</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">
      <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-header border-0 pt-3 px-4">
            <h5 class="fw-bold mb-0">Form Edit Berita</h5>
          </div>
          <div class="card-body px-4">
            <form action="proses_edit_berita.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= $data['id'] ?>">
              <div class="mb-3">
                <label class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Isi Berita</label>
                <textarea name="isi" id="editor" rows="10" class="form-control" required><?= htmlspecialchars($data['isi']) ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small><br>
                <?php if (!empty($data['gambar'])): ?>
                  <img src="upload/<?= $data['gambar'] ?>" width="150" class="mt-2 rounded">
                <?php endif; ?>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($data['tanggal'])) ?>" readonly disabled style="background:#f8f9fa;cursor:not-allowed;">
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Penulis</label>
                <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($data['penulis']) ?>" required>
              </div>
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
                <a href="data_berita.php" class="btn btn-secondary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "template/footer.php"; ?>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editor');</script>
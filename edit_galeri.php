<?php
include "../koneksi.php";
include "template/header.php";
include "template/menu.php";
require_admin('data_galeri.php');

$id = (int)$_GET['id'];
$q  = mysqli_query($koneksi, "SELECT * FROM galeri WHERE id='$id'");
$d  = mysqli_fetch_assoc($q);

if (!$d) {
    echo "<script>alert('Data tidak ditemukan');window.location='data_galeri.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul       = $_POST['judul'];
    $deskripsi   = strip_tags($_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama'];
    // tanggal tidak diambil dari POST, tetap pakai yang lama
    $tanggal_upload = $d['tanggal_upload'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar_baru = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'upload/' . $gambar_baru);
        if ($gambar_lama && file_exists('upload/' . $gambar_lama)) {
            unlink('upload/' . $gambar_lama);
        }
    } else {
        $gambar_baru = $gambar_lama;
    }

    $j = mysqli_real_escape_string($koneksi, $judul);
    $ds = mysqli_real_escape_string($koneksi, $deskripsi);
    $g  = mysqli_real_escape_string($koneksi, $gambar_baru);

    mysqli_query($koneksi, "UPDATE galeri SET judul='$j', deskripsi='$ds', gambar='$g', tanggal_upload='$tanggal_upload' WHERE id='$id'");
    echo "<script>alert('Galeri berhasil diupdate');window.location='data_galeri.php';</script>";
    exit;
}
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Edit Galeri</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item"><a href="data_galeri.php">Data Galeri</a></li>
            <li class="breadcrumb-item active">Edit Galeri</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-header border-0 pt-3 px-4">
            <h5 class="fw-bold mb-0">Form Edit Galeri</h5>
          </div>
          <div class="card-body px-4">
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="gambar_lama" value="<?= $d['gambar'] ?>">
              <div class="mb-3">
                <label class="form-label fw-semibold">Judul Galeri</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($d['judul']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" id="editor" rows="6" class="form-control"><?= htmlspecialchars($d['deskripsi']) ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Gambar</label><br>
                <?php if ($d['gambar']): ?>
                  <img src="upload/<?= $d['gambar'] ?>" width="150" class="mb-2 rounded"><br>
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Upload</label>
                <input type="text" class="form-control" value="<?= $d['tanggal_upload'] ?>" readonly disabled style="background:#f8f9fa;cursor:not-allowed;">
              </div>
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
                <a href="data_galeri.php" class="btn btn-secondary">Kembali</a>
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
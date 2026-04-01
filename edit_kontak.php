<?php
include "template/header.php";
include "template/menu.php";
include "../koneksi.php";
require_admin('data_kontak.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id    = (int)$_POST['id'];
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    mysqli_query($koneksi, "UPDATE kontak SET nama='$nama', email='$email', pesan='$pesan' WHERE id='$id'");
    echo "<script>alert('Data kontak berhasil diupdate');window.location='data_kontak.php';</script>";
    exit;
}

// Ambil data
$query = mysqli_query($koneksi, "SELECT * FROM kontak WHERE id='$id'");
$data  = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan');window.location='data_kontak.php';</script>";
    exit;
}
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Edit Kontak</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item"><a href="data_kontak.php">Data Kontak</a></li>
            <li class="breadcrumb-item active">Edit Kontak</li>
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
            <h5 class="fw-bold mb-0">Form Edit Kontak</h5>
          </div>
          <div class="card-body px-4">
            <form method="post">
              <input type="hidden" name="id" value="<?= $data['id'] ?>">
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>">
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Pesan</label>
                <textarea name="pesan" class="form-control" rows="4"><?= htmlspecialchars($data['pesan']) ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Kirim</label>
                <input type="text" class="form-control" value="<?= $data['tanggal_kirim'] ?>" readonly disabled style="background:#f8f9fa;cursor:not-allowed;">
              </div>
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
                <a href="data_kontak.php" class="btn btn-secondary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "template/footer.php"; ?>
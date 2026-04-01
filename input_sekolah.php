<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

include "template/header.php";
include "template/menu.php";
include "../koneksi.php";

/* ── Hanya admin yang boleh akses halaman ini ── */
require_admin('data_sekolah.php');

/* ── Cek apakah profil sudah ada (max 1 record) ── */
$total_profil = (int)mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) c FROM profil_sekolah")
)['c'];

if ($total_profil >= 1) {
    echo "<script>alert('Profil sekolah sudah ada. Hapus data lama terlebih dahulu untuk menambah profil baru, atau gunakan Edit.');window.location='data_sekolah.php';</script>";
    exit;
}

/* ── PROSES SIMPAN ── */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sekolah   = $_POST['nama_sekolah'];
    $npsn           = $_POST['npsn'];
    $alamat         = $_POST['alamat'];
    $desa           = $_POST['desa'];
    $kecamatan      = $_POST['kecamatan'];
    $kabupaten      = $_POST['kabupaten'];
    $provinsi       = $_POST['provinsi'];
    $email          = $_POST['email'];
    $telepon        = $_POST['telepon'];
    $website        = $_POST['website'];
    $kepala_sekolah = $_POST['kepala_sekolah'];
    $visi           = strip_tags($_POST['visi']);
    $misi           = strip_tags($_POST['misi']);
    $deskripsi      = strip_tags($_POST['deskripsi']);

    $logo = '';
    if (!empty($_FILES['logo']['name'])) {
        $logo = time() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], 'upload/' . $logo);
    }

    mysqli_query($koneksi, "INSERT INTO profil_sekolah
        (nama_sekolah,npsn,alamat,desa,kecamatan,kabupaten,provinsi,email,telepon,website,kepala_sekolah,logo,visi,misi,deskripsi)
        VALUES
        ('" . mysqli_real_escape_string($koneksi,$nama_sekolah) . "',
         '" . mysqli_real_escape_string($koneksi,$npsn) . "',
         '" . mysqli_real_escape_string($koneksi,$alamat) . "',
         '" . mysqli_real_escape_string($koneksi,$desa) . "',
         '" . mysqli_real_escape_string($koneksi,$kecamatan) . "',
         '" . mysqli_real_escape_string($koneksi,$kabupaten) . "',
         '" . mysqli_real_escape_string($koneksi,$provinsi) . "',
         '" . mysqli_real_escape_string($koneksi,$email) . "',
         '" . mysqli_real_escape_string($koneksi,$telepon) . "',
         '" . mysqli_real_escape_string($koneksi,$website) . "',
         '" . mysqli_real_escape_string($koneksi,$kepala_sekolah) . "',
         '" . mysqli_real_escape_string($koneksi,$logo) . "',
         '" . mysqli_real_escape_string($koneksi,$visi) . "',
         '" . mysqli_real_escape_string($koneksi,$misi) . "',
         '" . mysqli_real_escape_string($koneksi,$deskripsi) . "')
    ");

    tambah_notif('profil', 'Profil sekolah ditambahkan', 'Data ' . $nama_sekolah . ' berhasil disimpan');
    echo "<script>alert('Profil sekolah berhasil disimpan');window.location='data_sekolah.php';</script>";
    exit;
}
?>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Input Profil Sekolah</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="hal_admin.php">Home</a></li>
            <li class="breadcrumb-item active">Input Profil Sekolah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header border-0 pt-3 px-4">
          <h5 class="fw-bold mb-0">Form Profil Sekolah</h5>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6 mb-3"><label class="fw-semibold">Nama Sekolah</label><input type="text" name="nama_sekolah" class="form-control" required></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">NPSN</label><input type="text" name="npsn" class="form-control"></div>
              <div class="col-md-12 mb-3"><label class="fw-semibold">Alamat</label><textarea name="alamat" class="form-control" rows="3"></textarea></div>
              <div class="col-md-4 mb-3"><label class="fw-semibold">Desa</label><input type="text" name="desa" class="form-control"></div>
              <div class="col-md-4 mb-3"><label class="fw-semibold">Kecamatan</label><input type="text" name="kecamatan" class="form-control"></div>
              <div class="col-md-4 mb-3"><label class="fw-semibold">Kabupaten</label><input type="text" name="kabupaten" class="form-control"></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Provinsi</label><input type="text" name="provinsi" class="form-control"></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Kepala Sekolah</label><input type="text" name="kepala_sekolah" class="form-control"></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Email</label><input type="email" name="email" class="form-control"></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Telepon</label><input type="text" name="telepon" class="form-control"></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Website</label><input type="text" name="website" class="form-control"></div>
              <div class="col-md-6 mb-3">
                <label class="fw-semibold">Logo Sekolah</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
              </div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Visi</label><textarea name="visi" id="visi" class="form-control" rows="5"></textarea></div>
              <div class="col-md-6 mb-3"><label class="fw-semibold">Misi</label><textarea name="misi" id="misi" class="form-control" rows="5"></textarea></div>
              <div class="col-12 mb-3"><label class="fw-semibold">Deskripsi Sekolah</label><textarea name="deskripsi" id="deskripsi" class="form-control" rows="5"></textarea></div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
              <a href="data_sekolah.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include "template/footer.php"; ?>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('visi');
  CKEDITOR.replace('misi');
  CKEDITOR.replace('deskripsi');
</script>
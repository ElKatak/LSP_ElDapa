<?php
session_start();
require_once 'template/role_helper.php';

// Guard: hanya admin
if (!is_admin()) {
    echo "<script>alert('Akses ditolak. Anda tidak memiliki izin untuk menghapus data.');window.location='data_berita.php';</script>";
    exit;
}

include '../koneksi.php';

$id = (int)$_GET['id'];

$q = mysqli_query($koneksi, "SELECT gambar FROM berita WHERE id='$id'");
$d = mysqli_fetch_assoc($q);
if ($d && !empty($d['gambar']) && file_exists("upload/" . $d['gambar'])) {
    unlink("upload/" . $d['gambar']);
}

mysqli_query($koneksi, "DELETE FROM berita WHERE id='$id'");
header("location:data_berita.php");
exit;
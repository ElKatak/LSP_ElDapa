<?php
include '../koneksi.php';

$id = (int)$_GET['id'];

// Ambil nama gambar sebelum dihapus
$q = mysqli_query($koneksi, "SELECT gambar FROM berita WHERE id='$id'");
$d = mysqli_fetch_assoc($q);
if ($d && !empty($d['gambar']) && file_exists("upload/" . $d['gambar'])) {
    unlink("upload/" . $d['gambar']);
}

mysqli_query($koneksi, "DELETE FROM berita WHERE id='$id'");
header("location:data_berita.php");
<?php
session_start();
require_once 'template/role_helper.php';

// Guard: hanya admin
if (!is_admin()) {
    echo "<script>alert('Akses ditolak. Anda tidak memiliki izin untuk menghapus data.');window.location='data_kontak.php';</script>";
    exit;
}

include "../koneksi.php";

$id = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM kontak WHERE id='$id'");
header("location:data_kontak.php");
exit;
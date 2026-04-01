<?php
session_start();
include '../koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$data = mysqli_query($koneksi,
    "SELECT * FROM admin WHERE username='" . mysqli_real_escape_string($koneksi, $username) .
    "' AND password='" . mysqli_real_escape_string($koneksi, $password) . "' LIMIT 1"
);

$cek = mysqli_num_rows($data);
if ($cek > 0) {
    $row = mysqli_fetch_assoc($data);
    $_SESSION['username']   = $username;
    $_SESSION['status']     = 'login';
    $_SESSION['role']       = $row['role'] ?? 'admin'; // 'admin' atau 'user'
    $_SESSION['nama_admin'] = $row['nama_admin'];
    $_SESSION['id_admin']   = $row['id'];
    header("location:hal_admin.php");
} else {
    header("location:index.php?pesan=gagal");
}
exit;
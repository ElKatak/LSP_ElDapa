<?php
session_start();
include "../koneksi.php";
include "template/notif_helper.php";
require_admin('data_kontak.php');

$nama          = $_POST['nama'];
$email         = $_POST['email'];
$pesan         = $_POST['pesan'];
$tanggal_kirim = date('Y-m-d H:i:s');

$n = mysqli_real_escape_string($koneksi, $nama);
$e = mysqli_real_escape_string($koneksi, $email);
$p = mysqli_real_escape_string($koneksi, $pesan);
$t = mysqli_real_escape_string($koneksi, $tanggal_kirim);

mysqli_query($koneksi, "INSERT INTO kontak (nama, email, pesan, tanggal_kirim) VALUES ('$n','$e','$p','$t')");

tambah_notif('kontak', 'Pesan kontak baru masuk', $nama . ' mengirim pesan baru');

header("location:data_kontak.php");
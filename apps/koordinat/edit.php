<?php
// Include database connection
include '../../config/database.php';

if (isset($_POST['ubah_aplikasi'])) {
    // Ambil data dari form
    $id_koordinat = $_POST['id_koordinat'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude']; // Pastikan nama ini sesuai dengan input form
    $radius = $_POST['radius'];

    // // Validasi data
    // if (!is_numeric($latitude) || !is_numeric($longitude) || !is_numeric($radius)) {
    //     header("Location: ../../index.php?page=koordinat&edit=gagal");
    //     exit;
    // }

    // Pastikan radius bernilai positif
    if ($radius <= 0) {
        header("Location: ../../index.php?page=koordinat&edit=gagal");
        exit;
    }

    // Query untuk update data koordinat
    $query = "UPDATE tbl_koordinat 
              SET latitude = '$latitude', longitude = '$longitude', radius = '$radius' 
              WHERE id_koordinat = '$id_koordinat'";

    if (mysqli_query($kon, $query)) {
        // Jika berhasil
        header("Location: ../../index.php?page=koordinat&edit=berhasil");
        exit;
    } else {
        // Jika gagal
        header("Location: ../../index.php?page=koordinat&edit=gagal");
        exit;
    }
} else {
    // Jika file diakses langsung tanpa form
    header("Location: ../../index.php?page=koordinat");
    exit;
}

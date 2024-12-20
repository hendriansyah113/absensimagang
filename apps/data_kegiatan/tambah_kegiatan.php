<?php
include '../../config/database.php';

if (isset($_POST['nama_kegiatan'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $query = "INSERT INTO tbl_kegiatan_list (nama_kegiatan) VALUES ('$nama_kegiatan')";
    if (mysqli_query($kon, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}

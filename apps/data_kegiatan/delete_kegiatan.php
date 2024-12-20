<?php
include '../../config/database.php';

if (isset($_POST['nama_kegiatan'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'];

    // Dapatkan ID kegiatan berdasarkan nama kegiatan
    $query = "SELECT id FROM tbl_kegiatan_list WHERE nama_kegiatan='$nama_kegiatan'";
    $result = mysqli_query($kon, $query);
    $data = mysqli_fetch_assoc($result);
    $id = $data['id'];

    // Hapus kegiatan berdasarkan ID
    $query = "DELETE FROM tbl_kegiatan_list WHERE id='$id'";
    if (mysqli_query($kon, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}

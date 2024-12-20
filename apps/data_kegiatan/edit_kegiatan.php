<?php
include '../../config/database.php';

if (isset($_POST['nama_kegiatan_lama']) && isset($_POST['nama_kegiatan_baru'])) {
    $nama_kegiatan_lama = $_POST['nama_kegiatan_lama'];
    $nama_kegiatan_baru = $_POST['nama_kegiatan_baru'];

    // Dapatkan ID kegiatan berdasarkan nama kegiatan lama
    $query = "SELECT id FROM tbl_kegiatan_list WHERE nama_kegiatan='$nama_kegiatan_lama'";
    $result = mysqli_query($kon, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $id = $data['id'];

        // Update nama kegiatan
        $query = "UPDATE tbl_kegiatan_list SET nama_kegiatan='$nama_kegiatan_baru' WHERE id='$id'";
        if (mysqli_query($kon, $query)) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}

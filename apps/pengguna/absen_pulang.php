<?php
session_start();
include '../../config/database.php';

if (isset($_POST['id_mahasiswa'])) {
    $id_mahasiswa = $_POST['id_mahasiswa'];

    // Memeriksa apakah mahasiswa sudah absen masuk
    $sql = "SELECT * FROM tbl_absensi WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = CURDATE()";
    $result = mysqli_query($kon, $sql);
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        if ($data['status'] == 'Hadir') {
            // Update waktu pulang
            $update_sql = "UPDATE tbl_absensi SET waktu_pulang = NOW() WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = CURDATE()";
            if (mysqli_query($kon, $update_sql)) {
                echo "Berhasil Absen Pulang";
            } else {
                echo "Gagal Mengupdate Waktu Pulang";
            }
        } else {
            echo "Anda belum absen masuk hari ini";
        }
    } else {
        echo "Anda belum absen hari ini";
    }
}
?>

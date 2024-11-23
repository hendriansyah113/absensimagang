<?php
if (isset($_POST['simpan_kegiatan'])) {
    include '../../config/database.php';
    $id_mahasiswa = $_POST['mahasiswa'];
    $tanggal = $_POST['tanggal'];
    $waktu_awal = $_POST['waktu_awal'];
    $waktu_akhir = $_POST['waktu_akhir'];
    $kegiatan = $_POST['kegiatan'];

    // Validasi dan proses upload file
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        $file_ext = pathinfo($foto, PATHINFO_EXTENSION);
        $file_size = $_FILES['foto']['size'];

        if (in_array(strtolower($file_ext), $allowed_ext) && $file_size <= 2097152) {
            // Buat nama file unik
            $unique_name = uniqid('kegiatan_', true) . '.' . $file_ext;
            $upload_dir = "../../uploads/kegiatan/";

            // Pindahkan file ke direktori tujuan
            move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $unique_name);

            // Simpan data kegiatan ke database
            $sql = "INSERT INTO tbl_kegiatan (id_mahasiswa, tanggal, waktu_awal, waktu_akhir, kegiatan, file_upload) VALUES ('$id_mahasiswa', '$tanggal', '$waktu_awal', '$waktu_akhir', '$kegiatan', '$unique_name')";
            $result = mysqli_query($kon, $sql);
            if ($result) {
                echo "<script>alert('Kegiatan berhasil disimpan!'); window.location.href = 'http://localhost/absensimagang/index.php?page=data_kegiatan';</script>";
            } else {
                echo "<div class='alert alert-danger'>Gagal menyimpan kegiatan.</div>";
                echo "<script>window.location.href = 'http://localhost/absensimagang/index.php?page=data_kegiatan';</script>";
            }
        } else {
            echo "<script>alert('File tidak valid. Pastikan format gambar dan ukuran file sesuai.'); window.location.href = 'http://localhost/absensimagang/index.php?page=data_kegiatan';</script>";
        }
    } else {
        echo "<div class='alert alert-danger'>Gagal mengunggah foto.</div>";
        echo "<script>window.location.href = 'http://localhost/absensimagang/index.php?page=data_kegiatan';</script>";
    }
}
?>

<form action="apps/data_kegiatan/tambah.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nama Mahasiswa :</label>
                <select class="form-control" id="mahasiswa" name="mahasiswa" required>
                    <?php
                    include '../../config/database.php';
                    $query = "SELECT id_mahasiswa, nama FROM tbl_mahasiswa";
                    $result = mysqli_query($kon, $query);
                    while ($data = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . htmlspecialchars($data['id_mahasiswa']) . "'>" . htmlspecialchars($data['nama']) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tanggal Kegiatan :</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Awal Kegiatan :</label>
                <input type="time" name="waktu_awal" id="waktu_awal" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Akhir Kegiatan:</label>
                <input type="time" name="waktu_akhir" id="waktu_akhir" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kegiatan :</label>
                <input type="text" name="kegiatan" id="kegiatan" class="form-control" required
                    placeholder="Masukkan Kegiatan Harian">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Unggah Foto :</label>
                <input type="file" name="foto" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="simpan_kegiatan" id="simpan_kegiatan" class="btn btn-success"><i
                        class="fa fa-plus"></i> Simpan</button>
                <button type="reset" class="btn btn-warning"><i class="fa fa-trash"></i> Reset</button>
            </div>
        </div>
    </div>
</form>
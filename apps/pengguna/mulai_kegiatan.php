<?php
session_start();
if (isset($_POST['simpan_kegiatan'])) {

    include '../../config/database.php';
    function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $id_mahasiswa = $_SESSION["id_mahasiswa"];
    date_default_timezone_set("Asia/Jakarta");
    $kegiatan1 = $_POST["kegiatan1"];
    $kegiatan2 = $_POST["kegiatan2"];
    $waktu_awal = $_POST["waktu_awal"];
    $waktu_akhir = $_POST["waktu_akhir"];
    $tanggal = date("Y-m-d");

    // Proses upload file
    $target_dir = "../../uploads/kegiatan/";
    $file_upload = $_FILES["file_upload"]["name"];
    $fileType = strtolower(pathinfo($file_upload, PATHINFO_EXTENSION));

    // Menghasilkan nama file unik dengan uniqid() + extension file
    $uniq_name = uniqid() . "." . $fileType;
    $target_file = $target_dir . $uniq_name;

    $uploadOk = 1;

    // Validasi ukuran file
    if ($_FILES["file_upload"]["size"] > 5000000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Validasi format file
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
        echo "Maaf, hanya file JPG, JPEG, PNG, dan PDF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Cek apakah uploadOk = 0 karena error
    if ($uploadOk == 0) {
        echo "Maaf, file tidak dapat diunggah.";
    } else {
        // Jika valid, pindahkan file ke folder target
        if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
            echo "File " . htmlspecialchars(basename($uniq_name)) . " berhasil diunggah.";

            // Simpan data ke database
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $sql = "INSERT INTO tbl_kegiatan (id_mahasiswa, waktu_awal, waktu_akhir, tanggal, kegiatan1, kegiatan2, file_upload) 
                    VALUES ('$id_mahasiswa','$waktu_awal','$waktu_akhir','$tanggal', '$kegiatan1', '$kegiatan2', '$uniq_name')";

                $simpan_kegiatan = mysqli_query($kon, $sql);

                // validasi data
                if ($simpan_kegiatan) {
                    mysqli_query($kon, "COMMIT");
                    header("Location:../../index.php?page=kegiatan&tambah=berhasil");
                } else {
                    mysqli_query($kon, "ROLLBACK");
                    header("Location:../../index.php?page=kegiatan&tambah=gagal");
                }
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
        }
    }
}
?>

<form action="apps/pengguna/mulai_kegiatan.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Awal Kegiatan :</label>
                <input type="time" name="waktu_awal" class="form-control" value="" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Akhir Kegiatan :</label>
                <input type="time" name="waktu_akhir" class="form-control" value="" required>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="kegiatan1">Kegiatan 1:</label>
                <select name="kegiatan1" id="kegiatan1" class="form-control" required>
                    <option value="" disabled selected>Pilih Kegiatan Anda</option>
                    <option value="Menyalakan komputer layanan pagi">Menyalakan komputer layanan pagi</option>
                    <option value="Memasukkan berita">Memasukkan berita</option>
                    <option value="Merancang sebuah website">Merancang sebuah website</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="kegiatan2">Kegiatan 2:</label>
                <textarea name="kegiatan2" id="kegiatan2" class="form-control" rows="5"
                    placeholder="Masukkan kegiatan lainnya" required></textarea>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Upload File (jpg, jpeg, png, pdf) :</label>
                <input type="file" name="file_upload" class="form-control" accept=".jpg, .jpeg, .png, .pdf" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="simpan_kegiatan" id="simpan_kegiatan"
                    class="simpan_kegiatan btn btn-success"><i class="fa fa-plus"></i> Simpan</button>
                <button type="reset" class="btn btn-warning"><i class="fa fa-trash"></i> Hapus</button>
            </div>
        </div>
    </div>
</form>


<script>
    $('#simpan_kegiatan').on('click', function() {
        konfirmasi = confirm("Yakin ingin menyimpan kegiatan ini?")
        if (konfirmasi) {
            return true;
        } else {
            return false;
        }
    });
</script>
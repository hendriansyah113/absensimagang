<?php
session_start();
if (isset($_POST['edit_kegiatan'])) {
    include '../../config/database.php';

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $id_mahasiswa = $_POST["id_mahasiswa"];
    $id_kegiatan = $_POST["id_kegiatan"];
    $tanggal = $_POST["tanggal"];
    $waktu_awal = $_POST["waktu_awal"];
    $waktu_akhir = $_POST["waktu_akhir"];
    $kegiatan = $_POST["kegiatan"];
    
    // Handle file upload
    $foto = $_FILES['foto_kegiatan']['name'];
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($foto);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // If a file is uploaded
        if ($foto) {
            // Move the uploaded file to the designated folder
            move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], $target_file);
            
            // SQL query to update the record with the new photo
            $sql = "UPDATE tbl_kegiatan SET
            kegiatan = '$kegiatan',
            waktu_awal = '$waktu_awal',
            waktu_akhir = '$waktu_akhir',
            tanggal = '$tanggal',
            foto = '$foto' 
            WHERE id_kegiatan = '$id_kegiatan';";
        } else {
            // If no new file is uploaded, update without changing the photo
            $sql = "UPDATE tbl_kegiatan SET
            kegiatan = '$kegiatan',
            waktu_awal = '$waktu_awal',
            waktu_akhir = '$waktu_akhir',
            tanggal = '$tanggal'
            WHERE id_kegiatan = '$id_kegiatan';";
        }

        $edit_kegiatan = mysqli_query($kon, $sql);

        // Validate and commit transaction
        if ($edit_kegiatan) {
            mysqli_query($kon, "COMMIT");
            header("Location:../../index.php?page=data_kegiatan&edit=berhasil");
        } else {
            mysqli_query($kon, "ROLLBACK");
            header("Location:../../index.php?page=data_kegiatan&edit=gagal");
        }
    }
}
?>


<form action="apps/data_kegiatan/edit.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <input type="hidden" name="id_mahasiswa" value="<?php echo $_POST['id_mahasiswa']; ?>">
        <input type="hidden" name="id_kegiatan" value="<?php echo $_POST['id_kegiatan']; ?>">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tanggal Kegiatan :</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo $tanggal; ?>">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Waktu Awal Kegiatan :</label>
                <input type="time" name="waktu_awal" id="waktu_awal" class="form-control" value="<?php echo $waktu_awal; ?>">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Waktu Akhir Kegiatan:</label>
                <input type="time" name="waktu_akhir" id="waktu_akhir" class="form-control" value="<?php echo $waktu_akhir; ?>">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kegiatan :</label>
                <input type="text" name="kegiatan" id="kegiatan" class="form-control" value="<?php echo $kegiatan; ?>" placeholder="Masukkan Kegiatan Harian">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Upload Foto Kegiatan (Optional):</label> <!-- New input field for photo upload -->
                <input type="file" name="foto_kegiatan" id="foto_kegiatan" class="form-control" accept="image/*">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="edit_kegiatan" id="edit_kegiatan" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                <button type="clear" class="btn btn-warning"><i class="fa fa-trash"></i> Reset</button>
            </div>
        </div>
    </div>
</form>

<?php
if ($_SESSION["level"] != 'Admin' and $_SESSION["level"] != 'admin') {
    echo "<br><div class='alert alert-danger'>Tidak Memiliki Hak Akses</div>";
    exit;
}
?>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="index.php?page=koordinat">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Pengaturan Koordinat</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pengaturan Koordinat
            </div>
            <div class="panel-body">
                <?php

                if (isset($_GET['edit'])) {
                    if ($_GET['edit'] == 'berhasil') {
                        echo "<div class='alert alert-success'><strong>Berhasil!</strong> koordinat Koordinat Telah Diupdate</div>";
                    } else if ($_GET['edit'] == 'gagal') {
                        echo "<div class='alert alert-danger'><strong>Gagal!</strong> koordinat Koordinat Gagal Diupdate</div>";
                    }
                }
                ?>

                <?php
                //Include database
                include 'config/database.php';
                //Mengambil data profil aplikasi
                $hasil = mysqli_query($kon, "select * from tbl_koordinat");
                $data = mysqli_fetch_array($hasil);
                ?>

                <!-- Form Edit -->
                <form action="apps/koordinat/edit.php" method="post">
                    <div class="form-group">
                        <input type="hidden" class="form-control" value="<?php echo $data['id_koordinat']; ?>"
                            name="id_koordinat">
                    </div>
                    <div class="form-group">
                        <label>Latitude :</label>
                        <input type="text" id="latitude" class="form-control" value="<?php echo $data['latitude']; ?>"
                            name="latitude" placeholder="Masukkan Latitude" required oninput="validateNumber(this)">
                    </div>
                    <div class="form-group">
                        <label>Longitude :</label>
                        <input type="text" id="longitude" class="form-control" value="<?php echo $data['longitude']; ?>"
                            name="longitude" placeholder="Masukkan Longitude" required oninput="validateNumber(this)">
                    </div>
                    <div class="form-group">
                        <label>Radius :</label>
                        <input type="text" id="radius" class="form-control" value="<?php echo $data['radius']; ?>"
                            name="radius" placeholder="Masukkan Radius" required oninput="validateNumber(this)">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" name="ubah_aplikasi"><i class="fa fa-edit"></i>
                            Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Fungsi validasi angka desimal
    function validateNumber(input) {
        const value = input.value;
        const regex = /^[0-9]*\.?[0-9]*$/; // Hanya angka dan titik desimal

        if (!regex.test(value)) {
            input.value = value.slice(0, -1); // Hapus karakter terakhir jika invalid
        }
    }

    // Validasi sebelum submit form
    function validateForm() {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        const radius = document.getElementById('radius').value;

        if (!latitude || !longitude || !radius) {
            alert("Semua kolom harus diisi!");
            return false;
        }

        if (isNaN(latitude) || isNaN(longitude) || isNaN(radius)) {
            alert("Kolom Latitude, Longitude, dan Radius harus berupa angka!");
            return false;
        }

        return true; // Lolos validasi
    }
</script>
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
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Awal Kegiatan :</label>
                <input type="time" name="waktu_awal" id="waktu_awal" class="form-control" value="">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Akhir Kegiatan:</label>
                <input type="time" name="waktu_akhir" id="waktu_akhir" class="form-control" value="">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kegiatan :</label>
                <input type="text" name="kegiatan" id="kegiatan" class="form-control" value="" placeholder="Masukkan Kegiatan Harian">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Unggah Foto :</label>
                <input type="file" name="foto" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="simpan_kegiatan" id="simpan_kegiatan" class="btn btn-success"><i class="fa fa-plus"></i> Simpan</button>
                <button type="reset" class="btn btn-warning"><i class="fa fa-trash"></i> Reset</button>
            </div>
        </div>
    </div>
</form>

<?php
if (isset($_POST['simpan_kegiatan'])) {
    include '../../config/database.php';
    $id_mahasiswa = $_POST['mahasiswa'];
    $tanggal = $_POST['tanggal'];
    $waktu_awal = $_POST['waktu_awal'];
    $waktu_akhir = $_POST['waktu_akhir'];
    $kegiatan1 = $_POST['kegiatan1'];
    $kegiatan2 = $_POST['kegiatan2'];

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
            $sql = "INSERT INTO tbl_kegiatan (id_mahasiswa, tanggal, waktu_awal, waktu_akhir, kegiatan1, kegiatan2, file_upload) VALUES ('$id_mahasiswa', '$tanggal', '$waktu_awal', '$waktu_akhir', '$kegiatan1', '$kegiatan2', '$unique_name')";
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
<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group select {
        flex: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .input-group .input-group-append {
        display: flex;
    }

    .input-group .input-group-append button {
        margin-left: 5px;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #e9ecef;
        cursor: pointer;
    }

    .input-group .input-group-append button:hover {
        background-color: #dcdcdc;
    }
</style>

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
        <div class="form-group col-sm-12">
            <label for="kegiatan1">Kegiatan 1:</label>
            <div class="input-group">
                <select name="kegiatan1" id="kegiatan1" class="form-control" required>
                    <option value="" disabled selected>Pilih Kegiatan Anda</option>
                    <?php
                    $query = "SELECT id, nama_kegiatan FROM tbl_kegiatan_list";
                    $result = mysqli_query($kon, $query);
                    while ($data = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . htmlspecialchars($data['nama_kegiatan']) . "'>" . htmlspecialchars($data['nama_kegiatan']) . "</option>";
                    }
                    ?>
                    <option value="addNew">+ Tambah Baru</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="editKegiatan()">Edit</button>
                    <button class="btn btn-outline-danger" type="button" onclick="deleteKegiatan()">Hapus</button>
                </div>
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
                <label>Unggah Foto :</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
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
<script>
    document.getElementById('kegiatan1').addEventListener('change', function(event) {
        if (event.target.value === 'addNew') {
            addKegiatan();
        }
    });

    function addKegiatan() {
        const newData = prompt("Masukkan nama kegiatan baru:");
        if (newData) {
            const formData = new FormData();
            formData.append('nama_kegiatan', newData);

            fetch('apps/data_kegiatan/tambah_kegiatan.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('Kegiatan berhasil ditambahkan.');
                        location.reload();
                    } else {
                        alert('Gagal menambahkan kegiatan.');
                    }
                });
        }
    }

    function editKegiatan() {
        const select = document.getElementById("kegiatan1");
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value !== 'addNew') {
            const newData = prompt("Edit nama kegiatan:", selectedOption.text);
            if (newData) {
                const formData = new FormData();
                formData.append('nama_kegiatan_lama', selectedOption.value);
                formData.append('nama_kegiatan_baru', newData);

                fetch('apps/data_kegiatan/edit_kegiatan.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            alert('Kegiatan berhasil diubah.');
                            location.reload();
                        } else {
                            alert('Gagal mengubah kegiatan.');
                        }
                    });
            }
        } else {
            alert("Pilih kegiatan yang ingin diedit.");
        }
    }

    function deleteKegiatan() {
        const select = document.getElementById("kegiatan1");
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value !== 'addNew') {
            if (confirm("Apakah Anda yakin ingin menghapus kegiatan ini?")) {
                const formData = new FormData();
                formData.append('nama_kegiatan', selectedOption.value);

                fetch('apps/data_kegiatan/delete_kegiatan.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            alert('Kegiatan berhasil dihapus.');
                            location.reload();
                        } else {
                            alert('Gagal menghapus kegiatan.');
                        }
                    });
            }
        } else {
            alert("Pilih kegiatan yang ingin dihapus.");
        }
    }
</script>
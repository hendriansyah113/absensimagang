<?php
include '../../config/database.php';

// Mendapatkan ID mahasiswa dan ID kegiatan dari AJAX
if (isset($_POST['id_mahasiswa']) && isset($_POST['id_kegiatan'])) {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_kegiatan = $_POST['id_kegiatan'];

    // Query untuk mendapatkan data kegiatan berdasarkan ID
    $query = "SELECT * FROM tbl_kegiatan WHERE id_kegiatan='$id_kegiatan'";
    $result = mysqli_query($kon, $query);
    $data = mysqli_fetch_array($result);
}

if (isset($_POST['update_kegiatan'])) {
    $id_kegiatan = $_POST['id_kegiatan'];
    $id_mahasiswa = $_POST['mahasiswa'];
    $tanggal = $_POST['tanggal'];
    $waktu_awal = $_POST['waktu_awal'];
    $waktu_akhir = $_POST['waktu_akhir'];
    $kegiatan1 = $_POST['kegiatan1'];
    $kegiatan2 = $_POST['kegiatan2'];

    // Validasi dan proses upload file jika ada file baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        $file_ext = pathinfo($foto, PATHINFO_EXTENSION);
        $file_size = $_FILES['foto']['size'];

        if (in_array(strtolower($file_ext), $allowed_ext) && $file_size <= 2097152) { // Buat nama file unik
            $unique_name = uniqid('kegiatan_', true) . '.' . $file_ext;
            $upload_dir = "../../uploads/kegiatan/";
            move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $unique_name); // Update data kegiatan ke database
            $sql = "UPDATE tbl_kegiatan SET id_mahasiswa='$id_mahasiswa', tanggal='$tanggal', waktu_awal='$waktu_awal', waktu_akhir='$waktu_akhir', kegiatan1='$kegiatan1', kegiatan2='$kegiatan2', file_upload='$unique_name' WHERE id_kegiatan='$id_kegiatan'";
        } else {
            echo "<script>alert('File tidak valid. Pastikan format gambar dan ukuran file sesuai.');</script>";
        }
    } else { // Jika tidak ada file baru, cukup update data lainnya
        $sql = "UPDATE tbl_kegiatan SET id_mahasiswa='$id_mahasiswa', tanggal='$tanggal', waktu_awal='$waktu_awal', waktu_akhir='$waktu_akhir', kegiatan1='$kegiatan1', kegiatan2='$kegiatan2' WHERE id_kegiatan='$id_kegiatan'";
    }
    $result = mysqli_query($kon, $sql);
    if ($result) {
        echo "<script>alert('Kegiatan berhasil diperbarui!'); window.location.href = 'http://localhost/absensimagang/index.php?page=data_kegiatan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui kegiatan.');</script>";
    }
} ?>

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

<form action="apps/data_kegiatan/edit.php?id=<?php echo $data['id_kegiatan']; ?>" method="post"
    enctype="multipart/form-data">
    <div class="row">
        <!-- Input hidden untuk menyimpan ID kegiatan -->
        <input type="hidden" name="id_kegiatan" value="<?php echo $data['id_kegiatan']; ?>">

        <!-- Input hidden untuk ID mahasiswa -->
        <input type="hidden" name="id_mahasiswa" value="<?php echo $data['id_mahasiswa']; ?>">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nama Mahasiswa :</label>
                <select class="form-control" id="mahasiswa" name="mahasiswa" required>
                    <?php
                    $query = "SELECT id_mahasiswa, nama FROM tbl_mahasiswa";
                    $result = mysqli_query($kon, $query);
                    while ($mahasiswa = mysqli_fetch_assoc($result)) {
                        $selected = ($mahasiswa['id_mahasiswa'] == $data['id_mahasiswa']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($mahasiswa['id_mahasiswa']) . "' $selected>" . htmlspecialchars($mahasiswa['nama']) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tanggal Kegiatan :</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control"
                    value="<?php echo htmlspecialchars($data['tanggal']); ?>" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Awal Kegiatan :</label>
                <input type="time" name="waktu_awal" id="waktu_awal" class="form-control"
                    value="<?php echo htmlspecialchars($data['waktu_awal']); ?>" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Waktu Akhir Kegiatan:</label>
                <input type="time" name="waktu_akhir" id="waktu_akhir" class="form-control"
                    value="<?php echo htmlspecialchars($data['waktu_akhir']); ?>" required>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="kegiatan1">Kegiatan 1:</label>
            <div class="input-group">
                <select name="kegiatan1" id="kegiatan1" class="form-control" required>
                    <option value="" disabled>Pilih Kegiatan Anda</option>
                    <?php
                    $query = "SELECT id, nama_kegiatan FROM tbl_kegiatan_list";
                    $result = mysqli_query($kon, $query);
                    while ($data_kegiatan = mysqli_fetch_assoc($result)) {
                        $selected = ($data_kegiatan['nama_kegiatan'] == $data['kegiatan1']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($data_kegiatan['nama_kegiatan']) . "' $selected>" . htmlspecialchars($data_kegiatan['nama_kegiatan']) . "</option>";
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
                    placeholder="Masukkan kegiatan lainnya"
                    required><?php echo htmlspecialchars($data['kegiatan2']); ?></textarea>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Unggah Foto (Kosongkan jika tidak ingin mengubah):</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="update_kegiatan" class="btn btn-success"><i class="fa fa-pencil"></i>
                    Update</button>
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
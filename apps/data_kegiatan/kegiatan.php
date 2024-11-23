<div class="row">
    <ol class="breadcrumb">
        <li><a href="index.php?page=beranda">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Kegiatan Harian</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Kegiatan Harian
                <span class="pull-right clickable panel-toggle panel-button-tab-left">
                    <em class="fa fa-toggle-up"></em>
                </span>
            </div>
            <div class="panel-body">
                <div id="div_periode" class='alert alert-warning'>
                    <strong>Periode Kegiatan Harian Selesai</strong>
                </div>
                <div class="row">
                    <form action="#" method="GET" enctype="multipart/form-data">
                        <!-- Updated to support file upload -->
                        <input type="hidden" name="page" value="kegiatan" />
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal :</label>
                                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir :</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Upload Foto Kegiatan:</label> <!-- New input for photo -->
                                <input type="file" name="foto_kegiatan" id="foto_kegiatan" class="form-control"
                                    accept="image/*" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                </br>
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->

<?php
include 'config/database.php';
$id_mahasiswa = $_SESSION["id_mahasiswa"];
$sql = "select * from tbl_mahasiswa where id_mahasiswa=$id_mahasiswa limit 1";
$hasil = mysqli_query($kon, $sql);
$data = mysqli_fetch_array($hasil);
$mulai_magang = $data['mulai_magang'];
$akhir_magang = $data['akhir_magang'];

setlocale(LC_TIME, 'id_ID');
$tanggal_sekarang = new DateTime();
$tanggal_masuk = strftime("%d %B %Y", strtotime($mulai_magang));
$tanggal_keluar = strftime("%d %B %Y", strtotime($akhir_magang));
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                if (isset($_GET['tambah'])) {
                    if ($_GET['tambah'] == 'berhasil') {
                        echo "<div class='alert alert-success'><strong>Berhasil!</strong> Menambahkan Kegiatan Harian</div>";
                    } else if ($_GET['mulai'] == 'tambah') {
                        echo "<div class='alert alert-warning'><strong>Sudah!</strong> Menambahkan Kegiatan Harian</div>";
                    }
                }

                if (isset($_FILES['foto_kegiatan']) && $_FILES['foto_kegiatan']['error'] == 0) {
                    $foto = $_FILES['foto_kegiatan']['name'];
                    $upload_dir = "uploads/";
                    move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], $upload_dir . $foto);

                    echo "<div class='alert alert-success'><strong>Foto berhasil diupload!</strong></div>";
                }
                ?>

                <div class="form-group">
                    <button id_mahasiswa="<?php echo $_SESSION['id_mahasiswa']; ?>" type="button"
                        class="btn btn-success" id="tombol_kegiatan"><i class="fa fa-plus"></i> Tambah</button>
                    <button id_mahasiswa="<?php echo $_SESSION['id_mahasiswa']; ?>"
                        class="cetak_kegiatan btn btn-primary btn-circle" id="cetak_kegiatan"><i
                            class="fa fa-print"></i> Cetak</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Hari</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Kegiatan</th>
                                <th class="text-center">Foto Kegiatan</th> <!-- New column for photos -->
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            include 'config/database.php';
                            include 'config/function.php';
                            $id_mahasiswa = $_SESSION["id_mahasiswa"];
                            if (isset($_GET['tanggal_awal']) and $_GET['tanggal_akhir']) {
                                $tanggal_awal = $_GET["tanggal_awal"];
                                $tanggal_akhir = $_GET["tanggal_akhir"];
                                $sql = MencarikanKegiatan($id_mahasiswa, $tanggal_awal, $tanggal_akhir);
                            } else {
                                $sql = MenampilkanKegiatan($id_mahasiswa);
                            }
                            $hasil = mysqli_query($kon, $sql);
                            $no = 0;
                            while ($data = mysqli_fetch_array($hasil)) {
                                $no++;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td class="text-center"><?php echo MendapatkanHari($data['hari']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $tgl = date("d", strtotime($data['tanggal']));
                                        $bulan = date("m", strtotime($data['tanggal']));
                                        $tahun = date("Y", strtotime($data['tanggal']));
                                        echo $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun;
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo WaktuKegiatan($data['kegiatan']); ?></td>
                                    <td><?php echo BarisKegiatan($data['kegiatan']); ?></td>
                                    <td class="text-center">
                                        <!-- Display the uploaded photo -->
                                        <?php if (isset($data['file_upload'])): ?>
                                            <img src="uploads/kegiatan/<?php echo $data['file_upload']; ?>" width="100"
                                                alt="Foto Kegiatan">
                                        <?php else: ?>
                                            <span>No photo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="judul"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="tampil_data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#tombol_kegiatan').on('click', function() {
        var id_mahasiswa = $(this).attr("id_mahasiswa");
        $.ajax({
            url: 'apps/pengguna/mulai_kegiatan.php',
            method: 'POST',
            data: {
                id_mahasiswa: id_mahasiswa
            },
            success: function(data) {
                $('#tampil_data').html(data);
                document.getElementById("judul").innerHTML = 'Tambah Kegiatan';
            }
        });
        $('#modal').modal('show');
    });

    $('#cetak_kegiatan').on('click', function() {
        var id_mahasiswa = $(this).attr("id_mahasiswa");
        $.ajax({
            url: 'apps/data_kegiatan/cetak.php',
            method: 'POST',
            data: {
                id_mahasiswa: id_mahasiswa
            },
            success: function(data) {
                $('#tampil_data').html(data);
                document.getElementById("judul").innerHTML = 'Cetak Kegiatan';
            }
        });
        $('#modal').modal('show');
    });
</script>
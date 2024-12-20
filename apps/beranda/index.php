<div class="row">
    <ol class="breadcrumb">
        <li><a href="index.php?page=beranda">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Beranda</li>
    </ol>
</div>
<!--/.row-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Beranda</div>
            <div class="panel-body">

            <!--Menampilkan Nama Pengguna Sesuai Level -->
            <?php if ($_SESSION['level']=='Admin' or $_SESSION['level']=='Admin'):?>
                <h3>Selamat Datang,  <?php echo  $_SESSION["nama_admin"]; ?>.</h3>
            <?php endif; ?>
            <?php if ($_SESSION['level']=='Mahasiswa' or $_SESSION['level']=='mahasiswa'):?>
                <h3>Selamat Datang, <?php echo  $_SESSION["nama_mahasiswa"]; ?>.</h3>
            <?php endif; ?>
            <!-- Menampilkan Nama Pengguna Sesuai Level -->

            <!-- Mengambil data table tbl_site -->
            <?php 
                //Mengambil profil aplikasi
                //Mengubungkan database
                include 'config/database.php';
                $query = mysqli_query($kon, "select * from tbl_site limit 1");    
                $row = mysqli_fetch_array($query);

    if ($_SESSION['level'] == 'Admin') {
        $sql = "SELECT nama, 
                       COUNT(CASE WHEN status = 'Hadir' THEN 1 END) AS jumlah_hadir,
                       COUNT(CASE WHEN status = 'Izin' THEN 1 END) AS jumlah_izin,
                       COUNT(CASE WHEN status = 'Sakit' THEN 1 END) AS jumlah_sakit
                FROM tbl_absensi
                LEFT JOIN tbl_mahasiswa ON tbl_absensi.id_mahasiswa = tbl_mahasiswa.id_mahasiswa
                GROUP BY tbl_mahasiswa.id_mahasiswa";
    } else {
                      // Mengambil rekap absensi berdasarkan id_mahasiswa
    $id_mahasiswa = $_SESSION['id_mahasiswa'];
        // Query untuk Mahasiswa
        $sql = "SELECT 
                       COUNT(CASE WHEN status = 'Hadir' THEN 1 END) AS jumlah_hadir,
                       COUNT(CASE WHEN status = 'Izin' THEN 1 END) AS jumlah_izin,
                       COUNT(CASE WHEN status = 'Sakit' THEN 1 END) AS jumlah_sakit
                FROM tbl_absensi
                WHERE id_mahasiswa = '$id_mahasiswa'";
    }
    

    $rekap_result = mysqli_query($kon, $sql);
            ?>
            <!-- Menhambil data table tbl_site -->

            <!-- Info Aplikasi -->
            <p>Selamat Datang di Aplikasi Absensi dan Kegiatan Harian Mahasiswa berbasis Web. Sebuah sistem yang memungkinkan para Mahasiswa PKL di <?php echo $row['nama_instansi'];?> untuk melalukan absensi dan mencatat kegiatan harian dari website. Sistem ini diharapkan dapat memberi kemudahan setiap Mahasiswa PKL untuk melakukan absensi dan mencatat kegiatan harian.</p>
            <!-- Info Aplikasi -->
            <div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">Rekap Absensi</div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <?php if ($_SESSION['level'] == 'Admin') : ?>
                <th>Nama</th>
            <?php endif; ?>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row_absen = mysqli_fetch_assoc($rekap_result)) : ?>
        <tr>
            <?php if ($_SESSION['level'] == 'Admin') : ?>
                <td><?= $row_absen['nama']; ?></td>
            <?php endif; ?>
            <td><?= $row_absen['jumlah_hadir']; ?></td>
            <td><?= $row_absen['jumlah_izin']; ?></td>
            <td><?= $row_absen['jumlah_sakit']; ?></td>
        </tr>
    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if ($_SESSION['level'] == 'Admin') : ?>
    <a href="apps/cetak/cetak_absensi_semua.php" class="btn btn-primary" target="_blank">
    <em class="fa fa-print"></em> Cetak Absensi
</a>
            <?php endif; ?>            
            </div>
        </div>
    </div>
</div>
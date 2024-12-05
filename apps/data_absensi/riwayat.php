<style>
    #map {
        height: 400px;
        /* Adjust the height of the map */
        border: 2px solid #ccc;
        /* Optional: Add a border around the map */
        border-radius: 8px;
        /* Optional: Rounded corners */
    }
</style>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="index.php?page=beranda">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Riwayat Absensi</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Riwayat Absensi
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em
                        class="fa fa-toggle-up"></em></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="#" method="GET">
                        <input type="hidden" name="page" value="riwayat" />
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
                                </br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="form-group">
                    <button id_mahasiswa='<?php echo $_SESSION['id_mahasiswa']; ?>' type="button"
                        class="cetak btn btn-primary" id="cetak"><i class="fa fa-print"></i> Cetak</button>
                </div>
                <table class="table table-bordered table-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Hari</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Kehadiran</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">File Izin</th>
                            <th class="text-center">Lokasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // include database
                        include 'config/database.php';
                        include 'config/function.php';
                        $id_mahasiswa = $_SESSION["id_mahasiswa"];
                        if (isset($_GET['tanggal_awal']) and $_GET['tanggal_akhir']) {
                            $tanggal_awal = $_GET["tanggal_awal"];
                            $tanggal_akhir = $_GET["tanggal_akhir"];
                            $sql = "SELECT tbl_absensi.id_absensi, tbl_absensi.id_mahasiswa, tbl_alasan.id_alasan, 
                                DAYNAME(tbl_absensi.tanggal) AS hari,
                                tbl_absensi.waktu,
                                tbl_absensi.tanggal,
                                tbl_absensi.foto,
                                tbl_absensi.longitude,
                                tbl_absensi.latitude,
                                IFNULL(tbl_alasan.alasan, ' - ') AS alasan,
                                  (CASE
                                    WHEN tbl_absensi.status = 'Hadir' THEN 'Hadir'
                                    WHEN tbl_absensi.status = 'Izin' THEN 'Izin'
                                    WHEN tbl_absensi.status = 'Sakit' THEN 'Sakit'
                                    WHEN tbl_absensi.status = 'Tidak hadir' THEN 'Tidak Hadir'
                                    ELSE 'Belum Absensi'
                                END) AS status
                                FROM tbl_absensi
                                LEFT JOIN tbl_alasan ON tbl_absensi.tanggal = tbl_alasan.tanggal AND tbl_absensi.id_mahasiswa = tbl_alasan.id_mahasiswa
                                WHERE tbl_absensi.id_mahasiswa = '$id_mahasiswa' AND
                                tbl_absensi.tanggal >= '$tanggal_awal' AND
                                tbl_absensi.tanggal <= '$tanggal_akhir'
                                ORDER BY tbl_absensi.tanggal DESC;";
                        } else {
                            $sql = "SELECT tbl_absensi.id_absensi, tbl_absensi.id_mahasiswa, tbl_alasan.id_alasan, 
                                DAYNAME(tbl_absensi.tanggal) AS hari,
                                tbl_absensi.waktu,
                                tbl_absensi.tanggal,
                                tbl_absensi.foto,
                                tbl_absensi.longitude,
                                tbl_absensi.latitude,
                                IFNULL(tbl_alasan.alasan, ' - ') AS alasan,
                                  (CASE
                                    WHEN tbl_absensi.status = 'Hadir' THEN 'Hadir'
                                    WHEN tbl_absensi.status = 'Izin' THEN 'Izin'
                                    WHEN tbl_absensi.status = 'Sakit' THEN 'Sakit'
                                    WHEN tbl_absensi.status = 'Tidak hadir' THEN 'Tidak Hadir'
                                    ELSE 'Belum Absensi'
                                END) AS status
                                FROM tbl_absensi
                                LEFT JOIN tbl_alasan ON tbl_absensi.tanggal = tbl_alasan.tanggal AND tbl_absensi.id_mahasiswa = tbl_alasan.id_mahasiswa
                                WHERE tbl_absensi.id_mahasiswa = '$id_mahasiswa'
                                ORDER BY tbl_absensi.tanggal DESC;";
                        }
                        $hasil = mysqli_query($kon, $sql);
                        $no = 0;
                        //Menampilkan data dengan perulangan while
                        while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no; ?></td>
                                <td class="text-center">
                                    <?php
                                    $hari = $data['hari'];
                                    echo MendapatkanHari($hari);
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $tgl = date("d", strtotime($data['tanggal']));
                                    $bulan = date("m", strtotime($data['tanggal']));
                                    $tahun = date("Y", strtotime($data['tanggal']));
                                    echo $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun
                                    ?>
                                </td>
                                <td class="text-center"><?php echo $data['waktu']; ?></td>
                                <td class="text-center"><?php echo $data['status']; ?></td>
                                <td class="text-center"><?php echo $data['alasan']; ?></td>
                                <td class="text-center">
                                    <img src="uploads/<?php echo $data['foto']; ?>" alt="Foto Absen"
                                        style="width:50px; height:50px;">
                                </td>
                                <td></td>
                                <td class="text-center">
                                    <button class="show-map btn btn-info" data-lat="<?php echo $data['latitude']; ?>"
                                        data-lng="<?php echo $data['longitude']; ?>">Lihat Peta</button>
                                </td>

                            </tr>
                            <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="form-group">
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.row-->

<!-- Modal untuk peta -->
<div class="modal fade" id="mapModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lokasi Absensi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px;"></div> <!-- Ukuran peta -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="judul"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div id="tampil_data">
                    <!-- Data akan di load menggunakan AJAX -->
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>

        </div>
    </div>
</div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>

<script>
    $(document).ready(function() {
        // Variabel global untuk instance peta
        let map = null;
        // Setting absensi
        $('.cetak').on('click', function() {
            var id_mahasiswa = $(this).attr("id_mahasiswa");
            $.ajax({
                url: 'apps/data_absensi/cetak.php',
                method: 'POST',
                data: {
                    id_mahasiswa: id_mahasiswa
                },
                success: function(data) {
                    $('#tampil_data').html(data);
                    document.getElementById("judul").innerHTML = 'Cetak Absensi';
                    $('#modal').modal('show');
                }
            });
        });

        // Event untuk menampilkan lokasi dalam modal
        $('.show-map').on('click', function() {
            var latitude = $(this).data('lat');
            var longitude = $(this).data('lng');

            // Hapus instance peta sebelumnya jika ada
            if (map !== null) {
                map.remove();
                map = null;
            }

            // Bersihkan kontainer peta untuk menghindari duplikasi elemen
            $('#map').empty();

            // Inisialisasi peta baru
            map = L.map('map').setView([latitude, longitude], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Tambahkan marker pada peta
            L.marker([latitude, longitude]).addTo(map)
                .openPopup();

            // Tampilkan modal
            $('#mapModal').modal('show');
        });

        // Mengatur ulang peta saat modal ditutup
        $('#mapModal').on('hidden.bs.modal', function() {
            if (map !== null) {
                map.remove(); // Hapus instance peta
                map = null; // Reset variabel peta
            }
        });

        // Menyesuaikan ukuran peta setelah modal ditampilkan
        $('#mapModal').on('shown.bs.modal', function() {
            if (map !== null) {
                map.invalidateSize(); // Memperbarui ukuran peta
            }
        });
    });
</script>
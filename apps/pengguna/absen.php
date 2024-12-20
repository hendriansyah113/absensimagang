<?php
if ($_SESSION["level"] != 'Mahasiswa' and $_SESSION["level"] != 'mahasiswa') {
    echo "<br><div class='alert alert-danger'>Tidak Memiliki Hak Akses</div>";
    exit;
}
?>

<?php
// Mengambil data dari sessi login
include 'config/database.php';
$id_mahasiswa = $_SESSION["id_mahasiswa"];
$sql = "select * from tbl_mahasiswa where id_mahasiswa=$id_mahasiswa limit 1";
$hasil = mysqli_query($kon, $sql);
$data = mysqli_fetch_array($hasil);
$nama = $data['nama'];
$universitas = $data['universitas'];
$nim = $data['nim'];
$mulai_magang = $data['mulai_magang'];
$akhir_magang = $data['akhir_magang'];
$foto = $data['foto'];

// Mengubah format tanggal ke bahasa Indonesia
setlocale(LC_TIME, 'id_ID');
$tanggal_sekarang = new DateTime();
$tanggal_masuk = strftime("%d %B %Y", strtotime($mulai_magang));
$tanggal_keluar = strftime("%d %B %Y", strtotime($akhir_magang));

$querykoordinat = "SELECT * FROM tbl_koordinat WHERE id_koordinat = 1"; // Ganti sesuai kebutuhan Anda
$result = mysqli_query($kon, $querykoordinat);
$row = mysqli_fetch_assoc($result);

$allowedLat = $row['latitude'];
$allowedLng = $row['longitude'];
$allowedRadius = $row['radius'];
?>

<?php
// Mengambil data dari sessi login
include 'config/database.php';
$sql = "select * from tbl_setting_absensi limit 1";
$query = mysqli_query($kon, $sql);
$setting = mysqli_fetch_array($query);
$mulai_absen = $setting['mulai_absen'];
$akhir_absen = $setting['akhir_absen'];
?>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="index.php?page=absen">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Beranda</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Absensi</div>
            <div class="panel-body">
                <div id="div_periode" class='alert alert-warning'><strong>Periode Absensi Selesai</strong></div>

                <?php
                // Validasi untuk menampilkan pesan pemberitahuan saat user update pengaturan aplikasi                
                if (isset($_GET['mulai'])) {
                    if ($_GET['mulai'] == 'berhasil') {
                        echo "<div class='alert alert-success'><strong>Berhasil!</strong> Absensi</div>";
                    } else if ($_GET['mulai'] == 'gagal') {
                        echo "<div class='alert alert-warning'><strong>Maaf!</strong> Rentang Waktu Absensi Anda Belum Atau Lewat</div>";
                    }
                }
                ?>

                <div class="row">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Nama Mahasiswa</td>
                                <td width="80%">: <?php echo $nama; ?></td>
                            </tr>
                            <tr>
                                <td>Nomor Induk Mahasiswa</td>
                                <td width="80%">: <?php echo $nim; ?></td>
                            </tr>
                            <tr>
                                <td>Instansi</td>
                                <td width="80%">: <?php echo $universitas; ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td width="80%">:
                                    <?php
                                    include 'config/function.php';
                                    $tanggal_sekarang = date("d-m-Y");
                                    $tgl = date("d", strtotime($tanggal_sekarang));
                                    $bulan = date("m", strtotime($tanggal_sekarang));
                                    $tahun = date("Y", strtotime($tanggal_sekarang));
                                    echo $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td width="80%">:
                                    <?php
                                    include 'config/database.php';
                                    $tanggal_sekarang = date("Y-m-d");
                                    $hari_sekarang = date("l", strtotime($tanggal_sekarang));
                                    if ($hari_sekarang == "Saturday" || $hari_sekarang == "Sunday") {
                                        echo "Hari Libur";
                                    } else {
                                        $kueri = "SELECT waktu FROM tbl_absensi WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = '$tanggal_sekarang'";
                                        $result = mysqli_query($kon, $kueri);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            echo $data['waktu'];
                                        } else {
                                            echo "Belum Absensi";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td width="80">:
                                    <?php
                                    include 'config/database.php';
                                    $tanggal_sekarang = date("Y-m-d");
                                    $hari_sekarang = date("l", strtotime($tanggal_sekarang));
                                    $is_absen = false; // Mahasiswa sudah absen
                                    $kueri_pulang = "SELECT status, waktu_pulang FROM tbl_absensi WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = '$tanggal_sekarang'";
$result_pulang = mysqli_query($kon, $kueri_pulang);
if (mysqli_num_rows($result_pulang) > 0) {
    $data_pulang = mysqli_fetch_assoc($result_pulang);
    if ($data_pulang['waktu_pulang'] != null) {
        // The user has already marked "Pulang"
        $is_absen_pulang = true;
    } else {
        $is_absen_pulang = false;
    }
} else {
    $is_absen_pulang = false;
}

                                    if ($hari_sekarang == "Saturday" || $hari_sekarang == "Sunday") {
                                        echo "Hari Libur";
                                    } else {
                                        $kueri = "SELECT status FROM tbl_absensi WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = '$tanggal_sekarang'";
                                        $result = mysqli_query($kon, $kueri);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_array($result);
                                            $is_absen = true; // Mahasiswa sudah absen
                                            if ($data['status'] == "Hadir") {
                                                echo "Hadir";
                                            } elseif ($data['status'] == "Izin") {
                                                echo "Izin";
                                            } elseif ($data['status'] == "Sakit") {
                                                echo "Sakit";
                                            }
                                        } else {
                                            echo "Belum Absensi";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
    <td>Status Pulang</td>
    <td width="80">:
        <?php
        $kueri_pulang_status = "SELECT waktu_pulang FROM tbl_absensi WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = '$tanggal_sekarang'";
        $result_pulang_status = mysqli_query($kon, $kueri_pulang_status);
        if (mysqli_num_rows($result_pulang_status) > 0) {
            $data_pulang_status = mysqli_fetch_assoc($result_pulang_status);
            if ($data_pulang_status['waktu_pulang'] != null) {
                echo "Sudah Pulang pada: " . date("H:i:s", strtotime($data_pulang_status['waktu_pulang']));
            } else {
                echo "Belum Absen Pulang";
            }
        } else {
            echo "Belum Absen Pulang";
        }
        ?>
    </td>
</tr>

                            <tr>
                                <td>
                                    <button id_mahasiswa="<?php echo $id_mahasiswa; ?>" id="tombol_absensi"
                                        class="tombol_periode mulai_absensi btn btn-success btn-circle" <?php if ($is_absen) {
                                                                                                            echo 'disabled';
                                                                                                        } ?>>
                                        <i class="fa fa-clock-o"></i> Absensi
                                    </button>
                                </td>
                            </tr>
                            <tr>
                            <tr>
    <td>
        <button id_mahasiswa="<?php echo $id_mahasiswa; ?>" class="tombol_periode pulang_absensi btn btn-danger btn-circle" <?php if (!$is_absen) { echo 'disabled'; } ?>>
            <i class="fa fa-clock-o"></i> Absen Pulang
        </button>
    </td>
</tr>



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'config/database.php';
    $query = "SELECT mulai_absen, akhir_absen FROM tbl_setting_absensi";
    $result = mysqli_query($kon, $query);
    $data = mysqli_fetch_assoc($result);
    $mulai_absen = date("H:i:s", strtotime($data['mulai_absen']));
    $akhir_absen = date("H:i:s", strtotime($data['akhir_absen']));
    ?>

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
    <!-- Model AJAX -->

    <script>
        // Setting pengguna
        $('.mulai_absensi').on('click', function() {
            var id_mahasiswa = $(this).attr("id_mahasiswa");
            $.ajax({
                url: 'apps/pengguna/mulai_absensi.php',
                method: 'post',
                data: {
                    id_mahasiswa: id_mahasiswa
                },
                success: function(data) {
                    $('#tampil_data').html(data);
                    document.getElementById("judul").innerHTML = 'Mulai Absensi';
                }
            });
            // Membuka modal
            $('#modal').modal('show');
        });

   // Menangani tombol absen pulang
$('.pulang_absensi').on('click', function() {
    var id_mahasiswa = $(this).attr("id_mahasiswa");

    // Memanggil AJAX untuk absen pulang
    $.ajax({
        url: 'apps/pengguna/absen_pulang.php',
        method: 'post',
        data: {
            id_mahasiswa: id_mahasiswa
        },
        success: function(response) {
            // Menampilkan hasil status
            alert(response); // Tampilkan hasil dari server
            location.reload(); // Reload halaman untuk memperbarui status absen
        },
        error: function() {
            alert('Terjadi kesalahan saat memproses absen pulang.');
        }
    });
});


    </script>

    <script>
        $(document).ready(function() {
            var tanggal_sekarang = new Date();
            var tanggal_keluar = new Date("<?php echo $tanggal_keluar; ?>");
            if (tanggal_sekarang > tanggal_keluar) {
                // Sembunyikan button absensi
                $(".tombol_periode").hide();
                $("#div_periode").show();
            } else {
                $("#div_periode").hide();
            }
        });
    </script>

    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let map;
            let videoStream;

            // Koordinat yang diambil dari PHP
            var allowedLattbl = <?php echo $allowedLat; ?>;
            var allowedLngtbl = <?php echo $allowedLng; ?>;
            var allowedRadiustbl = <?php echo $allowedRadius; ?>;

            // Fungsi untuk menghitung jarak antara dua koordinat (Haversine formula)
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radius bumi dalam kilometer
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distance = R * c; // Jarak dalam kilometer
                return distance * 1000; // Konversi ke meter
            }

            // Fungsi untuk konversi derajat ke radian
            function toRadians(degrees) {
                return degrees * (Math.PI / 180);
            }

            $('#openModalButton').on('click', function() {
                $('#modal').modal('show');
            });

            $('#modal').on('shown.bs.modal', function() {
                // Mengaktifkan kamera
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({
                        video: true
                    }).then(function(stream) {
                        var video = document.getElementById('video');
                        video.srcObject = stream;
                        video.play();
                        videoStream = stream;
                    }).catch(function(error) {
                        alert('Kamera tidak tersedia: ' + error.message);
                    });
                } else {
                    alert('Browser tidak mendukung akses kamera.');
                }

                // Menampilkan Map dan Lokasi
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        // Titik referensi (lokasi yang diizinkan) menggunakan koordinat yang Anda berikan
                        var allowedLat = allowedLattbl;
                        var allowedLng = allowedLngtbl;

                        // Radius yang diizinkan (misalnya 500 meter)
                        var allowedRadius = allowedRadiustbl;

                        // Menghitung jarak antara lokasi pengguna dan titik referensi
                        var distance = calculateDistance(userLat, userLng, allowedLat, allowedLng);

                        // Inisialisasi peta
                        if (!map) {
                            map = L.map('map').setView([userLat, userLng], 13);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);

                            // Marker untuk lokasi user
                            L.marker([userLat, userLng]).addTo(map)
                                .openPopup();

                            // Tambahkan radius di sekitar titik referensi
                            L.circle([allowedLat, allowedLng], {
                                color: 'blue',
                                fillColor: '#3f51b5',
                                fillOpacity: 0.5,
                                radius: allowedRadius
                            }).addTo(map);
                        } else {
                            map.setView([userLat, userLng], 13);
                        }

                        // Pastikan map ditampilkan dengan benar
                        setTimeout(function() {
                            map.invalidateSize();
                        }, 200);

                    }, function(error) {
                        alert("Gagal mendapatkan lokasi: " + error.message);
                    });
                } else {
                    alert("Browser tidak mendukung Geolocation.");
                }
            });

            $('#modal').on('hidden.bs.modal', function() {
                // Matikan kamera
                if (videoStream) {
                    let tracks = videoStream.getTracks();
                    tracks.forEach(track => track.stop());
                }
                // Refresh halaman
                location.reload(); // Me-refresh halaman setelah modal ditutup
            });

            $('#absensiForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah form dikirim langsung

                // Ambil foto sebelum mengirim form
                let video = document.getElementById('video');
                let canvas = document.getElementById('photoCanvas');
                let context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                let photoDataUrl = canvas.toDataURL('image/png');
                document.getElementById('foto').value = photoDataUrl;

                // Ambil nilai status absen
                var status = $('#status').val();
                var userLatitude = parseFloat($('#latitude').val());
                var userLongitude = parseFloat($('#longitude').val());

                // Koordinat pusat untuk validasi radius (misal lokasi kantor)
                var allowedLatitude = allowedLattbl;
                var allowedLongitude = allowedLngtbl;
                var allowedRadius = allowedRadiustbl; // Radius dalam meter

                // Cek jika statusnya "Hadir" (1)
                if (status === "1") {
                    // Hitung jarak antara lokasi user dan lokasi yang diizinkan
                    var distance = calculateDistance(userLatitude, userLongitude, allowedLatitude,
                        allowedLongitude);

                    if (distance > allowedRadius) {
                        // Tampilkan SweetAlert jika user di luar radius
                        Swal.fire({
                            icon: 'error',
                            title: 'Lokasi Tidak Valid',
                            text: 'Anda berada di luar radius yang diizinkan untuk absen!',
                        });
                    } else {
                        // Submit form jika dalam radius yang diperbolehkan
                        this.submit(); // Submit form secara manual
                    }
                } else {
                    // Jika statusnya bukan "Hadir" (Izin atau Tidak Hadir), langsung submit form tanpa validasi
                    this.submit(); // Submit form
                }
            });
        });
    </script>
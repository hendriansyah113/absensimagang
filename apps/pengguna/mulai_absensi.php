<?php
session_start();
if (isset($_POST['submit'])) {
    include '../../config/database.php';

    function input($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Ambil data dari form absensi
    $id_mahasiswa = $_SESSION["id_mahasiswa"];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $status = $_POST["status"];
    $alasan = isset($_POST["alasan"]) ? input($_POST["alasan"]) : null;

    date_default_timezone_set("Asia/Jakarta");
    $tanggal = date("Y-m-d");
    $waktu = date("H:i:s");

    // Proses menyimpan foto
    $foto = $_POST['foto'];
    error_log("Foto: " . $foto); // Log untuk memeriksa foto

    if (preg_match('/^data:image\/(png|jpeg);base64,/', $foto)) {
        // Hapus header
        $foto = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $foto);
        $foto = str_replace(' ', '+', $foto); // Pastikan spasi diganti dengan '+'
        $data = base64_decode($foto);

        // Periksa apakah dekode berhasil
        if ($data === false) {
            echo "Gagal mendekode gambar.";
            exit; // Keluar jika dekode gagal
        }

        $file_name = 'foto_' . uniqid() . '.png';
        $file_path = '../../uploads/' . $file_name;

        // Simpan foto ke server
        if (file_put_contents($file_path, $data) === false) {
            echo "Gagal menyimpan gambar.";
            exit; // Keluar jika gagal menyimpan
        }
    } else {
        echo "Format gambar tidak valid.";
        exit; // Keluar jika format tidak valid
    }

    // Cek waktu absen
    $cek_waktu = "SELECT CONCAT(CURDATE(), ' ', mulai_absen) as mulai_absen, CONCAT(CURDATE(), ' ', akhir_absen) as akhir_absen, NOW() as waktu_sekarang FROM tbl_setting_absensi LIMIT 1;";
    $query = mysqli_query($kon, $cek_waktu);
    $setting = mysqli_fetch_array($query);
    $mulai_absen = $setting["mulai_absen"];
    $akhir_absen = $setting["akhir_absen"];
    $waktu_sekarang = $setting["waktu_sekarang"];

    if ($waktu_sekarang >= $mulai_absen && $waktu_sekarang <= $akhir_absen) {
        // Menambahkan data ke tabel absensi
        $sql = "INSERT INTO tbl_absensi (id_mahasiswa, status, waktu, tanggal, foto, latitude, longitude) VALUES 
                ('$id_mahasiswa', '$status', '$waktu', '$tanggal', '$file_name', '$latitude', '$longitude')";
        $simpan_absensi = mysqli_query($kon, $sql);

        // Jika status izin, simpan alasan
        if ($status == "2" && $alasan) {
            $sql = "INSERT INTO tbl_alasan (id_mahasiswa, alasan, tanggal) VALUES 
                    ('$id_mahasiswa', '$alasan', '$tanggal')";
            $simpan_izin = mysqli_query($kon, $sql);
        }

        // Validasi data
        if ($simpan_absensi) {
            mysqli_query($kon, "COMMIT");
            header("Location:../../index.php?page=absen&mulai=berhasil");
        } else {
            echo "<script>alert('Gagal menyimpan data.');</script>";
        }
    } else {
        echo "<script> alert('Waktu absen tidak valid.');
    window.location.href = 'http://localhost/absensimagang/index.php?page=absen';</script>";
    }
}
?>

<?php
$id_mahasiswa = $_SESSION["id_mahasiswa"];
$nama_mahasiswa = $_SESSION["nama_mahasiswa"];
$tanggal = date("Y-m-d");
include '../../config/database.php';
$query = mysqli_query(
    $kon,
    "SELECT mulai_magang, akhir_magang FROM tbl_mahasiswa WHERE id_mahasiswa=$id_mahasiswa;"
);
$periode = mysqli_fetch_array($query);
$tanggal_masuk = $periode["mulai_magang"];
$tanggal_keluar = $periode["akhir_magang"];
?>

<?php
$tanggal_sekarang = date("Y-m-d");
$query = "SELECT COUNT(*) FROM tbl_absensi WHERE tanggal = '$tanggal_sekarang' AND id_mahasiswa = '$id_mahasiswa'";
$result = mysqli_query($kon, $query);
$data = mysqli_fetch_assoc($result);
$absensi_sudah = $data['COUNT(*)'] > 0 ? "disabled" : "";
?>

<form action="apps/pengguna/mulai_absensi.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <div class="form-group">
                <label>Status :</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1">Hadir</option>
                    <option value="2">Izin</option>
                    <option value="3">Tidak Hadir</option>
                </select>

            </div>

        </div>

        <div class="col-sm-6" id="text_alasan" style="display:none;">
            <div class="form-group">
                <label>Alasan :</label>
                <input type="text" name="alasan" id="alasan" class="form-control"
                    placeholder="Masukkan Alasan Kenapa Izin?">
            </div>
        </div>
    </div>

    <!-- Bagian Kamera dan Hasil Foto -->
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Ambil Foto:</label>
                <div id="my_camera" style="width:320px; height:240px;">
                    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                    <video id="video" width="320" height="240" autoplay></video>
                </div>
                <input type="hidden" name="foto" class="image-tag">
                <button type="button" id="ambil_foto" class="btn btn-success mb-2">Ambil Foto</button>
                <button type="button" id="ulang_foto" class="btn btn-warning mb-2" style="display:none;">Ulang
                    Foto</button>
                <button type="submit" id="submit" name="submit" class="simpan_absensi btn btn-primary"
                    style="display:none;" <?php echo $absensi_sudah; ?>>
                    <i class="fa fa-clock-o"></i> Absensi
                </button>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Hasil Foto:</label>
                <div id="results" style="border: 1px solid #ddd; padding: 10px;">
                    <p>Foto akan tampil di sini setelah diambil.</p>
                    <img id="foto_hasil" style="max-width: 60%; height: auto; display: none;" />
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Lokasi Anda:</label>
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    $(document).ready(function() {
        // Nonaktifkan select status saat page pertama kali diload
        $('#status').prop('disabled', true);
        $("#submit").prop('disabled', true);

        // Event saat status diubah
        $("#status").change(function() {
            var status = $(this).val();

            // Jika status kosong (belum dipilih), disable tombol submit
            if (!status) {
                $("#submit").prop('disabled', true);
                $("#text_alasan").hide();
                $("#alasan").attr("required", false);
            } else if (status == "2") { // Jika memilih Izin (status 2)
                // Tampilkan input alasan dan wajib diisi
                $("#text_alasan").show();
                $("#alasan").attr("required", true);
                // Aktifkan tombol absen tanpa cek radius
                $("#submit").prop('disabled', false);
            } else {
                // Sembunyikan input alasan dan tidak wajib diisi
                $("#text_alasan").hide();
                $("#alasan").attr("required", false);

                if (status == "1") { // Jika statusnya Hadir (status 1)
                    // Lakukan pengecekan lokasi/radius
                    checkRadius();
                } else {
                    // Aktifkan tombol jika status selain Hadir
                    $("#submit").prop('disabled', false);
                }
            }
        });

        // Fungsi untuk mengecek radius
        function checkRadius() {
            var userLatitude = parseFloat($('#latitude').val());
            var userLongitude = parseFloat($('#longitude').val());

            // Koordinat lokasi yang diizinkan (misalnya kantor)
            var allowedLatitude = -2.204598045273787;
            var allowedLongitude = 113.91863623695271;
            var allowedRadius = 500; // Radius dalam meter

            // Hitung jarak antara lokasi user dan lokasi yang diizinkan
            var distance = calculateDistance(userLatitude, userLongitude, allowedLatitude, allowedLongitude);

            if (distance > allowedRadius) {
                // Disable tombol jika di luar radius
                Swal.fire({
                    icon: 'error',
                    title: 'Lokasi Tidak Valid',
                    text: 'Anda berada di luar radius yang diizinkan untuk absen!',
                });
                $("#submit").prop('disabled', true);
            } else {
                // Aktifkan tombol jika dalam radius yang diperbolehkan
                $("#submit").prop('disabled', false);
            }
        }

        // Fungsi untuk menghitung jarak antara dua koordinat dalam meter (haversine formula)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371000; // Radius Bumi dalam meter
            var dLat = toRadians(lat2 - lat1);
            var dLon = toRadians(lon2 - lon1);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c;
            return distance;
        }

        // Fungsi untuk konversi derajat ke radian
        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);

                // Aktifkan select status setelah lokasi berhasil diambil
                $('#status').prop('disabled', false);
            }, function(error) {
                console.error("Error getting location: ", error);
                alert("Gagal mengambil lokasi. Pastikan GPS diaktifkan.");
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }

        // Ambil video element dan canvas
        const video = document.querySelector("#video");
        const canvas = document.querySelector("#canvas");
        const context = canvas.getContext('2d');

        // Fungsi untuk mengambil foto
        document.getElementById('ambil_foto').addEventListener('click', function() {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png');
            const fotoHasil = document.getElementById('foto_hasil');
            fotoHasil.src = dataURL;
            fotoHasil.style.display = 'block'; // Tampilkan gambar
            $('input[name="foto"]').val(dataURL);
            $('#ulang_foto').show(); // Tampilkan tombol ulang foto
            $('#submit').show(); // Tampilkan tombol ulang foto
        });


        document.getElementById('ulang_foto').addEventListener('click', function() {
            document.getElementById('results').innerHTML =
                '<p>Foto akan tampil di sini setelah diambil.</p>';
            $('input[name="foto"]').val('');
            $('#ulang_foto').hide(); // Sembunyikan tombol ulang foto
            $('#submit').hide(); // Sembunyikan tombol ulang foto
        });



    });
</script>
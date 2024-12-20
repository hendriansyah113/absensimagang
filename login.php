<?php
//memulai session
session_start();
//Jika terdetesi ada variabel id_pengguna dalam session maka langsung arahkan ke halaman dashboard
if (isset($_SESSION["id_pengguna"])) {
    session_unset();
    session_destroy();
}
//Variable pesan untuk menampilkan validasi login
$pesan = "";
//Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan CAPTCHA sudah diisi
    if (empty($_POST['g-recaptcha-response'])) {
        $pesan = "<div class='alert alert-danger'><strong>Error!</strong> CAPTCHA harus diisi.</div>";
    } else {
        // Verifikasi CAPTCHA menggunakan Secret Key dari Google
        $secret_key = '6LfG8IcqAAAAAGfbnbww8AcuqGrJ8hUu9RLAJT2H'; // Ganti dengan Secret Key Anda
        $captcha_response = $_POST['g-recaptcha-response'];
        $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $captcha_response;

        // Lakukan request untuk memverifikasi CAPTCHA
        $response = file_get_contents($verify_url);
        $response_data = json_decode($response);

        if (!$response_data->success) {
            $pesan = "<div class='alert alert-danger'><strong>Error!</strong> Verifikasi CAPTCHA gagal.</div>";
        } else {
            // CAPTCHA sukses, lanjutkan proses login
            // Menghubungkan database
            include "config/database.php";
            // Mengambil input username dan password dari form login
            $username = input($_POST["username"]);
            $password = input(md5($_POST["password"])); //hash yang dipakai md5
            // Query untuk cek tbl_user yang dijoinkan dengan table tbl_admin
            $tabel_admin = "SELECT * FROM tbl_user p
            INNER JOIN tbl_admin k ON k.kode_admin=p.kode_pengguna
            WHERE username='" . $username . "' and password='" . $password . "' LIMIT 1";
            $cek_tabel_admin = mysqli_query($kon, $tabel_admin);
            $admin = mysqli_num_rows($cek_tabel_admin);
            // Query untuk cek pada tbl_user yang dijoinkan dengan table tbl_mahasiswa
            $tabel_mahasiswa = "SELECT * FROM tbl_user p
            INNER JOIN tbl_mahasiswa m ON m.kode_mahasiswa=p.kode_pengguna
            WHERE username='" . $username . "' and password='" . $password . "' LIMIT 1";
            $cek_tabel_mahasiswa = mysqli_query($kon, $tabel_mahasiswa);
            $mahasiswa = mysqli_num_rows($cek_tabel_mahasiswa);

            // Kondisi jika pengguna merupakan admin
            if ($admin > 0) {
                $row = mysqli_fetch_assoc($cek_tabel_admin);
                $_SESSION["id_pengguna"] = $row["id_user"];
                $_SESSION["kode_pengguna"] = $row["kode_pengguna"];
                $_SESSION["nama_admin"] = $row["nama"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["level"] = $row["level"];
                $_SESSION["nip"] = $row["nip"];
                //mengalihkan halaman ke page beranda
                header("Location:index.php?page=beranda");
            } else if ($mahasiswa > 0) {
                $row = mysqli_fetch_assoc($cek_tabel_mahasiswa);
                $_SESSION["id_pengguna"] = $row["id_user"];
                $_SESSION["kode_pengguna"] = $row["kode_pengguna"];
                $_SESSION["id_mahasiswa"] = $row["id_mahasiswa"];
                $_SESSION["nama_mahasiswa"] = $row["nama"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["universitas"] = $row["universitas"];
                $_SESSION["level"] = $row["level"];
                $_SESSION["foto"] = $row["foto"];
                $_SESSION["nim"] = $row["nim"];
                //mengalihkan halaman ke page beranda
                header("Location:index.php?page=beranda");
            } else {
                $pesan = "<div class='alert alert-danger'><strong>Error!</strong> Username dan Password Salah.</div>";
            }
        }
    }
}
?>

<!-- Mengambil Profil Aplikasi -->
<?php
//Menghubungkan database
include 'config/database.php';
//Melakukan query untuk menampilkan table tbl_site
$query = mysqli_query($kon, "select * from tbl_site limit 1");
//Menyimpan hasil query    
$row = mysqli_fetch_array($query);
//Menyimpan nama instansi dari tbl_site
$nama_instansi = $row['nama_instansi'];
//Menyimpan nama logo dari tbl_site
$logo = $row['logo'];
?>
<!-- Mengambil Profil Aplikasi -->

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="apps/pengaturan/logo/<?php echo $logo; ?>">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="template/login/css/bootstrap.min.css" />
    <!-- Google Font Roboto -->
    <link href="template/login/font/" rel="stylesheet" />
    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Roboto Condensed", sans-serif;
            background-color: #F4F1F1;
        }
    </style>
    <!-- Title Website -->
    <title>Login | <?php echo $nama_instansi; ?></title>
</head>

<body>
    <div class="container rounded shadow-lg px-4 py-5 mt-5 bg-white" style="max-width: 420px">
        <!-- Menambahkan logo di atas teks -->
        <div class="text-center mb-4">
            <img src="apps/pengaturan/logo/<?php echo $logo; ?>" alt="Logobps.png" style="max-width: 100px;">
        </div>
        <h3 class="text-center mb-4">ABSENSI & KEGIATAN PKL</h3>
        <!-- Tambahkan script Google reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <label for="info">Silahkan Login Terlebih Dahulu</label>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" />
            </div>
            <!-- Tambahkan reCAPTCHA di sini -->
            <div class="g-recaptcha" data-sitekey="6LfG8IcqAAAAAFKSOsbCw1RE1ozqHpezA2leq35b"></div>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo $pesan; ?>
            <button type="submit" name="submit" class="btn btn-primary w-75 btn-block mx-auto">Masuk</button>
        </form>

    </div>
</body>

</html>
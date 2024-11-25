<?php

$id_mahasiswa = $_GET["id_mahasiswa"];
$tanggal_awal = $_GET["tanggal_awal"];
$tanggal_akhir = $_GET["tanggal_akhir"];

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $namafile . '"');

require('../../source/plugin/fpdf/fpdf.php');
$pdf = new FPDF('P', 'mm', 'Letter');

include '../../config/database.php';
include '../../config/function.php';
$query = mysqli_query($kon, "select * from tbl_site limit 1");
$row = mysqli_fetch_array($query);
$pembimbing = $row['pembimbing'];

$pdf->AddPage();
$pdf->Image('../../apps/pengaturan/logo/' . $row['logo'], 15, 5, 20, 20);
// Atur ukuran font untuk nama instansi (ukuran lebih kecil)
$pdf->SetFont('Arial', 'B', 15); // Mengubah dari 21 menjadi 16
$pdf->Cell(0, 7, strtoupper($row['nama_instansi']), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, $row['alamat'] . ', Telp ' . $row['no_telp'], 0, 1, 'C');
$pdf->Cell(0, 7, $row['website'], 0, 1, 'C');

//Membuat garis (line)
$pdf->SetLineWidth(1);
$pdf->Line(10, 31, 206, 31);
$pdf->SetLineWidth(0);
$pdf->Line(10, 32, 206, 32);

//Mendampilkan data dari tabel jadwal dan direlasikan dengan beberapa tabel lainnya
$sql = "select * from tbl_mahasiswa where id_mahasiswa=$id_mahasiswa";
$hasil = mysqli_query($kon, $sql);
$data = mysqli_fetch_array($hasil);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 7, 'JURNAL KEGIATAN HARIAN', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, 'Nama ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['nama'], 0, 1);
$pdf->Cell(30, 6, 'Nim ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['nim'], 0, 1);
$pdf->Cell(30, 6, 'Universitas ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['universitas'], 0, 1);
$pdf->Cell(30, 6, 'Jurusan ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['jurusan'], 0, 1);

// Mengurangi jumlah kolom agar tabel tidak terlalu lebar
$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5, 4, 'No', 1, 0, 'C');
$pdf->Cell(25, 4, 'Foto', 1, 0, 'C');
$pdf->Cell(30, 4, 'Nama', 1, 0, 'C');
$pdf->Cell(30, 4, 'Tanggal & Hari', 1, 0, 'C');
$pdf->Cell(20, 4, 'Jam', 1, 0, 'C');
$pdf->Cell(90, 4, 'Kegiatan', 1, 1, 'C');

$pdf->SetFont('Arial', '', 6);

$no = 0;

$sql = "SELECT 
    tbl_kegiatan.id_kegiatan, 
    tbl_kegiatan.id_mahasiswa, 
    tbl_kegiatan.file_upload, 
    tbl_mahasiswa.nama, 
    DATE_FORMAT(tbl_kegiatan.tanggal, '%d-%M-%Y') AS tanggal, 
    DAYNAME(tbl_kegiatan.tanggal) AS hari, 
    tbl_kegiatan.waktu_awal, 
    tbl_kegiatan.waktu_akhir, 
    tbl_kegiatan.kegiatan
FROM 
    tbl_kegiatan 
INNER JOIN 
    tbl_mahasiswa 
ON 
    tbl_kegiatan.id_mahasiswa = tbl_mahasiswa.id_mahasiswa
WHERE 
    tbl_kegiatan.id_mahasiswa = '$id_mahasiswa'
    AND tbl_kegiatan.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
ORDER BY 
    tbl_kegiatan.tanggal ASC;";
$hasil = mysqli_query($kon, $sql);

while ($data = mysqli_fetch_assoc($hasil)) {
    $hari = strftime('%A', strtotime($data['tanggal'])); // Mendapatkan nama hari dalam bahasa Indonesia
    $tgl = date("d", strtotime($data['tanggal']));
    $bulan = date("m", strtotime($data['tanggal']));
    $tahun = date("Y", strtotime($data['tanggal']));
    $waktu_awal = date("h:i", strtotime($data['waktu_awal']));
    $waktu_akhir = date("h:i", strtotime($data['waktu_akhir']));
    $tanggal = $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun . ' (' . MendapatkanHari($hari) . ')';
    $no++;

    // Tentukan tinggi default
    $tinggiDefault = 6;
    $tinggiGambar = 20;

    // Cek apakah ada gambar dan sesuaikan tinggi baris
    $tinggiBaris = (isset($data['file_upload']) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $data['file_upload']) && file_exists('../../uploads/kegiatan/' . $data['file_upload'])) ? $tinggiGambar : $tinggiDefault;

    // Tambahkan nomor
    $pdf->Cell(5, $tinggiBaris, $no, 1, 0, 'C');

    // Menambahkan Foto Kegiatan Jika Ada
    if (isset($data['file_upload']) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $data['file_upload'])) {
        $file_path = '../../uploads/kegiatan/' . $data['file_upload'];

        // Memeriksa apakah file ada sebelum menambahkannya ke PDF
        if (file_exists($file_path)) {
            // Menambahkan gambar, parameter: file_path, x_pos, y_pos, width, height
            $pdf->Image($file_path, $pdf->GetX(), $pdf->GetY(), 20, 20);

            // Mengatur posisi x selanjutnya setelah gambar
            $pdf->SetX($pdf->GetX() + 25);
        } else {
            // Jika file tidak ditemukan, tampilkan teks default dengan tinggi yang sesuai
            $pdf->Cell(25, $tinggiBaris, 'No photo', 1, 0, 'C');
        }
    } else {
        // Jika file tidak valid atau tidak ada, tampilkan teks default dengan tinggi yang sesuai
        $pdf->Cell(25, $tinggiBaris, 'No photo', 1, 0, 'C');
    }

    // Tambahkan data lainnya dengan tinggi yang sama dengan tinggi baris
    $pdf->Cell(30, $tinggiBaris, $data["nama"], 1, 0, 'C');
    $pdf->Cell(30, $tinggiBaris, $tanggal, 1, 0, 'C');
    $pdf->Cell(20, $tinggiBaris, $waktu_awal . ' - ' . $waktu_akhir, 1, 0, 'C');
    $pdf->Cell(90, $tinggiBaris, $data["kegiatan"], 1, 1);
}


$tanggal = date('Y-m-d');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(340, 15, '', 0, 1, 'C');
$pdf->Cell(340, 12, '', 0, 1, 'C');
$pdf->Cell(340, 0, 'Pembimbing Magang', 0, 1, 'C');
$pdf->Cell(340, 50, $pembimbing, 0, 1, 'C');

$kueri = "select nama from tbl_mahasiswa where id_mahasiswa=$id_mahasiswa";
$hasilsql = mysqli_query($kon, $kueri);
$hasilnama = mysqli_fetch_array($hasilsql);
$nama = $hasilnama['nama'];
$namafile = 'Kegiatan Harian-' . $nama . '-' . date('YmdHis') . '.pdf';
$pdf->Output('files/' . $namafile, 'F');
readfile('files/' . $namafile);

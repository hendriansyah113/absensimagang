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
$pdf->SetFont('Arial', 'B', 15); // Mengubah dari 21 menjadi 16
$pdf->Cell(0, 7, strtoupper($row['nama_instansi']), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, $row['alamat'] . ', Telp ' . $row['no_telp'], 0, 1, 'C');
$pdf->Cell(0, 7, $row['website'], 0, 1, 'C');

$pdf->SetLineWidth(1);
$pdf->Line(10, 31, 206, 31);
$pdf->SetLineWidth(0);
$pdf->Line(10, 32, 206, 32);

$sql = "select * from tbl_mahasiswa where id_mahasiswa=$id_mahasiswa";
$hasil = mysqli_query($kon, $sql);
$data = mysqli_fetch_array($hasil);

$awal_magang = $data['mulai_magang'];
$akhir_magang = $data['akhir_magang'];
$mulai_bulan = date("m", strtotime($awal_magang));
$akhir_bulan = date("m", strtotime($akhir_magang));
$mulai_hari = date("d", strtotime($awal_magang));
$akhir_hari = date("d", strtotime($akhir_magang));
$akhir_tahun = date("Y", strtotime($akhir_magang));

// Tambahkan variabel untuk menghitung jumlah hadir, izin, dan sakit
$sql_count = "SELECT 
    SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
    SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS jumlah_izin,
    SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS jumlah_sakit
FROM tbl_absensi 
WHERE id_mahasiswa = $id_mahasiswa AND 
tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
$result_count = mysqli_query($kon, $sql_count);
$count_data = mysqli_fetch_assoc($result_count);

$jumlah_hadir = $count_data['jumlah_hadir'];
$jumlah_izin = $count_data['jumlah_izin'];
$jumlah_sakit = $count_data['jumlah_sakit'];

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 7, 'DAFTAR HADIR MAHASISWA MAGANG', 0, 1, 'C');
$pdf->Cell(0, 7, 'PERIODE ' . $mulai_hari . ' ' . MendapatkanAwalBulan($mulai_bulan) . ' - ' . $akhir_hari . ' ' . MendapatkanAkhirBulan($akhir_bulan) . ' ' . $akhir_tahun, 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, 'Nama ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['nama'], 0, 1);
$pdf->Cell(30, 6, 'Nim ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['nim'], 0, 1);
$pdf->Cell(30, 6, 'Instansi ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['universitas'], 0, 1);
$pdf->Cell(30, 6, 'Jurusan ', 0, 0);
$pdf->Cell(31, 6, ': ' . $data['jurusan'], 0, 1);

$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(30, 6, 'Hari', 1, 0, 'C');
$pdf->Cell(40, 6, 'Tanggal', 1, 0, 'C');
$pdf->Cell(20, 6, 'Waktu', 1, 0, 'C');
$pdf->Cell(25, 6, 'Waktu Pulang', 1, 0, 'C');
$pdf->Cell(48, 6, 'Keterangan', 1, 0, 'C');
$pdf->Cell(30, 6, 'Foto', 1, 1, 'C');
$pdf->SetFont('Arial', '', 10);

$no = 0;

$sql = "SELECT id_absensi, foto, id_mahasiswa, status, tanggal, waktu, waktu_pulang,
    DATE_FORMAT(tanggal, '%W') AS hari 
    FROM tbl_absensi WHERE id_mahasiswa = $id_mahasiswa AND 
    tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY tanggal ASC";
$hasil = mysqli_query($kon, $sql);

while ($data = mysqli_fetch_assoc($hasil)) {
    $waktu = date("h:i", strtotime($data['waktu']));
    $status = $data['status'];
    $hari = $data['hari'];
    $tgl = date("d", strtotime($data['tanggal']));
    $bulan = date("m", strtotime($data['tanggal']));
    $tahun = date("Y", strtotime($data['tanggal']));

    $no++;

    // Tentukan tinggi baris
    $tinggi_baris = (!empty($data['foto']) && file_exists('../../uploads/' . $data['foto'])) ? 20 : 6;

    // Cell kolom biasa
    $pdf->Cell(10, $tinggi_baris, $no, 1, 0, 'C');
    $pdf->Cell(30, $tinggi_baris, MendapatkanHari($hari), 1, 0, 'C');
    $pdf->Cell(40, $tinggi_baris, $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun, 1, 0, 'C');
    $pdf->Cell(20, $tinggi_baris, $waktu, 1, 0, 'C');
    $pdf->Cell(25, $tinggi_baris, $data['waktu_pulang'], 1, 0, 'C');
    $pdf->Cell(48, $tinggi_baris, StatusAbsensi($status), 1, 0, 'C');

    // Kolom untuk foto
    if (!empty($data['foto']) && file_exists('../../uploads/' . $data['foto'])) {
        // Simpan posisi awal X dan Y untuk sel
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Buat sel untuk gambar
        $pdf->Cell(30, $tinggi_baris, '', 1, 0, 'C');

        // Tempatkan gambar di tengah sel
        $pdf->Image('../../uploads/' . $data['foto'], $x + 2.5, $y + 2.5, 15, 15); // Padding 2.5 untuk memposisikan di tengah
    } else {
        // Jika tidak ada foto
        $pdf->Cell(30, $tinggi_baris, 'Foto tidak tersedia', 1, 0, 'C');
    }

    // Pindah ke baris berikutnya
    $pdf->Ln();
}

// Tambahkan bagian akhir tabel untuk menampilkan jumlah
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, 'Hadir :', 0, 0, 'C');
$pdf->Cell(20, 6, $jumlah_hadir, 0, 0, 'C');

$pdf->Cell(5, 6, 'Izin :', 0, 0, 'C');
$pdf->Cell(20, 6, $jumlah_izin, 0, 0, 'C');

$pdf->Cell(5, 6, 'Sakit :', 0, 0, 'C');
$pdf->Cell(20, 6, $jumlah_sakit, 0, 0, 'C');

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
$namafile = 'Absensi-' . $nama . '-' . date('YmdHis') . '.pdf';
$pdf->Output('files/' . $namafile, 'F');
readfile('files/' . $namafile);

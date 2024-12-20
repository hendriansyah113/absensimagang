<?php

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

$awal_magang = '';
$akhir_magang = '';
$mulai_bulan = '';
$akhir_bulan = '';
$mulai_hari = '';
$akhir_hari = '';
$akhir_tahun = '';

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 7, 'DAFTAR HADIR MAHASISWA MAGANG', 0, 1, 'C');

$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(48, 6, 'Nama', 1, 0, 'C');
$pdf->Cell(40, 6, 'Jumlah Hadir', 1, 0, 'C');
$pdf->Cell(30, 6, 'Jumlah Izin', 1, 0, 'C');
$pdf->Cell(48, 6, 'Jumlah Sakit', 1, 1, 'C');
$pdf->SetFont('Arial', '', 10);

$no = 0;

$sql = "SELECT nama, 
                       COUNT(CASE WHEN status = 'Hadir' THEN 1 END) AS jumlah_hadir,
                       COUNT(CASE WHEN status = 'Izin' THEN 1 END) AS jumlah_izin,
                       COUNT(CASE WHEN status = 'Sakit' THEN 1 END) AS jumlah_sakit
                FROM tbl_absensi
                LEFT JOIN tbl_mahasiswa ON tbl_absensi.id_mahasiswa = tbl_mahasiswa.id_mahasiswa
                GROUP BY tbl_mahasiswa.id_mahasiswa";
$hasil = mysqli_query($kon, $sql);

while ($data = mysqli_fetch_assoc($hasil)) {
    $nama = $data['nama'];
    $jumlah_hadir = $data['jumlah_hadir'];
    $jumlah_izin = $data['jumlah_izin'];
    $jumlah_sakit = $data['jumlah_sakit'];

    $no++;

    // Cell kolom biasa
    $pdf->Cell(10, 10, $no, 1, 0, 'C');
    $pdf->Cell(48, 10, $nama, 1, 0, 'C');
    $pdf->Cell(40, 10, $jumlah_hadir, 1, 0, 'C');
    $pdf->Cell(30, 10, $jumlah_izin, 1, 0, 'C');
    $pdf->Cell(48, 10, $jumlah_sakit, 1, 1, 'C');
}


$tanggal = date('Y-m-d');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(340, 15, '', 0, 1, 'C');
$pdf->Cell(340, 12, '', 0, 1, 'C');
$pdf->Cell(340, 0, 'Pembimbing Magang', 0, 1, 'C');
$pdf->Cell(340, 50, $pembimbing, 0, 1, 'C');

$namafile = 'Absensi';
$pdf->Output('files/' . $namafile, 'F');
readfile('files/' . $namafile);

<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || ($_SESSION['role'] != "petugas" && $_SESSION['role'] != "admin")){
    header("location:../index.php?pesan=gagal"); exit();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=peminjaman_export_'.date('Ymd').'.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['PeminjamanID','UserID','NamaPeminjam','BukuID','Judul','TanggalPeminjaman','TanggalPengembalian','Status']);

$q = mysqli_query($koneksi, "SELECT peminjaman.*, user.NamaLengkap, buku.Judul FROM peminjaman LEFT JOIN user ON peminjaman.UserID=user.UserID LEFT JOIN buku ON peminjaman.BukuID=buku.BukuID ORDER BY PeminjamanID DESC");
while($r = mysqli_fetch_assoc($q)){
    fputcsv($output, [
        $r['PeminjamanID'], $r['UserID'], $r['NamaLengkap'], $r['BukuID'], $r['Judul'], $r['TanggalPeminjaman'], $r['TanggalPengembalian'], $r['StatusPeminjaman']
    ]);
}

fclose($output);
exit();

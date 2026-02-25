<?php
session_start();
include '../koneksi.php';

header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if($q === ''){
    echo json_encode([]);
    exit();
}

$exact = isset($_GET['exact']) && ($_GET['exact'] == '1' || $_GET['exact'] === 'true');
$q_esc = mysqli_real_escape_string($koneksi, $q);
$out = [];
if($exact){
    // exact case-insensitive match
    $sql = "SELECT UserID, NamaLengkap FROM user WHERE LOWER(TRIM(NamaLengkap)) = LOWER(TRIM('$q_esc')) ORDER BY NamaLengkap LIMIT 20";
    $res = mysqli_query($koneksi, $sql);
    while($r = mysqli_fetch_assoc($res)){
        $out[] = ['id' => (int)$r['UserID'], 'name' => $r['NamaLengkap']];
    }
} else {
    $sql = "SELECT UserID, NamaLengkap FROM user WHERE NamaLengkap LIKE '%$q_esc%' ORDER BY NamaLengkap LIMIT 20";
    $res = mysqli_query($koneksi, $sql);
    while($r = mysqli_fetch_assoc($res)){
        $out[] = ['id' => (int)$r['UserID'], 'name' => $r['NamaLengkap']];
    }
}

echo json_encode($out);
exit();

?>

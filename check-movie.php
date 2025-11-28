<?php
header('Content-Type: application/json');
require_once __DIR__ . '/security_headers.php';
include("db.php");

$movieName = isset($_POST['MovieName']) ? trim($_POST['MovieName']) : '';

if ($movieName === '') {
    echo json_encode(['exists' => false]);
    exit;
}

$sql = "SELECT COUNT(*) AS cnt FROM films WHERE Movie_name = ?";
$stmt = mysqli_prepare($mysqli, $sql);

if (!$stmt) {
    echo json_encode(['exists' => false]);
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $movieName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$row = $result ? mysqli_fetch_assoc($result) : null;
$count = $row ? (int)$row['cnt'] : 0;

mysqli_stmt_close($stmt);

echo json_encode(['exists' => $count > 0]);
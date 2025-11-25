<?php
header('Content-Type: application/json');

require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Only POST allowed']);
    exit;
}

$movieId  = isset($_POST['Movie_id']) ? (int)$_POST['Movie_id'] : 0;
$fav      = isset($_POST['Favorite']) ? (int)$_POST['Favorite'] : 0;

if (!$movieId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing Movie_id']);
    exit;
}

$fav = $fav ? 1 : 0;

$stmt = $mysqli->prepare("UPDATE films SET Favorite = ? WHERE Movie_id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error (prepare)']);
    exit;
}

$stmt->bind_param('ii', $fav, $movieId);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error (execute)']);
    exit;
}

echo json_encode([
    'success'  => true,
    'favorite' => $fav,
]);

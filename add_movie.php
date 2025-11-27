<?php
header('Content-Type: application/json');

include("db.php");

$movieName   = trim($_POST['MovieName']   ?? '');
$genre       = trim($_POST['Genre']       ?? '');
$releaseDate = trim($_POST['ReleaseDate'] ?? '');
$score       = trim($_POST['Score']       ?? '');

$captchaA      = isset($_POST['captcha_a']) ? (int)$_POST['captcha_a'] : 0;
$captchaB      = isset($_POST['captcha_b']) ? (int)$_POST['captcha_b'] : 0;
$captchaAnswer = isset($_POST['captcha_answer']) ? (int)$_POST['captcha_answer'] : 0;

if ($captchaAnswer !== ($captchaA + $captchaB)) {
    echo json_encode([
        'success' => false,
        'error'   => 'Captcha failed. Please try again.'
    ]);
    exit;
}


if ($movieName === '' || $genre === '' || $releaseDate === '' || $score === '') {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

$sql = "INSERT INTO films (Movie_name, Genre, Release_Date, Score)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($mysqli, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'sssi', 
    $movieName, 
    $genre, 
    $releaseDate, 
    $score
);

if (mysqli_stmt_execute($stmt)) {
    $newId = mysqli_insert_id($mysqli);
    echo json_encode([
        'success' => true,
        'movie' => [
            'Movie_id' => $newId,
            'Movie_name' => $movieName,
            'Genre' => $genre,
            'Release_Date' => $releaseDate,
            'Score' => (int)$score
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Insert failed.']);
}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);

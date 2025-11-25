<?php
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$id          = isset($_POST['Movie_id']) ? (int)$_POST['Movie_id'] : 0;
$movieName   = trim($_POST['MovieName']   ?? '');
$genre       = trim($_POST['Genre']       ?? '');
$releaseDate = trim($_POST['ReleaseDate'] ?? '');
$score       = trim($_POST['Score']       ?? '');

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid movie ID.']);
    exit;
}

if ($movieName === '' || $genre === '' || $releaseDate === '' || $score === '') {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

$sql = "UPDATE films
        SET Movie_name = ?, Genre = ?, Release_Date = ?, Score = ?
        WHERE Movie_id = ?";

$stmt = mysqli_prepare($mysqli, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'sssii',
    $movieName,
    $genre,
    $releaseDate,
    $score,
    $id
);

if (mysqli_stmt_execute($stmt)) {

    $getFavSql = "SELECT Favorite FROM films WHERE Movie_id = ?";
    $getFav = mysqli_prepare($mysqli, $getFavSql);

     if ($getFav) {
        mysqli_stmt_bind_param($getFav, 'i', $id);
        mysqli_stmt_execute($getFav);
        mysqli_stmt_bind_result($getFav, $fav);
        mysqli_stmt_fetch($getFav);
        mysqli_stmt_close($getFav);
    } else {
        // default if something weird happens
        $fav = 0;
    }

    if ($fav === null) {
        $fav = 0;
    }


    echo json_encode([
        'success' => true,
        'movie' => [
            'Movie_id'     => $id,
            'Movie_name'   => $movieName,
            'Genre'        => $genre,
            'Release_Date' => $releaseDate,
            'Score'        => (int)$score,
 	    'Favorite'     => $fav  
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed.']);
}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);

<?php

header('Content-Type: application/json');

include("db.php");


$search     = trim($_GET['search']     ?? '');
$genre      = trim($_GET['genre']      ?? '');
$yearBefore = trim($_GET['year_before'] ?? '');


$sql   = "SELECT * FROM films WHERE 1=1";
$where = [];
$params = [];
$types  = "";


if ($search !== '') {
    $sql    .= " AND Movie_name LIKE ?";
    $params[] = '%' . $search . '%';
    $types   .= "s";
}


if ($genre !== '') {
    $sql    .= " AND Genre LIKE ?";
    $params[] = '%' . $genre . '%';
    $types   .= "s";
}


if ($yearBefore !== '') {
    $yearInt = (int)$yearBefore;
    if ($yearInt > 0) {
        $sql    .= " AND YEAR(Release_Date) < ?";
        $params[] = $yearInt;
        $types   .= "i";
    }
}


$sql .= " ORDER BY Favorite DESC, Movie_name ASC";

if ($types === "") {
    
    $result = mysqli_query($mysqli, $sql);
    if (!$result) {
        echo json_encode([]);
        mysqli_close($mysqli);
        exit;
    }

    $movies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($mysqli);
    echo json_encode($movies);
    exit;
}


$stmt = mysqli_prepare($mysqli, $sql);
if (!$stmt) {
    echo json_encode([]);
    mysqli_close($mysqli);
    exit;
}


mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$movies = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);

echo json_encode($movies);

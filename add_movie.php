<?php
require_once __DIR__ . '/vendor/autoload.php';

include("db.php");

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $movieName   = trim($_POST['MovieName']   ?? '');
    $genre       = trim($_POST['Genre']       ?? '');
    $releaseDate = trim($_POST['ReleaseDate'] ?? '');
    $score       = trim($_POST['Score']       ?? '');


    if ($movieName === '' || $genre === '' || $releaseDate === '' || $score === '') {
        $error = 'All fields are required.';
    } else {
        $checkSql = "SELECT COUNT(*) AS cnt FROM films WHERE Movie_name = ?";
        $checkStmt = mysqli_prepare($mysqli, $checkSql);
        mysqli_stmt_bind_param($checkStmt, 's', $movieName);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $row = $checkResult ? mysqli_fetch_assoc($checkResult) : null;
        mysqli_stmt_close($checkStmt);

        if ($row && (int)$row['cnt'] > 0) {
            $error = 'This movie is already in the database.';
        } else {
            // INSERT into films table
            $sql = "INSERT INTO films (Movie_name, Genre, Release_Date, Score)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($mysqli, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssi',
                    $movieName,
                    $genre,
                    $releaseDate,
                    $score
                );

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: index.php?success=1");
                    exit;
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}

// If not POST or there was an error, show the form
echo $twig->render('add_movie.twig', [
    'error' => $error
]);

mysqli_close($mysqli);

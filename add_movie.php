<?php
require_once __DIR__ . '/vendor/autoload.php';

include("db.php");

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader);

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $movieName   = $_POST['MovieName']   ?? '';
    $genre       = $_POST['Genre']       ?? '';
    $releaseDate = $_POST['ReleaseDate'] ?? '';
    $score       = $_POST['Score']       ?? '';

    // INSERT into films table
    $sql = "INSERT INTO films (Movie_name, Genre, Release_Date, Score)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($mysqli, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            'sssi',  // s = string, i = integer
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

// Render the template
echo $twig->render('add_movie.twig');

mysqli_close($mysqli);

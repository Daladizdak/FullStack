<?php

	require_once __DIR__ . '/vendor/autoload.php';

	include("db.php");

	// Query movies 
	$query  = "SELECT * FROM films ORDER BY Favorite DESC, Movie_name ASC";
	$result = mysqli_query($mysqli, $query);

	if (!$result) {
		die("Query error: " . mysqli_error($mysqli));
	}

	// Convert result into array
	$movies = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$movies[] = $row;
	}

	// Close connection
	mysqli_close($mysqli);

	$success = isset($_GET['success']) && $_GET['success'] == 1;

	// Setup Twig
	$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
	$twig   = new \Twig\Environment($loader);

	// Render Twig template
	echo $twig->render('movies.twig', [
    	'movies' => $movies,
    	'success' => $success
	]);

<?php

	require_once __DIR__ . '/session.php';
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/security_headers.php';
	include("db.php");

	
	$query  = "SELECT * FROM films ORDER BY Favorite DESC, Movie_name ASC";
	$result = mysqli_query($mysqli, $query);

	if (!$result) {
		die("Query error: " . mysqli_error($mysqli));
	}

	
	$movies = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$movies[] = $row;
	}

	
	mysqli_close($mysqli);

	$success = isset($_GET['success']) && $_GET['success'] == 1;

	
	$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
	$twig   = new \Twig\Environment($loader);

	
	echo $twig->render('movies.twig', [
    	'movies' => $movies,
    	'success' => $success,
	'loggedIn' => !empty($_SESSION['user_id']),
	'username' => $_SESSION['username'] ?? null
	]);

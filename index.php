<?php

	require_once __DIR__ . '/vendor/autoload.php';

	// Connect to MySQL
	$mysqli = new mysqli("localhost","2337117","Bioin150words","db2337117");

	if ($mysqli->connect_errno) {
    		die("Failed to connect: " . $mysqli->connect_error);
	}

	// Query movies
	$query = "SELECT * FROM movies ORDER BY Movie_name"; 
	$result = $mysqli->query($query);

	// Convert result into array
	$movies = [];
	while ($row = $result->fetch_assoc()) {
   		 $movies[] = $row;
	}

	// Setup Twig
	$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
	$twig = new \Twig\Environment($loader);

	// Render Twig template
	echo $twig->render('movies.twig', [
    		'movies' => $movies
	]);


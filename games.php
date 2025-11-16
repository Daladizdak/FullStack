<?php

	// Point to library
	require_once '../../vendor/autoload.php';
	
	// Set up Environment
	$loader = new \Twig\Loader\FilesystemLoader('.');
	$twig = new \Twig\Environment($loader);
	include("db.php");
	
	// Run SQL query
	$sql = "SELECT * FROM videogames ORDER BY released_date";
	$results = mysqli_query($mysqli, $sql);
	
	// How many rows were returned?
	$num_rows = mysqli_num_rows($results);
	
	// Load and render template
	echo $twig->render('games.html',
	array('num_rows' => $num_rows, 'results' => $results));
?>
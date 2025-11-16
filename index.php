<?php
	// Point to library
	require_once 'vendor/autoload.php';
	// Set up Environment
	$loader = new \Twig\Loader\FilesystemLoader('.');
	$twig = new \Twig\Environment($loader);
	// Array of data
	$people[0]['FirstName'] = "Alix";
	$people[0]['Surname'] = "Bergeret";
	$people[1]['FirstName'] = "Hiran";
	$people[1]['Surname'] = "Patel";
	// Load and render template
	echo $twig->render('template.html',
	array('a_variable' => 'Alix',
	'another_variable' => 'Bergeret',
	'people' => $people));
?>
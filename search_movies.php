<?php
	// search_movies.php
	header('Content-Type: application/json');

	// Get search term from query string (e.g. ?search=abc)
	$search = isset($_GET['search']) ? trim($_GET['search']) : '';

	include("db.php");

	//if the search box is empty, All movies will be displayed in order of their name
	if ($search === '') {
		$sql = "SELECT * FROM films ORDER BY Favorite DESC, Movie_name ASC";
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

	// When there *is* a search term, use a prepared statement for more security
	$sql = "SELECT * FROM films 
			WHERE Movie_name LIKE ?
			ORDER BY Favorite DESC, Movie_name ASC";

	$stmt = mysqli_prepare($mysqli, $sql);

	if (!$stmt) {
		echo json_encode([]);
		mysqli_close($mysqli);
		exit;
	}

	
	$like = '%' . $search . '%';
	mysqli_stmt_bind_param($stmt, 's', $like);

	// Execute statement
	mysqli_stmt_execute($stmt);

	// Get result set
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
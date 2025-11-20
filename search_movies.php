<?php
// search_movies.php
header('Content-Type: application/json');

// Get search term from query string (e.g. ?search=abc)
$search = $_GET['search'] ?? '';
$search = trim($search);

// Connect to MySQL
$mysqli = new mysqli("localhost","2337117","Bioin150words","db2337117");

if ($mysqli->connect_errno) {
    echo json_encode([]);
    exit;
}

if ($search === '') {
    // If no keyword typed, return ALL movies (same as index)
    $sql = "SELECT * FROM movies ORDER BY Movie_name";
    $stmt = $mysqli->prepare($sql);
} else {
    // Search by movie name or genre
    $sql = "SELECT * FROM movies 
            WHERE Movie_name LIKE ? 
               OR Genre LIKE ?
            ORDER BY Movie_name";
    $stmt = $mysqli->prepare($sql);

    $like = '%' . $search . '%';
    $stmt->bind_param('ss', $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();

$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}

$stmt->close();
$mysqli->close();

// Send JSON back to the browser
echo json_encode($movies);

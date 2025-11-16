<?php

  $mysqli = new mysqli("localhost","2337117","Bioin150words","db2337117");

  if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }
?>

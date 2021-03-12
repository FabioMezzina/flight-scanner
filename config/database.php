<?php
// Database setup (insert your local db credential)
$host = "localhost";
$username = "root";
$password = "";
$db_name = "flight_scanner";

// connection to the database
try {
  $connection = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}
catch(PDOException $exception) {
  echo "connection failed" . $exception->getMessage();
}
<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "2021_2024";
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
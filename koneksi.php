<?php
// Database configuration
$host = 'localhost'; // Host database (default: localhost)
$username = 'root'; // Username database
$password = ''; // Password database (kosong untuk XAMPP default)
$database = 'pengaduan_masyarakat_5027_5029'; // Nama database

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8 encoding for consistent database interaction
mysqli_set_charset($conn, 'utf8');
?>
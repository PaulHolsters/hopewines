<?php
$servername = "localhost:3306";
$username = "root";
$passwd = "";
$db = "winedows";

$conn = new mysqli($servername, $username, $passwd, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
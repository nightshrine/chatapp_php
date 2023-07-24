<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "chatapp_php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("データベースに接続できませんでした: " . $conn->connect_error);
}
<?php
session_start();

$_SESSION["id"] = null;
$_SESSION["name"] = null;
$_SESSION["email"] = null;

header("Location: login.php");
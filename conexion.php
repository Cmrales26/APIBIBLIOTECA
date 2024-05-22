<?php
$user = "root";
$pass = "";
$server = "mysql:host=localhost;dbname=bdbiblioteca";

$con = new PDO($server, $user, $pass);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
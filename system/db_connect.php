<?php

$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "thechurch";


$connect = mysqli_connect($localhost, $username, $password, $dbname);

if (!$connect) {
    die("Connection failed");
}

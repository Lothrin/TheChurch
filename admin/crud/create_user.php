<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}


if (isset($_POST['create_user'])) {
    $username = cleanInput($_POST['username'], $connect);
    $email = cleanInput($_POST['email'], $connect);
    $password = cleanInput($_POST['password'], $connect);
    $role = cleanInput($_POST['role'], $connect);

    if (createUser($username, $email, $password, $role)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error creating user.";
    }
}

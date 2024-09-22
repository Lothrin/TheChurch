<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}


if (isset($_GET['id'])) {
    $menuId = cleanInput($_GET['id'], $connect);


    if (deleteMenuItem($menuId)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error deleting menu item.";
    }
} else {
    header("Location: ../dashboard.php");
}

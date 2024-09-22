<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}

if (isset($_POST['create_menu'])) {
    $item_name = cleanInput($_POST['item_name'], $connect);
    $description = cleanInput($_POST['description'], $connect);
    $price = cleanInput($_POST['price'], $connect);
    $category = cleanInput($_POST['category'], $connect);


    if (createMenuItem($item_name, $description, $price, $category)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error creating menu item.";
    }
}

<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}


if (isset($_GET['id'])) {
    $reservationId = cleanInput($_GET['id'], $connect);

    if (deleteReservation($reservationId)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error deleting reservation.";
    }
} else {
    header("Location: ../dashboard.php");
}

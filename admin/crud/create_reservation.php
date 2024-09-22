<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}


if (isset($_POST['create_reservation'])) {
    $customer_name = cleanInput($_POST['customer_name'], $connect);
    $customer_email = cleanInput($_POST['customer_email'], $connect);
    $phone_number = cleanInput($_POST['phone_number'], $connect);
    $date = cleanInput($_POST['date'], $connect);
    $time = cleanInput($_POST['time'], $connect);
    $number_of_people = cleanInput($_POST['number_of_people'], $connect);
    $event_id = isset($_POST['event_id']) ? cleanInput($_POST['event_id'], $connect) : null;
    $special_requests = cleanInput($_POST['special_requests'], $connect);

    if (createReservation($customer_name, $customer_email, $phone_number, $date, $time, $number_of_people, $event_id, $special_requests)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error creating reservation.";
    }
}

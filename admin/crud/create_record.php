<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";
require_once "../../system/file_upload.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = cleanInput($_POST['table'], $connect, 'general');

    switch ($table) {
        case 'event':

            $data = [
                'name' => cleanInput($_POST['name'], $connect, 'general'),
                'description' => cleanInput($_POST['description'], $connect, 'general'),
                'date' => cleanInput($_POST['date'], $connect, 'date'),
                'time' => cleanInput($_POST['time'], $connect, 'time')
            ];

            list($uploadedFiles, $uploadErrors) = fileUpload($_FILES['event_images']);

            if (empty($uploadErrors)) {
                $data['image'] = implode(",", $uploadedFiles);
                if (insertRecord('events', $data)) {
                    header("Location: ../dashboard.php");
                } else {
                    echo "Error creating event.";
                }
            } else {
                foreach ($uploadErrors as $error) {
                    echo $error . "<br>";
                }
            }
            break;

        case 'menu':

            $data = [
                'item_name' => cleanInput($_POST['item_name'], $connect, 'general'),
                'description' => cleanInput($_POST['description'], $connect, 'general'),
                'price' => cleanInput($_POST['price'], $connect, 'number'),
                'category' => cleanInput($_POST['category'], $connect, 'general')
            ];

            if (insertRecord('menus', $data)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error creating menu item.";
            }
            break;

        case 'reservation':

            $data = [
                'customer_name' => cleanInput($_POST['customer_name'], $connect, 'general'),
                'customer_email' => cleanInput($_POST['customer_email'], $connect, 'email'),
                'phone_number' => cleanInput($_POST['phone_number'], $connect, 'phone'),
                'date' => cleanInput($_POST['date'], $connect, 'date'),
                'time' => cleanInput($_POST['time'], $connect, 'time'),
                'number_of_people' => cleanInput($_POST['number_of_people'], $connect, 'number'),
                'special_requests' => cleanInput($_POST['special_requests'], $connect, 'general')
            ];


            if (empty($_POST['event_id'])) {
                $data['event_id'] = 'NULL';
            } else {
                $data['event_id'] = cleanInput($_POST['event_id'], $connect, 'number');
            }


            if (insertRecord('reservations', $data)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error creating reservation.";
            }
            break;

        case 'user':

            $data = [
                'username' => cleanInput($_POST['username'], $connect, 'general'),
                'email' => cleanInput($_POST['email'], $connect, 'email'),
                'password_hash' => hash("sha256", cleanInput($_POST['password'], $connect, 'general')),
                'role' => cleanInput($_POST['role'], $connect, 'general')
            ];

            if (insertRecord('users', $data)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error creating user.";
            }
            break;

        default:
            echo "Invalid table specified.";
            break;
    }
}

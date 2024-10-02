<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = cleanInput($_GET['table'], $connect);
    $id = cleanInput($_GET['id'], $connect);

    switch ($table) {
        case 'events':

            $query = "SELECT image FROM events WHERE id = $id";
            $result = mysqli_query($connect, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $event = mysqli_fetch_assoc($result);
                $imageString = $event['image'];
                $images = explode(",", $imageString);

                if (deleteRecord($table, $id)) {

                    foreach ($images as $image) {
                        $imagePath = "../../assets/event-images/" . trim($image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    header("Location: ../dashboard.php");
                    exit();
                } else {
                    echo "Error deleting event.";
                }
            } else {
                echo "Event not found.";
            }
            break;

        case 'menus':
            if (deleteRecord($table, $id)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error deleting menu item.";
            }
            break;

        case 'reservations':
            if (deleteRecord($table, $id)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error deleting reservation.";
            }
            break;

        case 'users':
            if (deleteRecord($table, $id)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error deleting user.";
            }
            break;

        case 'contact_messages':
            if (deleteRecord($table, $id)) {
                header("Location: ../dashboard.php");
            } else {
                echo "Error deleting message.";
            }
            break;

        default:
            header("Location: ../dashboard.php");
            break;
    }
} else {
    header("Location: ../dashboard.php");
}

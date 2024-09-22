<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";

if (!isset($_SESSION['admin'])) {
    header("Location:  ../login/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $eventId = cleanInput($_GET['id'], $connect);


    $query = "SELECT image FROM events WHERE id = $eventId";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $event = mysqli_fetch_assoc($result);
        $imageString = $event['image'];


        $images = explode(",", $imageString);


        if (deleteEvent($eventId)) {

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
} else {
    header("Location: ../dashboard.php");
}

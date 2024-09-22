<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";
require_once "../../system/file_upload.php";


if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}


if (isset($_POST['create_event'])) {
    $name = cleanInput($_POST['name'], $connect);
    $description = cleanInput($_POST['description'], $connect);
    $date = cleanInput($_POST['date'], $connect);
    $time = cleanInput($_POST['time'], $connect);


    list($uploadedFiles, $uploadErrors) = fileUpload($_FILES['event_images']);


    if (empty($uploadErrors)) {

        $images = implode(",", $uploadedFiles);

        if (createEvent($name, $description, $date, $time, $images)) {
            header("Location: ../dashboard.php");
        } else {
            echo "Error creating event.";
        }
    } else {
        foreach ($uploadErrors as $error) {
            echo $error . "<br>";
        }
    }
}

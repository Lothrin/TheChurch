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


    $reservation = showDetailsPage('reservations', $reservationId);


    if (isset($_POST['update_reservation'])) {
        $customer_name = cleanInput($_POST['customer_name'], $connect);
        $customer_email = cleanInput($_POST['customer_email'], $connect);
        $phone_number = cleanInput($_POST['phone_number'], $connect);
        $date = cleanInput($_POST['date'], $connect);
        $time = cleanInput($_POST['time'], $connect);
        $number_of_people = cleanInput($_POST['number_of_people'], $connect);
        $event_id = isset($_POST['event_id']) ? cleanInput($_POST['event_id'], $connect) : null;
        $special_requests = cleanInput($_POST['special_requests'], $connect);


        if (updateReservation($reservationId, $customer_name, $customer_email, $phone_number, $date, $time, $number_of_people, $event_id, $special_requests)) {
            header("Location: ../dashboard.php");
        } else {
            echo "Error updating reservation.";
        }
    }
} else {
    header("Location:../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>The Church International</title>
</head>
<?php include "../../components/hero.php" ?>

<body>
    <div class="container">
        <h1>Edit Reservation</h1>
        <form method="POST" class="crud-form">
            <input type="text" name="customer_name" value="<?= $reservation['customer_name'] ?>" required>
            <input type="email" name="customer_email" value="<?= $reservation['customer_email'] ?>" required>
            <input type="tel" name="phone_number" value="<?= $reservation['phone_number'] ?>" required> <!-- Added phone number field -->
            <input type="date" name="date" value="<?= $reservation['date'] ?>" required>
            <input type="time" name="time" value="<?= $reservation['time'] ?>" required>
            <input type="number" name="number_of_people" value="<?= $reservation['number_of_people'] ?>" required>
            <textarea name="special_requests" placeholder="Special Requests"><?= $reservation['special_requests'] ?></textarea>
            <input type="submit" name="update_reservation" value="Update Reservation">
        </form>
    </div>
</body>
<?php include "../../components/footer.php" ?>

</html>
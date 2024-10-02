<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";


if (!isset($_GET['id'])) {
    echo "Event not found!";
    exit();
}

$eventId = cleanInput($_GET['id'], $connect);


$customEventDetails = showDetailsPage('events', $eventId);


if (!$customEventDetails) {
    echo "Event not found!";
    exit();
}


$firstImage = getFirstImage($customEventDetails['image']);
$eventImages = getEventImages($customEventDetails['image']);


$eventTime = isset($customEventDetails['time']) && !empty($customEventDetails['time']) ? date('h:i A', strtotime($customEventDetails['time'])) : 'Time not set';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title><?= htmlspecialchars($customEventDetails['name']) ?> - The Church International</title>
</head>

<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body>
    <div class="event-details-container">

        <div class="event-header">
            <img src="assets/event-images/<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($customEventDetails['name']) ?>" class="event-main-image">
            <h1 class="event-title"><?= htmlspecialchars($customEventDetails['name']) ?></h1>
            <p class="event-description"><?= htmlspecialchars($customEventDetails['description']) ?></p>
            <p class="event-date-time">Date: <?= formatEventDate($customEventDetails['date']) ?> | Time: <?= $eventTime ?></p>
        </div>


        <?php if (isPastEvent($customEventDetails['date'])) : ?>
            <div class="past-event-section">
                <h2>Photos from the Event</h2>
                <div class="photo-gallery">
                    <?php foreach ($eventImages as $image) : ?>
                        <div class="gallery-item">
                            <img src="assets/event-images/<?= htmlspecialchars(trim($image)) ?>" alt="Event Photo" class="gallery-image">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include "components/footer.php"; ?>
</body>

</html>
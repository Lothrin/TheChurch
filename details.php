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

<?php include "components/header.php"; ?>

<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body class="body">
    <div class="container">
        <div class="event-details-container">

            <div class="event-header">
                <img src="assets/event-images/<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($customEventDetails['name']) ?>" class="event-main-image">
                <h3 class="event-title"><?= htmlspecialchars($customEventDetails['name']) ?></h3>
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
    </div>

</body>
<?php include "components/footer.php"; ?>

</html>
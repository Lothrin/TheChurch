<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";


$events = getAll('events');


$pastEventImages = [];


foreach ($events as $event) {
    if ($event['date'] < getCurrentDate()) {
        $eventImages = getEventImages($event['image']);


        if (!empty($eventImages)) {
            $pastEventImages[] = [
                'event_name' => $event['name'],
                'event_date' => $event['date'],
                'images' => $eventImages,
                'event_id' => $event['id']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="gallery.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Gallery - The Church International</title>
</head>

<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body>
    <div class="gallery-container">
        <h1>Gallery</h1>

        <?php if (!empty($pastEventImages)) : ?>
            <?php foreach ($pastEventImages as $pastEvent) : ?>
                <div class="event-gallery-section">
                    <h3><?= htmlspecialchars($pastEvent['event_name']) ?> (<?= formatEventDate($pastEvent['event_date']) ?>)</h3>
                    <div class="gallery">
                        <?php foreach ($pastEvent['images'] as $image) : ?>
                            <div class="gallery-item">
                                <div class="event-photo">
                                    <img height="100%" width="100%" src="assets/event-images/<?= htmlspecialchars(trim($image)) ?>" alt="Event Photo">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No past event images available.</p>
        <?php endif; ?>
    </div>

    <script src="main.js"></script>
</body>

<?php include "components/footer.php"; ?>

</html>
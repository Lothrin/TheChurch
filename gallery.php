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
<?php include "components/header.php"; ?>
<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body class="body">
    <div class="container">
        <div class="gallery-container">
            <h3>Gallery</h3>

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
    </div>
    <script src="main.js"></script>
</body>

<?php include "components/footer.php"; ?>

</html>
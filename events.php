<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";


$customEvents = getAll('events');


$upcomingCustomEvents = [];
$pastCustomEvents = [];
$currentDate = getCurrentDate();

foreach ($customEvents as $event) {
    if ($event['date'] >= $currentDate) {
        $upcomingCustomEvents[] = $event;
    } else {
        $pastCustomEvents[] = $event;
    }
}
?>

<?php include "components/header.php"; ?>
<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>


<body class="body">
    <div class="container">
        <div class="event-container">

            <h2 class="event-section-title">Upcoming Events</h2>
            <div class="event-list">
                <?php if (!empty($upcomingCustomEvents)) : ?>
                    <?php foreach ($upcomingCustomEvents as $event) : ?>
                        <div class="event-card">

                            <?php $firstImage = getFirstImage($event['image']); ?>
                            <img src="assets/event-images/<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($event['name']) ?>" class="event-image">

                            <div class="event-details">
                                <h3 class="event-title"><?= htmlspecialchars($event['name']) ?></h3>
                                <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
                                <p class="event-date">Date: <?= formatEventDate($event['date']) ?></p>
                                <?php if (!empty($event['time'])) : ?>
                                    <p class="event-time">Time: <?= date('h:i A', strtotime($event['time'])) ?></p>
                                <?php endif; ?>
                                <a href="details.php?id=<?= htmlspecialchars($event['id']) ?>" class="event-link">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="no-events">No upcoming events at the moment.</p>
                <?php endif; ?>
            </div>


            <h2 class="event-section-title">Past Events</h2>
            <div class="event-list">
                <?php if (!empty($pastCustomEvents)) : ?>
                    <?php foreach ($pastCustomEvents as $event) : ?>
                        <div class="event-card">

                            <?php $firstImage = getFirstImage($event['image']); ?>
                            <img src="assets/event-images/<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($event['name']) ?>" class="event-image">

                            <div class="event-details">
                                <h3 class="event-title"><?= htmlspecialchars($event['name']) ?></h3>
                                <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
                                <p class="event-date">Date: <?= formatEventDate($event['date']) ?></p>
                                <?php if (!empty($event['time'])) : ?>
                                    <p class="event-time">Time: <?= date('h:i A', strtotime($event['time'])) ?></p>
                                <?php endif; ?>
                                <a href="details.php?id=<?= htmlspecialchars($event['id']) ?>" class="event-link">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="no-events">No past events available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>

<?php include "components/footer.php"; ?>

</html>
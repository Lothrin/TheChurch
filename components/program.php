<?php
require_once "system/functions.php";


$events = getAll('events');


$upcomingEvents = getUpcomingEvents($events, 5);
?>

<section class="program-section">
    <?php foreach ($upcomingEvents as $index => $event) : ?>
        <?php
        $dayOfWeek = date('l', strtotime($event['db_date']));
        $timeLabel = ($index == 0) ? 'Today' : (($index == 1) ? 'Tomorrow' : $dayOfWeek . ', ' . $event['date']);
        $eventId = htmlspecialchars($event['event_id']);
        $eventDate = htmlspecialchars($event['db_date']);
        ?>
        <div class="program-event-card" style="background-image: url('<?= htmlspecialchars($event['image']) ?>');">
            <div class="event-details">
                <p class="program-event-name"><?= htmlspecialchars($event['name']) ?></p>
                <p class="program-event-time"><?= $timeLabel ?></p>
            </div>
            <?php if ($event['day_of_week'] !== 'Sunday') : ?>
                <a href="reserve.php?event_id=<?= $eventId ?>&date=<?= $eventDate ?>" class="book-button">Book a Table</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";


$nextEvent = getNextEvent($connect);
$firstImage = getFirstImage($nextEvent['image']);
$imagePath = "assets/event-images/$firstImage";

$events = getAll('events');
$allImages = [];
foreach ($events as $event) {
    $images = explode(",", $event['image']);
    foreach ($images as $image) {
        $trimmedImage = trim($image);
        if (!empty($trimmedImage)) {
            $allImages[] = $trimmedImage;
        }
    }
}

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


$upcomingCustomEvents = array_slice($upcomingCustomEvents, 1);
?>



<?php include "components/header.php"; ?>
<?php include "components/hero.php"; ?>
<?php include "components/program.php"; ?>

<body class="body">
    <div class="container">

        <div class="home-grid">

            <div class="next-event">
                <h2>Next Event</h2>

                <?php if ($nextEvent) : ?>
                    <?php if (file_exists($imagePath)) : ?>
                        <div class="event-card" style="background-image: url('<?= $imagePath ?>');">
                            <h2 class="event-date"> <?= formatEventDate($nextEvent['date']) ?></h2>
                        <?php endif; ?>
                        <a href="details.php?id=<?= $nextEvent['id'] ?>" class="event-link">View Details</a>
                        <div class="event-details">
                            <h3 class="event-title"><?= $nextEvent['name'] ?></h3>
                            <p class="event-time">Time: <?= date('h:i A', strtotime($nextEvent['time'])) ?></p>

                        </div>
                        </div>
                    <?php else : ?>
                        <p>No upcoming events at the moment.</p>
                    <?php endif; ?>
            </div>


            <section class="content">
                <h2>Future Events</h2>
                <div class="future-events-slider">

                    <i class="fas fa-arrow-left future-arrow-left" id="futureLeftArrow"></i>


                    <div class="future-events-container" id="futureEventsContainer">
                        <?php if (!empty($upcomingCustomEvents)) : ?>
                            <?php foreach ($upcomingCustomEvents as $event) : ?>
                                <?php

                                $firstImage = getFirstImage($event['image']);
                                $eventImagePath = "assets/event-images/" . htmlspecialchars($firstImage);
                                ?>
                                <div class="future-event-card" style="background-image: url('<?= $eventImagePath ?>');">
                                    <h2 class="event-date">Date: <?= formatEventDate($event['date']) ?></h2>
                                    <a href="details.php?id=<?= $event['id'] ?>" class="event-link">View Details</a>
                                    <div class="event-details">
                                        <h3 class="event-title"><?= htmlspecialchars($event['name']) ?></h3>

                                        <?php if (!empty($event['time'])) : ?>
                                            <p class="event-time">Time: <?= date('h:i A', strtotime($event['time'])) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="no-events">No upcoming events at the moment.</p>
                        <?php endif; ?>
                    </div>


                    <i class="fas fa-arrow-right future-arrow-right" id="futureRightArrow"></i>
                </div>
            </section>


            <section class="gallery">
                <h2>Gallery</h2>
                <div class="slideshow-container">
                    <div id="gallerySlideshow"></div>

                </div>
                <a href="/gallery.php" class="visit-gallery-link">Visit Full Gallery</a>
            </section>


            <section class="reserve">
                <h3>Reserve a Table</h3>

            </section>


            <section class="contact-home">
                <h3>Contact</h3>
            </section>



            <section class="menu">
                <h3>Menu</h3>
            </section>
            <section class="about">
                <div class="photo">
                    <img src="/assets/images/about.JPG" alt="">
                </div>
                <div class="about-text">
                    <h3>Gary & Ines Scott</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident accusantium, magnam hic quos totam eius modi ducimus, voluptatem culpa quidem laudantium qui optio alias aperiam at? Earum voluptatibus dignissimos consequuntur neque, optio, laboriosam id quaerat, rem distinctio tempora placeat. Cumque, vero? Est velit optio laborum sit iusto adipisci autem labore, nesciunt aliquam ratione? Harum, porro rem? Impedit cumque dolorem labore dolore, recusandae quisquam corporis aperiam quas obcaecati quam. Inventore accusamus vitae distinctio quas quam fugiat rerum laudantium laborum dicta corrupti eos neque cumque repellendus blanditiis, est, animi expedita molestias odio commodi, quis iste quasi! Fuga dolore nesciunt magni quisquam et? Est aliquam veniam ut amet vero adipisci quo magnam harum et quam in ipsum temporibus, autem rerum quaerat debitis pariatur iste quod eveniet voluptatum. Amet recusandae accusamus voluptate. Autem harum aperiam molestias neque voluptatibus animi qui dolorum ducimus itaque, maxime adipisci inventore sint aliquam natus, eos in, culpa accusamus placeat.</p>
                </div>

            </section>

            <section class="content2">
                <h3>The Private Cellar</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aspernatur natus animi rem reprehenderit quo consectetur ipsam debitis rerum laboriosam minima impedit corrupti tempora velit eveniet optio molestiae molestias, similique dignissimos.</p>
            </section>
        </div>
    </div>
    </div>
    </div>
    <script src="main.js"></script>
    <script>
        const allImages = [
            <?php
            foreach ($allImages as $image) {
                echo "'assets/event-images/" . htmlspecialchars($image) . "',";
            }
            ?>
        ];

        let currentImageIndex = 0;

        function showNextImage() {

            const slideshowContainer = document.getElementById('gallerySlideshow');
            const gallerySlideshow = document.getElementById('gallerySlideshow');


            slideshowContainer.innerHTML = "";


            const img = document.createElement('img');
            img.src = allImages[currentImageIndex];
            img.alt = "Event Photo";


            gallerySlideshow.appendChild(img);


            currentImageIndex = (currentImageIndex + 1) % allImages.length;
        }


        showNextImage();


        setInterval(showNextImage, 3000);
    </script>
</body>

<?php include "components/footer.php"; ?>

</html>
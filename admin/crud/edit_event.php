<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";
require_once "../../system/file_upload.php";


if (!isset($_SESSION['admin'])) {
    header("Location:  ../login.php");
    exit();
}


if (isset($_GET['id'])) {
    $eventId = cleanInput($_GET['id'], $connect);


    $event = showDetailsPage('events', $eventId);
    $images = explode(',', $event['image']);


    if (isset($_POST['update_event'])) {
        $name = cleanInput($_POST['name'], $connect);
        $description = cleanInput($_POST['description'], $connect);
        $date = cleanInput($_POST['date'], $connect);
        $time = cleanInput($_POST['time'], $connect);


        $selectedImage = cleanInput($_POST['main_image'], $connect);
        if (($key = array_search($selectedImage, $images)) !== false) {
            unset($images[$key]);
            array_unshift($images, $selectedImage);
        }


        if (!empty($_FILES['new_images']['name'][0])) {
            list($uploadedFiles, $uploadErrors) = fileUpload($_FILES['new_images'], "event");
            if (empty($uploadErrors)) {

                $images = array_merge($images, $uploadedFiles);
            } else {
                foreach ($uploadErrors as $error) {
                    echo $error . "<br>";
                }
            }
        }

        $updatedImages = implode(',', $images);


        if (updateEvent($eventId, $name, $description, $date, $time, $updatedImages)) {
            header("Location: ../dashboard.php");
        } else {
            echo "Error updating event.";
        }
    }
} else {
    header("Location: ../dashboard.php");
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
        <h1>Edit Event</h1>
        <form method="POST" class="crud-form" enctype="multipart/form-data">

            <input type="text" name="name" value="<?= $event['name'] ?>" required>

            <textarea name="description" required><?= $event['description'] ?></textarea>


            <input type="date" name="date" value="<?= $event['date'] ?>" required>


            <input type="time" name="time" value="<?= $event['time'] ?>" required>

            <label>Select Main Image:</label>
            <div class="image-selection">
                <?php foreach ($images as $image): ?>
                    <div class="image-option">
                        <input type="radio" name="main_image" value="<?= trim($image) ?>" <?= $images[0] == trim($image) ? 'checked' : '' ?>>
                        <img src="../../assets/event-images/<?= trim($image) ?>" alt="Image" class="image-preview">
                    </div>
                <?php endforeach; ?>
            </div>


            <label for="new_images">Upload New Images:</label>
            <input type="file" name="new_images[]" accept="image/*" multiple>


            <input type="submit" name="update_event" value="Update Event">
        </form>
    </div>
</body>

<?php include "../../components/footer.php" ?>

</html>
<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";
require_once "../../system/file_upload.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = cleanInput($_GET['table'], $connect);
    $id = cleanInput($_GET['id'], $connect);
    $record = showDetailsPage($table, $id);

    if ($record) {
        if (isset($_POST['update_record'])) {
            switch ($table) {
                case 'events':
                    $name = cleanInput($_POST['name'], $connect);
                    $description = cleanInput($_POST['description'], $connect);
                    $date = cleanInput($_POST['date'], $connect);
                    $time = cleanInput($_POST['time'], $connect);

                    $images = explode(',', $record['image']);
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
                    if (updateRecord($table, ['name' => $name, 'description' => $description, 'date' => $date, 'time' => $time, 'image' => $updatedImages], $id)) {
                        header("Location: ../dashboard.php");
                    } else {
                        echo "Error updating event.";
                    }
                    break;

                case 'menus':
                    $item_name = cleanInput($_POST['item_name'], $connect);
                    $description = cleanInput($_POST['description'], $connect);
                    $price = cleanInput($_POST['price'], $connect);
                    $category = cleanInput($_POST['category'], $connect);

                    if (updateRecord($table, ['item_name' => $item_name, 'description' => $description, 'price' => $price, 'category' => $category], $id)) {
                        header("Location: ../dashboard.php");
                    } else {
                        echo "Error updating menu item.";
                    }
                    break;

                case 'reservations':
                    $customer_name = cleanInput($_POST['customer_name'], $connect);
                    $customer_email = cleanInput($_POST['customer_email'], $connect);
                    $phone_number = cleanInput($_POST['phone_number'], $connect);
                    $date = cleanInput($_POST['date'], $connect);
                    $time = cleanInput($_POST['time'], $connect);
                    $number_of_people = cleanInput($_POST['number_of_people'], $connect);
                    $event_id = isset($_POST['event_id']) ? cleanInput($_POST['event_id'], $connect) : null;
                    $special_requests = cleanInput($_POST['special_requests'], $connect);

                    if (updateRecord($table, [
                        'customer_name' => $customer_name,
                        'customer_email' => $customer_email,
                        'phone_number' => $phone_number,
                        'date' => $date,
                        'time' => $time,
                        'number_of_people' => $number_of_people,
                        'event_id' => $event_id,
                        'special_requests' => $special_requests
                    ], $id)) {
                        header("Location: ../dashboard.php");
                    } else {
                        echo "Error updating reservation.";
                    }
                    break;

                case 'users':
                    $username = cleanInput($_POST['username'], $connect);
                    $email = cleanInput($_POST['email'], $connect);
                    $role = cleanInput($_POST['role'], $connect);

                    if (updateRecord($table, ['username' => $username, 'email' => $email, 'role' => $role], $id)) {
                        header("Location: ../dashboard.php");
                    } else {
                        echo "Error updating user.";
                    }
                    break;

                default:
                    header("Location: ../dashboard.php");
                    break;
            }
        }
    } else {
        header("Location: ../dashboard.php");
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
    <title>Edit Record - The Church International</title>
</head>

<body>
    <div class="container">
        <h1>Edit <?= ucfirst($table) ?></h1>
        <form method="POST" class="crud-form" enctype="multipart/form-data">

            <?php if ($table === 'events'): ?>
                <input type="text" name="name" value="<?= $record['name'] ?>" required>
                <textarea name="description" required><?= $record['description'] ?></textarea>
                <input type="date" name="date" value="<?= $record['date'] ?>" required>
                <input type="time" name="time" value="<?= $record['time'] ?>" required>

                <label>Select Main Image:</label>
                <div class="image-selection">
                    <?php $images = explode(',', $record['image']); ?>
                    <?php foreach ($images as $image): ?>
                        <div class="image-option">
                            <input type="radio" name="main_image" value="<?= trim($image) ?>" <?= $images[0] == trim($image) ? 'checked' : '' ?>>
                            <img src="../../assets/event-images/<?= trim($image) ?>" alt="Image" class="image-preview">
                        </div>
                    <?php endforeach; ?>
                </div>

                <label for="new_images">Upload New Images:</label>
                <input type="file" name="new_images[]" accept="image/*" multiple>

            <?php elseif ($table === 'menus'): ?>
                <input type="text" name="item_name" value="<?= $record['item_name'] ?>" required>
                <textarea name="description" required><?= $record['description'] ?></textarea>
                <input type="number" name="price" value="<?= $record['price'] ?>" step="0.01" required>
                <input type="text" name="category" value="<?= $record['category'] ?>" required>

            <?php elseif ($table === 'reservations'): ?>
                <input type="text" name="customer_name" value="<?= $record['customer_name'] ?>" required>
                <input type="email" name="customer_email" value="<?= $record['customer_email'] ?>" required>
                <input type="tel" name="phone_number" value="<?= $record['phone_number'] ?>" required>
                <input type="date" name="date" value="<?= $record['date'] ?>" required>
                <input type="time" name="time" value="<?= $record['time'] ?>" required>
                <input type="number" name="number_of_people" value="<?= $record['number_of_people'] ?>" required>
                <textarea name="special_requests" placeholder="Special Requests"><?= $record['special_requests'] ?></textarea>

            <?php elseif ($table === 'users'): ?>
                <input type="text" name="username" value="<?= $record['username'] ?>" required>
                <input type="email" name="email" value="<?= $record['email'] ?>" required>
                <input type="text" name="role" value="<?= $record['role'] ?>" required>
            <?php endif; ?>

            <input type="submit" name="update_record" value="Update <?= ucfirst($table) ?>">
        </form>
    </div>
</body>

<?php include "../../components/footer.php" ?>

</html>
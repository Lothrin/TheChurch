<?php
session_start();
require_once "../../system/db_connect.php";
require_once "../../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}


if (isset($_GET['id'])) {
    $menuId = cleanInput($_GET['id'], $connect);


    $menu = showDetailsPage('menus', $menuId);


    if (isset($_POST['update_menu'])) {
        $item_name = cleanInput($_POST['item_name'], $connect);
        $description = cleanInput($_POST['description'], $connect);
        $price = cleanInput($_POST['price'], $connect);
        $category = cleanInput($_POST['category'], $connect);


        if (updateMenuItem($menuId, $item_name, $description, $price, $category)) {
            header("Location: ../dashboard.php");
        } else {
            echo "Error updating menu item.";
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
        <h1>Edit Menu Item</h1>
        <form method="POST" class="crud-form">
            <input type="text" name="item_name" value="<?= $menu['item_name'] ?>" required>
            <textarea name="description" required><?= $menu['description'] ?></textarea>
            <input type="number" name="price" value="<?= $menu['price'] ?>" step="0.01" required>
            <input type="text" name="category" value="<?= $menu['category'] ?>" required>
            <input type="submit" name="update_menu" value="Update Menu Item">
        </form>
    </div>
</body>
<?php include "../../components/footer.php" ?>

</html>
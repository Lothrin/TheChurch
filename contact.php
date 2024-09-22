<?php
session_start();
require_once "system/db_connect.php";
require_once "system/functions.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = cleanInput($_POST['name'], $connect);
    $email = cleanInput($_POST['email'], $connect);
    $phone_number = cleanInput($_POST['phone_number'], $connect);
    $message = cleanInput($_POST['message'], $connect);

    $sql = "INSERT INTO contact_messages (name, email, phone_number, message, created_at) 
            VALUES ('$name', '$email', '$phone_number', '$message', NOW())";

    if (mysqli_query($connect, $sql)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Failed to send message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>The Church International</title>
</head>
<?php include "components/hero.php"; ?>

<body class="contact-body">

    <section class="contact-container">
        <div class="contact-details">
            <h1>Visit Us</h1>
            <p><strong></strong></p>
            <p>Radetzskystraße 3</p>
            <p>1030, Vienna</p>
            <p>Austria</p>
            <hr class="contact-hr">
            <h1>Owner</h1>
            <p><strong></strong> Gary Scott</p>
            <p><strong></strong> +43 650 730 0447</p>
            <p><strong></strong> garyscott@mail.com</p>
        </div>

        <div class="contact-form-wrapper">
            <h2>Get in Touch</h2>

            <?php if (isset($success)): ?>
                <p class="contact-success-message"><?= $success ?></p>
            <?php elseif (isset($error)): ?>
                <p class="contact-error-message"><?= $error ?></p>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="contact-form-custom">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="submit" value="Send Message">
            </form>
        </div>
    </section>

</body>
<?php include "components/footer.php"; ?>

</html>
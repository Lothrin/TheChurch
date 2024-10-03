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

<?php include "components/header.php"; ?>
<?php include "components/hero.php"; ?>

<body class="body">

    <section class="container">
        <div class="contact-details">
            <h3>Visit Us</h3>
            <p><strong></strong></p>
            <p>Radetzskystraße 3</p>
            <p>1030, Vienna</p>
            <p>Austria</p>
            <hr class="contact-hr">
            <h3>Owner</h3>
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
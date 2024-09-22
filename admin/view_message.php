<?php
session_start();
require_once "../system/db_connect.php";
require_once "../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location: /admin/login/login.php");
    exit();
}


if (!isset($_GET['id'])) {
    echo "Message not found!";
    exit();
}

$messageId = cleanInput($_GET['id'], $connect);


$query = "SELECT * FROM contact_messages WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $messageId);
$stmt->execute();
$message = $stmt->get_result()->fetch_assoc();

if (!$message) {
    echo "Message not found!";
    exit();
}


$updateQuery = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
$stmt = $connect->prepare($updateQuery);
$stmt->bind_param("i", $messageId);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../components/hero.css">
    <link rel="stylesheet" href="view_message.css">
    <link rel="stylesheet" href="../components/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>View Message</title>
</head>
<?php include "../components/hero.php"; ?>

<body>
    <div class="message-container">
        <h1>Message from <?= htmlspecialchars($message['name']) ?></h1>
        <p><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($message['phone_number']) ?></p>
        <p><strong>Received at:</strong> <?= date('F j, Y h:i A', strtotime($message['created_at'])) ?></p>
        <p><strong>Message:</strong></p>
        <p> <?= nl2br(htmlspecialchars($message['message'])) ?> </p>

        <a href="inbox.php" class="back-btn">Back to Inbox</a>
    </div>

    <script src="../main.js"></script>
</body>

<?php include "../components/footer.php"; ?>

</html>
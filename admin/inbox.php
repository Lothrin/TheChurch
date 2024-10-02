<?php
session_start();
require_once "../system/db_connect.php";
require_once "../system/functions.php";


if (!isset($_SESSION['admin'])) {
    header("Location: /admin/login/login.php");
    exit();
}


$messages = getAll('contact_messages');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="inbox.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans+Condensed:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Inbox - Contact Messages</title>
</head>
<?php include "../components/hero.php"; ?>

<body>
    <div class="inbox-container">
        <h1>Inbox - Contact Messages</h1>

        <?php if (!empty($messages)) : ?>
            <table class="inbox-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Message</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) : ?>
                        <tr>
                            <td><?= htmlspecialchars($message['id']) ?></td>
                            <td><?= htmlspecialchars($message['name']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td><?= htmlspecialchars($message['phone_number']) ?></td>
                            <td><?= substr(htmlspecialchars($message['message']), 0, 50) ?>...</td>
                            <td><?= date('F j, Y h:i A', strtotime($message['created_at'])) ?></td>
                            <td>
                                <a href="view_message.php?id=<?= $message['id'] ?>" class="view-message-btn">View</a>
                                <?php if (!$message['is_read']): ?>
                                    <span class="unread-indicator">Unread</span>
                                <?php endif; ?>
                                <a href="crud/delete_record.php?table=contact_messages&id=<?= $message['id'] ?>" class="delete-message-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No messages received yet.</p>
        <?php endif; ?>
    </div>

    <script src="../main.js"></script>
</body>

<?php include "../components/footer.php"; ?>

</html>
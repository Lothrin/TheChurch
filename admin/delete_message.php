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


$query = "DELETE FROM contact_messages WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $messageId);

if ($stmt->execute()) {
    header("Location: inbox.php?success=Message deleted successfully");
} else {
    echo "Error deleting message.";
}

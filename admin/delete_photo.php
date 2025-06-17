<?php
session_start();
require "../db.php";

if (!isset($_SESSION["admin"])) {
    echo "Access Denied!";
    exit;
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

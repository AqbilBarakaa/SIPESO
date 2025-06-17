<?php
session_start();
require "../db.php";

if (!isset($_SESSION["admin"])) {
    echo "Access Denied!";
    exit;
}

if (isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    
    // Check if directory exists, if not create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
        $photo_url = '../uploads/' . basename($photo["name"]);
        $stmt = $kon->prepare("INSERT INTO gallery (photo_url) VALUES (?)");
        $stmt->bind_param("s", $photo_url);
        $stmt->execute();
        $stmt->close();
        header("Location: galeri.php");
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

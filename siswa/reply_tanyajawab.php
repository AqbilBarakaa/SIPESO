<?php
session_start();
require "../db.php";

if (!isset($_SESSION["siswa"]) && !isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

$nisn = isset($_SESSION['siswa']) ? $_SESSION['siswa'] : $_SESSION['admin'];
$user_type = $_POST['user_type'];
$parent_id = mysqli_real_escape_string($kon, $_POST['parent_id']);
$reply_content = mysqli_real_escape_string($kon, $_POST['reply']);

$insert_query = "INSERT INTO tanya_jawab (nisn, parent_id, content, user_type) VALUES ('$nisn', '$parent_id', '$reply_content', '$user_type')";

if (mysqli_query($kon, $insert_query)) {
    echo '<script>alert("Balasan berhasil dikirim!"); window.location="tanyajawab.php";</script>';
} else {
    echo '<script>alert("Gagal menyimpan balasan: ' . mysqli_error($kon) . '"); window.location="tanyajawab.php";</script>';
}
?>

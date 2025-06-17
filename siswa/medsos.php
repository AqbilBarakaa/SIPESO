<?php
session_start();
require "../db.php";
$page = "media_sosial";

if (!isset($_SESSION["siswa"])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MEDIA SOSIAL</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill.quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
</head>

<body>

    <!-- HEADER -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR -->
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-people"></i>&nbsp; MEDIA SOSIAL</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">MEDIA SOSIAL</li>
                </ol>
            </nav>
        </div>
        <div class="container my-5">
            <div class="text-center mb-4">
                <h2>MEDIA SOSIAL</h2>
                <p>Temukan kami di berbagai platform media sosial untuk mendapatkan update terbaru tentang kegiatan dan informasi sekolah.</p>
            </div>
            <div class="text-center mb-5" style="margin-bottom: 100px;">
                <h1>MA AL-MARDLIYYAH PAMEKASAN</h1>
            </div>
            <!-- Media Sosial Cards -->
            <div class="row align-items-center mb-5" style="margin-bottom: 100px;">
                <div class="col">
                    <div class="card text-center shadow p-3 mb-5 bg-body rounded mb-5" style="margin-bottom: 150px">
                        <div class="card-body">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Facebook" class="img-fluid rounded-circle mb-3" style="max-width: 100px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3)">
                            <h4 class="card-title">Facebook</h4>
                            <p class="card-text">Ikuti kami di Facebook untuk melihat foto dan video terbaru.</p>
                            <a href="https://www.facebook.com/p/MA-AL-Mardliyyah-100057332115485/" class="btn btn-primary">Kunjungi Facebook</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center shadow p-3 mb-5 bg-body rounded mb-5" style="margin-bottom: 150px">
                        <div class="card-body">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/95/Twitter_new_X_logo.png" alt="X" class="img-fluid rounded-circle mb-3" style="max-width: 100px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3)">
                            <h4 class="card-title">X</h4>
                            <p class="card-text">Ikuti kami di X untuk melihat foto dan video terbaru.</p>
                            <a href="https://x.com/al_mardliyyah?lang=en" class="btn btn-primary">Kunjungi X</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center shadow p-3 mb-5 bg-body rounded mb-5" style="margin-bottom: 150px">
                        <div class="card-body">
                        <img src="https://github.com/Asepbotz/Portofolio/blob/main/img/Ig.png?raw=true" alt="Instagram" class="img-fluid rounded-circle mb-3" style="max-width: 100px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3)">
                            <h4 class="card-title">Instagram</h4>
                            <p class="card-text">Ikuti kami di Instagram untuk melihat foto dan video terbaru.</p>
                            <a href="https://www.instagram.com/maalmardliyyah/" class="btn btn-primary">Kunjungi Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    
    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

</body>

</html>

<?php
session_start();
require "../db.php";
$page = "about";

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
    <title>ABOUT</title>
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
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
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
            <h1><i class="bi bi-info-circle"></i>&nbsp; ABOUT</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">ABOUT</li>
                </ol>
            </nav>
        </div>
        
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="about-section">
                            <h2 class="text-center mb-4">MA AL-MARDLIYYAH PAMEKASAN</h2>
                            <div class="row align-items-start">
                                <div class="col-md-6 order-md-1">
                                    <img src="../uploads/almardliyyah.png" alt="School Image" class="img-fluid rounded" style="max-width: 300px;">
                                </div>
                                <div class="col-md-6 order-md-2">
                                    <div class="about-text">
                                        <h3>Visi:</h3>
                                        <p>Terwujudnya Insan Yang Beriman, Berilmu, Beramal, Peduli dan Berbudaya</p>
                                        <h3>Misi:</h3>
                                        <ul>
                                            <li>Menumbuhkan Insan yang Beriman, bertaqwa, berakhlak mulia dan berbudi luhur.</li>
                                            <li>Mewujudkan kegiatan pembelajaran yang menyenangkan, kreatif dan inovatif.</li>
                                            <li>Membentuk Pribadi yang Enterpreneur.</li>
                                            <li>Menguasai dan Mengembangkan IPTEK.</li>
                                            <li>Menumbuhkan Pribadi yang Mandiri, Kompetitif, Berprestasi dan Belajar dengan Sepanjang Hayat.</li>
                                            <li>Membentuk Pribadi yang sehat jasmani dan rohani.</li>
                                            <li>Membangun warga sekolah yang peka akan keindahan dan keharmonisan.</li>
                                            <li>Menumbuhkan warga yang memiliki kepedulian terhadap diri sendiri, keluarga, lingkungan dan berestetika tinggi.</li>
                                            <li>Mewujudkan pendidikan lingkungan hidup yang berkesinambungan, mencegah kerusakan alam, dan melestarikan pembiasaan peduli lingkungan.</li>
                                            <li>Mewujudkan Manajemen Peningkatan Mutu Berbasis Sekolah yang Profesional, Transparan dan Akuntabel.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

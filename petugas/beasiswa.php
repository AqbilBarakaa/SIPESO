<?php
session_start();
require "../db.php";
$page = "beasiswa";

// Cek apakah session siswa ada
if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

$message = "";

// Ambil data beasiswa (hanya yang memiliki judul)
$beasiswa = [];
$result = $kon->query("SELECT * FROM beasiswa WHERE judul IS NOT NULL AND judul != '' ORDER BY id ASC");
while ($row = $result->fetch_assoc()) {
    $beasiswa[] = $row;
}

// Pesan dari URL jika ada
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>BEASISWA</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <style>
        .info-section {
            padding: 50px 0;
        }
        .info-text {
            padding: 30px;
            border-radius: 15px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<?php require "atas.php"; ?>

<!-- SIDEBAR -->
<?php require "menu.php"; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1><i class="bi bi-journal"></i>&nbsp; INFORMASI BEASISWA</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                <li class="breadcrumb-item active">INFORMASI BEASISWA</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($message): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($beasiswa)): ?>
                        <div class="alert alert-info">
                            Tidak ada informasi beasiswa tersedia saat ini.
                        </div>
                    <?php else: ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($beasiswa as $row): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['judul']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                                        <td>
                                            <a href="code_beasiswa.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-arrow-right-circle"></i>&nbsp; Forward</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
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
<script src="../assets/js/main.js"></script>

</body>
</html>
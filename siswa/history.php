<?php
session_start();
require "../db.php";
$page = "history";

if (!isset($_SESSION["siswa"])) {
    header("Location: ../login.php");
    exit;
}

$siswa = $_SESSION["siswa"];
$kue_siswa = mysqli_query($kon, "SELECT * FROM siswa WHERE nisn = '$siswa'");
$row_siswa = mysqli_fetch_array($kue_siswa);

if (!$row_siswa) {
    echo "Siswa tidak ditemukan.";
    exit;
}

$nisn = mysqli_real_escape_string($kon, $row_siswa["nisn"]);

$sql = mysqli_query($kon, "SELECT * FROM pembayaran WHERE nisn = '$siswa' ORDER BY id_pembayaran DESC");

if (!$sql) {
    echo "Terjadi kesalahan pada query: " . mysqli_error($kon);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RIWAYAT PEMBAYARAN</title>
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

    <!-- HEADER!!! -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR!!! -->
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-clock-history"></i>&nbsp; RIWAYAT PEMBAYARAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">RIWAYAT PEMBAYARAN</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <table class="table datatable">
                                <tr>
                                    <th><center>NO</center></th>
                                    <th><center>NAMA PETUGAS</center></th>
                                    <th><center>NISN SISWA</center></th>
                                    <th><center>NAMA SISWA</center></th>
                                    <th><center>TANGGAL BAYAR</center></th>
                                    <th><center>JUMLAH BAYAR</center></th>
                                </tr>

                                <?php
                                $no = 0;
                                while ($gb = mysqli_fetch_array($sql)) {
                                    $no++;
                                ?>

                                <tr class="text-center">
                                    <td><?= $no ?></td>
                                    <td>
                                        <?php
                                        $kue_petugas = mysqli_query($kon, "SELECT * FROM petugas WHERE id_petugas = " . $gb["id_petugas"]);
                                        $pts = mysqli_fetch_array($kue_petugas);
                                        echo $pts['nama_petugas'];
                                        ?>
                                    </td>
                                    <td><?= $gb["nisn"] ?></td>
                                    <td>
                                        <?php
                                        $kue_siswa = mysqli_query($kon, "SELECT * FROM siswa WHERE nisn = " . $gb["nisn"]);
                                        $siswa = mysqli_fetch_array($kue_siswa);
                                        echo $siswa['nama'];
                                        ?>
                                    </td>
                                    <td><?= $gb["tgl_bayar"] ?>-<?= $gb["bulan_dibayar"] ?>-<?= $gb['tahun_dibayar'] ?></td>
                                    <td>Rp<?= number_format($gb["jumlah_bayar"], 0, "", ".") ?>,-</td>
                                </tr>

                                <?php } ?>

                            </table>
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

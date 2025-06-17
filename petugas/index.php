<?php
session_start();
require "../db.php";
$page = "dashboard";

if (!isset($_SESSION["petugas"]))
{
  header("Location: ../login.php");
}

// COUNT SEMUA DATA
$sql_pembayaran = mysqli_query($kon, "SELECT * FROM pembayaran");
$row_pembayaran = mysqli_num_rows($sql_pembayaran);

$sql_keringanan = mysqli_query($kon, "SELECT * FROM pengajuan_keringanan");
$row_keringanan = mysqli_num_rows($sql_keringanan);

$sql_pemasukan = mysqli_query($kon, "SELECT * FROM pembayaran");
$row_pemasukan = mysqli_num_rows($sql_pemasukan);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DASHBOARD</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

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
</head>

<body>

  <!-- ======= Header ======= -->
  <?php require "atas.php"; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php require "menu.php"; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1><i class="bi bi-grid"></i>&nbsp; DASHBOARD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
          <li class="breadcrumb-item active">DASHBOARD</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <div class="col-lg-12">
          <div class="row">

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="history.php">
                <div class="card-body">
                  <h5 class="card-title">RIWAYAT PEMBAYARAN</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_pembayaran, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </a>
             </div>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="pengajuan_keringanan.php">
                <div class="card-body">
                  <h5 class="card-title">PENGAJUAN KERINGANAN</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-chat-square-dots"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_keringanan, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </a>
             </div>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="beasiswa.php">
                <div class="card-body">
                  <h5 class="card-title">TOTAL PEMASUKAN</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-bar-chart"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_pemasukan, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </a>
             </div>
            </div>

          </div>
        </div>

      </div>
    </section>

  </main><!-- End #main -->

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
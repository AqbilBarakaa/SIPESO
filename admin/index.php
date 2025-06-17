<?php
session_start();
require "../db.php";
$page = "dashboard";

if (!isset($_SESSION["admin"]))
{
  header("Location: ../login.php");
}

// MENGHITUNG SEMUA DATA
$sql_siswa = mysqli_query($kon, "SELECT * FROM siswa");
$row_siswa = mysqli_num_rows($sql_siswa);

$sql_petugas = mysqli_query($kon, "SELECT * FROM petugas");
$row_petugas = mysqli_num_rows($sql_petugas);

$sql_kelas = mysqli_query($kon, "SELECT * FROM kelas");
$row_kelas = mysqli_num_rows($sql_kelas);

$sql_spp = mysqli_query($kon, "SELECT * FROM spp");
$row_spp = mysqli_num_rows($sql_spp);

$sql_beasiswa = mysqli_query($kon, "SELECT * FROM beasiswa");
$row_beasiswa = mysqli_num_rows($sql_beasiswa);

$sql_pembayaran = mysqli_query($kon, "SELECT * FROM pembayaran");
$row_pembayaran = mysqli_num_rows($sql_pembayaran);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DASHBOARD</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php 
    include 'aset.php';
  ?>
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

            <!-- ROW CARDS -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
              <a href="siswa.php">
                <div class="card-body">
                  <h5 class="card-title">SISWA</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_siswa, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>
              </div>
            </a>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="petugas.php">
                <div class="card-body">
                  <h5 class="card-title">PETUGAS</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_petugas, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </div>
             </a>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="kelas.php">
                <div class="card-body">
                  <h5 class="card-title">KELAS</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-pass"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_kelas, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </div>
             </a>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="spp.php">
                <div class="card-body">
                  <h5 class="card-title">SPP</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_spp, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </div>
             </a>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="beasiswa.php">
                <div class="card-body">
                  <h5 class="card-title">BEASISWA</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-journal"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?= number_format($row_beasiswa, 0, "", ".") ?></h6>
                    </div>
                  </div>
                </div>

              </div>
             </a>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

              <a href="riwayatpembayaran.php">
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

              </div>
             </a>
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
<?php
session_start();
require "../db.php";

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mengambil data pemasukan per bulan
$data_bulan = [];
for ($i = 1; $i <= 12; $i++) {
    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
    $query = "SELECT SUM(jumlah_bayar) AS total FROM pembayaran WHERE bulan_dibayar = '$month' AND tahun_dibayar = YEAR(CURDATE())";
    $result = mysqli_query($kon, $query);
    $row = mysqli_fetch_assoc($result);
    $data_bulan[] = $row['total'] ? $row['total'] : 0;
}

// Mengambil data pemasukan per tahun
$data_tahun = [];
$current_year = date('Y');
for ($i = $current_year; $i >= $current_year - 5; $i--) {
    $query = "SELECT SUM(jumlah_bayar) AS total FROM pembayaran WHERE tahun_dibayar = '$i'";
    $result = mysqli_query($kon, $query);
    $row = mysqli_fetch_assoc($result);
    $data_tahun[] = ['tahun' => $i, 'total' => $row['total'] ? $row['total'] : 0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Grafik Pemasukan</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php 
    include 'aset.php'
    ?>
</head>
<body>
    <!-- HEADER -->
    <?php include 'atas.php'; ?>
    <!-- SIDEBAR -->
    <?php include 'menu.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-graph-up"></i>&nbsp; Grafik Pemasukan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Grafik Pemasukan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pemasukan Bulanan</h5>
                            <canvas id="chartBulanan" width="400" height="200"></canvas>
                            <script>
                                var ctx = document.getElementById('chartBulanan').getContext('2d');
                                var chartBulanan = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                                        datasets: [{
                                            label: 'Pemasukan (Rp)',
                                            data: <?php echo json_encode($data_bulan); ?>,
                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Pemasukan Tahunan</h5>
                            <canvas id="chartTahunan" width="400" height="200"></canvas>
                            <script>
                                var ctx = document.getElementById('chartTahunan').getContext('2d');
                                var chartTahunan = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: <?php echo json_encode(array_column($data_tahun, 'tahun')); ?>,
                                        datasets: [{
                                            label: 'Pemasukan (Rp)',
                                            data: <?php echo json_encode(array_column($data_tahun, 'total')); ?>,
                                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                            borderColor: 'rgba(153, 102, 255, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>

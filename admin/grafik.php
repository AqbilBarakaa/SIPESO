<?php
session_start();
require "../db.php";
$page = "grafik_pembayaran";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
}

// Fetch payment data
$semester_payments_query = mysqli_query($kon, "SELECT COUNT(*) as count, bulan_dibayar, tahun_dibayar FROM pembayaran WHERE payment_type='semester' GROUP BY tahun_dibayar, bulan_dibayar ORDER BY tahun_dibayar, bulan_dibayar");
$monthly_payments_query = mysqli_query($kon, "SELECT COUNT(*) as count, bulan_dibayar, tahun_dibayar FROM pembayaran WHERE payment_type='bulanan' GROUP BY tahun_dibayar, bulan_dibayar ORDER BY tahun_dibayar, bulan_dibayar");

$semester_payments = [];
$monthly_payments = [];

while ($row = mysqli_fetch_assoc($semester_payments_query)) {
    $semester_payments[] = $row;
}

while ($row = mysqli_fetch_assoc($monthly_payments_query)) {
    $monthly_payments[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Grafik Pembayaran</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    
    <?php include 'aset.php'; ?>
</head>
<body>
    <!-- HEADER!!! -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR!!! -->
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-bar-chart"></i>&nbsp; GRAFIK PEMBAYARAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">GRAFIK PEMBAYARAN</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="paymentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php
                        // Collect all unique month-year combinations
                        $labels = [];
                        foreach ($semester_payments as $payment) {
                            $labels[] = $payment['bulan_dibayar'] . '-' . $payment['tahun_dibayar'];
                        }
                        foreach ($monthly_payments as $payment) {
                            $labels[] = $payment['bulan_dibayar'] . '-' . $payment['tahun_dibayar'];
                        }
                        $labels = array_unique($labels);
                        sort($labels);
                        echo '"' . implode('", "', $labels) . '"';
                        ?>
                    ],
                    datasets: [{
                        label: 'Pembayaran Persemester',
                        data: [
                            <?php
                            foreach ($labels as $label) {
                                $count = 0;
                                foreach ($semester_payments as $payment) {
                                    if ($payment['bulan_dibayar'] . '-' . $payment['tahun_dibayar'] === $label) {
                                        $count = $payment['count'];
                                        break;
                                    }
                                }
                                echo $count . ', ';
                            }
                            ?>
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Pembayaran Perbulan',
                        data: [
                            <?php
                            foreach ($labels as $label) {
                                $count = 0;
                                foreach ($monthly_payments as $payment) {
                                    if ($payment['bulan_dibayar'] . '-' . $payment['tahun_dibayar'] === $label) {
                                        $count = $payment['count'];
                                        break;
                                    }
                                }
                                echo $count . ', ';
                            }
                            ?>
                        ],
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

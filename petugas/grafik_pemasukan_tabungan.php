<?php
session_start();
require "../db.php";
$page = "grafik_pemasukan_tabungan";

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mengambil data tabungan untuk grafik
$query = "
    SELECT 
        s.nama, 
        SUM(t.jumlah_tabungan) as total_tabungan
    FROM 
        tabungan t
        JOIN siswa s ON t.nisn = s.nisn
    GROUP BY 
        s.nama
    ORDER BY 
        total_tabungan DESC";

$result = mysqli_query($kon, $query);

if (!$result) {
    echo '<script>alert("Query gagal: ' . mysqli_error($kon) . '");</script>';
}

// Menyiapkan data untuk grafik
$data_nama = [];
$data_total_tabungan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data_nama[] = htmlspecialchars($row['nama']);
    $data_total_tabungan[] = $row['total_tabungan'];
}

$data_nama_json = json_encode($data_nama);
$data_total_tabungan_json = json_encode($data_total_tabungan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Grafik Pemasukan Tabungan</title>
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
            <h1><i class="bi bi-bar-chart-line"></i>&nbsp; Grafik Pemasukan Tabungan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Grafik Pemasukan Tabungan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Grafik Pemasukan Tabungan Siswa</h5>
                            <canvas id="tabunganChart" width="400" height="200"></canvas>
                            <script>
                                var ctx = document.getElementById('tabunganChart').getContext('2d');
                                var tabunganChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo $data_nama_json; ?>,
                                        datasets: [{
                                            label: 'Total Tabungan (Rp)',
                                            data: <?php echo $data_total_tabungan_json; ?>,
                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
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

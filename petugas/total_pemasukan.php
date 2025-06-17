<?php
session_start();
require "../db.php";
$page = "total_masuk";

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inisialisasi variabel
$total_pemasukan = 0;
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Query untuk total pemasukan
$query = "SELECT SUM(jumlah_bayar) AS total_pemasukan FROM pembayaran WHERE 1";

// Filter berdasarkan bulan
if ($filter_bulan) {
    $query .= " AND bulan_dibayar = '$filter_bulan'";
}

// Filter berdasarkan tahun
if ($filter_tahun) {
    $query .= " AND tahun_dibayar = '$filter_tahun'";
}

$result = mysqli_query($kon, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_pemasukan = $row['total_pemasukan'];
} else {
    echo '<script>alert("Query gagal: ' . mysqli_error($kon) . '");</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Total Pemasukan</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <?php include 'aset.php'; ?>
</head>
<body>
    <!-- HEADER -->
    <?php include 'atas.php'; ?>
    <!-- SIDEBAR -->
    <?php include 'menu.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-bar-chart"></i>&nbsp; Total Pemasukan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Total Pemasukan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="get" class="row g-3">
                                <div class="col-md-4">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select id="bulan" name="bulan" class="form-select">
                                        <option value="">Semua Bulan</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo $filter_bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select id="tahun" name="tahun" class="form-select">
                                        <option value="">Semua Tahun</option>
                                        <?php
                                        $current_year = date('Y');
                                        for ($i = $current_year; $i >= 2000; $i--): ?>
                                            <option value="<?php echo $i; ?>" <?php echo $filter_tahun == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="grafik_pemasukan.php" class="btn btn-info">Grafik Pemasukan</a>
                                </div>
                            </form>

                            <h5 class="mt-4">Total Pemasukan: Rp<?php echo number_format($total_pemasukan ?? 0, 0, ',', '.'); ?>,-</h5>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Jumlah Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM pembayaran WHERE 1";
                                    
                                    if ($filter_bulan) {
                                        $sql .= " AND bulan_dibayar = '$filter_bulan'";
                                    }
                                    
                                    if ($filter_tahun) {
                                        $sql .= " AND tahun_dibayar = '$filter_tahun'";
                                    }
                                    
                                    $sql .= " ORDER BY tgl_bayar DESC";
                                    $result = mysqli_query($kon, $sql);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $row['nisn']; ?></td>
                                        <td>
                                            <?php
                                            $siswa_result = mysqli_query($kon, "SELECT nama FROM siswa WHERE nisn = '{$row['nisn']}'");
                                            $siswa = mysqli_fetch_assoc($siswa_result);
                                            echo $siswa['nama'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $tgl_bayar = $row['tgl_bayar'];
                                            $bulan_bayar = $row['bulan_dibayar'];
                                            $tahun_bayar = $row['tahun_dibayar'];
                                            
                                            if ($tgl_bayar && $bulan_bayar && $tahun_bayar) {
                                                echo sprintf("%02d-%02d-%d", $tgl_bayar, $bulan_bayar, $tahun_bayar);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>Rp<?php echo number_format($row['jumlah_bayar'], 0, ',', '.'); ?>,-</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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

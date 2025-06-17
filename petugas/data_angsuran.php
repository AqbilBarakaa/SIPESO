<?php
session_start();
require "../db.php";
$page = "angsuran";

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inisialisasi variabel filter
$filter_angsuran = isset($_GET['filter_angsuran']) ? $_GET['filter_angsuran'] : '';

// Query untuk mengambil data siswa yang melakukan angsuran
$query = "
    SELECT 
        s.nisn, 
        s.nama, 
        p.angsuran, 
        SUM(p.jumlah_bayar) as total_bayar
    FROM 
        pembayaran p
        INNER JOIN siswa s ON p.nisn = s.nisn
    WHERE 
        p.angsuran IS NOT NULL 
        AND p.angsuran != ''
        AND p.angsuran != '0'";

if ($filter_angsuran != '') {
    $query .= " AND p.angsuran = '" . mysqli_real_escape_string($kon, $filter_angsuran) . "'";
}

$query .= "
    GROUP BY s.nisn, s.nama, p.angsuran 
    ORDER BY s.nama ASC";

$spp_query = "SELECT s.nisn, spp.nominal FROM siswa s LEFT JOIN spp ON s.id_spp = spp.id_spp";
$spp_result = mysqli_query($kon, $spp_query);
$spp_data = array();
if($spp_result) {
    while($spp_row = mysqli_fetch_assoc($spp_result)) {
        $spp_data[$spp_row['nisn']] = $spp_row['nominal'] ? $spp_row['nominal'] : 0;
    }
}

$result = mysqli_query($kon, $query);

if (!$result) {
    echo '<script>alert("Query gagal: ' . mysqli_error($kon) . '");</script>';
    die("Query Error: " . mysqli_error($kon));
}

$data_angsuran = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data_angsuran[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>DATA ANGSURAN</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
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
    <?php include 'atas.php'; ?>
    <!-- SIDEBAR -->
    <?php include 'menu.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-card-list"></i>&nbsp; DATA ANGSURAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">DATA ANGSURAN</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Siswa yang Melakukan Angsuran</h5>
                            
                            <form method="get" class="mb-4">
                                <label for="filter_angsuran" class="form-label">Filter Jenis Angsuran:</label>
                                <select class="form-select" name="filter_angsuran" id="filter_angsuran" onchange="this.form.submit()">
                                    <option value="">Semua</option>
                                    <option value="6" <?php echo $filter_angsuran == '6' ? 'selected' : ''; ?>>6x / Bulan</option>
                                    <option value="12" <?php echo $filter_angsuran == '12' ? 'selected' : ''; ?>>12x / Bulan</option>
                                </select>
                            </form>
                            
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Angsuran</th>
                                        <th>Total Bayar</th>
                                        <th>Sisa Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($data_angsuran) > 0): ?>
                                        <?php foreach ($data_angsuran as $row): ?>
                                            <?php 
                                                $nominal_spp = isset($spp_data[$row['nisn']]) ? $spp_data[$row['nisn']] : 0;
                                                $sisa_bayar = $nominal_spp - $row['total_bayar'];
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['nisn']); ?></td>
                                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                                <td><?php echo $row['angsuran'] . 'x'; ?></td>
                                                <td>Rp<?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>,-</td>
                                                <td>Rp<?php echo number_format($sisa_bayar, 0, ',', '.'); ?>,-</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                Tidak ada data angsuran siswa yang ditemukan.
                                                <br><small>Pastikan ada data pembayaran dengan kolom angsuran yang terisi.</small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
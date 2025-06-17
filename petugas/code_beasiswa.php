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

// Fungsi untuk menghasilkan kode promo
function generatePromoCode($length = 5) {
    return strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length));
}

// Handle kode promo pembuatan
if (isset($_POST['generate_promo'])) {
    $promo_code = generatePromoCode();
    $discount_amount = 1000000; // Nilai diskon tetap

    $query = "INSERT INTO code_beasiswa (code, discount_amount) VALUES ('$promo_code', $discount_amount)";
    if (mysqli_query($kon, $query)) {
        echo "<script>alert('Kode promo berhasil dibuat: $promo_code');</script>";
    } else {
        echo '<script>alert("Gagal menyimpan kode promo: ' . mysqli_error($kon) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>KODE BEASISWA</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
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
</head>
<body>
    <!-- HEADER -->
    <?php include 'atas.php'; ?>
    <!-- SIDEBAR -->
    <?php include 'menu.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-percent"></i>&nbsp; KODE BEASISWA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">KODE BEASISWA</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Generate Kode</h5>
                            <form method="post">
                                <button type="submit" name="generate_promo" class="btn btn-primary">Generate Kode</button>
                            </form>

                            <h5 class="card-title">Kode Beasiswa yang Tersedia</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode Beasiswa</th>
                                        <th>Diskon</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $promo_query = "SELECT * FROM code_beasiswa";
                                    $promo_result = mysqli_query($kon, $promo_query);
                                    while ($promo_row = mysqli_fetch_assoc($promo_result)) {
                                        echo "<tr>";
                                        echo "<td>{$promo_row['code']}</td>";
                                        echo "<td>Rp. " . number_format($promo_row['discount_amount'], 0, ',', '.') . "</td>";
                                        echo "<td>" . ($promo_row['used'] ? 'Sudah Digunakan' : 'Tersedia') . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
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

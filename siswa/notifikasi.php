<?php
session_start();
require "../db.php";

if (!isset($_SESSION["siswa"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['siswa'];

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil notifikasi dari database
$query_notif = "SELECT * FROM notifikasi WHERE nisn = '$user_id' ORDER BY tanggal_kirim DESC";
$result_notif = mysqli_query($kon, $query_notif);
echo "Jumlah Notifikasi: " . mysqli_num_rows($result_notif) . "<br>";

// Tandai notifikasi sebagai dibaca
mysqli_query($kon, "UPDATE notifikasi SET status = 'dibaca' WHERE nisn = '$user_id' AND status = 'terkirim'");
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>NOTIFIKASI</title>
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
</head>
<body>
    <!-- HEADER -->
    <?php include 'atas.php'; ?>
    <!-- SIDEBAR -->
    <?php include 'menu.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-question-circle"></i>&nbsp; NOTIFIKASI</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">NOTIFIKASI</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Notifikasi</h5>
                            <?php if (mysqli_num_rows($result_notif) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result_notif)): ?>
                                    <div class="alert alert-<?php echo ($row['status'] == 'terkirim') ? 'warning' : 'info'; ?>" role="alert">
                                        <b><?= htmlspecialchars($row['judul']); ?></b><br>
                                        <?php
                                         $id_petugas = $row['id_petugas'];  // Mengambil id_petugas dari hasil notifikasi
                                         $kue_petugas = mysqli_query($kon, "SELECT nama_petugas FROM petugas WHERE id_petugas = '$id_petugas'");
                                         $row_petugas = mysqli_fetch_array($kue_petugas);
                                         $nama_petugas = $row_petugas ? htmlspecialchars($row_petugas['nama_petugas']) : 'Petugas tidak ditemukan';
                                     
                                        $nisn = htmlspecialchars($row['nisn']);
                                        $kue_siswa = mysqli_query($kon, "SELECT nama FROM siswa WHERE nisn = '$nisn'");
                                        $row_siswa = mysqli_fetch_array($kue_siswa);
                                        $nama_siswa = $row_siswa ? htmlspecialchars($row_siswa['nama']) : 'Nama tidak ditemukan';
                                        $tanggal_kirim = date('d-m-Y H:i', strtotime($row['tanggal_kirim']));
                                        ?>
                                        <?php
                                        $nisn = $row['nisn'];
                                        $query_siswa = mysqli_query($kon, "SELECT id_spp FROM siswa WHERE nisn = '$nisn'");
                                        $siswa = mysqli_fetch_assoc($query_siswa);
                                        if (!$siswa) {
                                            echo "Siswa tidak ditemukan.";
                                            exit;
                                        }
                                        $id_spp = $siswa['id_spp'];
                                        $query_spp = mysqli_query($kon, "SELECT nominal FROM spp WHERE id_spp = '$id_spp'");
                                        $spp = mysqli_fetch_assoc($query_spp);
                                        if (!$spp) {
                                            echo "Data SPP tidak ditemukan.";
                                            exit;
                                        }
                                        ?>
    
                                        <strong>NISN:</strong> <?= $nisn; ?><br>
                                        <strong>NAMA SISWA:</strong> <?= $nama_siswa; ?><br>
                                        <strong>JUMLAH BAYAR:</strong> Rp<?= number_format($spp['nominal'], 0, ',', '.'); ?>,-<br>

                                        <strong>STATUS:</strong> <?= htmlspecialchars($row['status']); ?><br>
                                        <strong>KETERANGAN:</strong> <?= htmlspecialchars($row['keterangan']); ?><br>
                                        <small><i><?= $nama_petugas; ?> memberikan notifikasi pada <?= $tanggal_kirim; ?></i></small>
                                    </div>

                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada notifikasi.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Bootstrap JS -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
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
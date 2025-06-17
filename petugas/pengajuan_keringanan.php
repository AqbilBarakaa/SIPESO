<?php
session_start();
require "../db.php";
$page = "keringanan";
// Pastikan admin telah login
if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Reset notifikasi pengajuan baru ketika admin mengunjungi halaman ini
if (isset($_GET['reset_notification'])) {
    mysqli_query($kon, "UPDATE siswa SET pengajuan_baru = 0");
}

// Menghitung jumlah pengajuan yang belum dilihat
$unseen_query = "SELECT COUNT(*) AS total FROM pengajuan_keringanan WHERE status = 'Pending'";
$unseen_result = mysqli_query($kon, $unseen_query);
$unseen_count = mysqli_fetch_assoc($unseen_result)['total'];

// Melakukan update status pengajuan keringanan
if (isset($_POST["update_status"])) {
    $id = $_POST["id"];
    $status = $_POST["status"];
    $update_query = "UPDATE pengajuan_keringanan SET status = '$status' WHERE id = $id";
    if (mysqli_query($kon, $update_query)) {
        echo '<script>alert("Status pengajuan berhasil diupdate!"); window.location="pengajuan_keringanan.php";</script>';
    } else {
        echo '<script>alert("Gagal mengupdate status: ' . mysqli_error($kon) . '");</script>';
    }
}

$pengajuan_query = "SELECT pk.id, pk.alasan, pk.status, pk.created_at, s.nama 
                    FROM pengajuan_keringanan pk 
                    JOIN siswa s ON pk.nis = s.nisn
                    ORDER BY pk.created_at DESC";

$pengajuan = mysqli_query($kon, $pengajuan_query);

// Debugging: Check if there are any rows returned
if (!$pengajuan) {
    echo '<script>alert("Query gagal: ' . mysqli_error($kon) . '");</script>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>PENGAJUAN KERINGANAN</title>
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
            <h1><i class="bi bi-chat-square-dots"></i>&nbsp; PENGAJUAN KERINGANAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">PENGAJUAN KERINGANAN</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Pengajuan Keringanan</h5>
                            <div class="accordion" id="pengajuanAccordion">
                                <?php if (mysqli_num_rows($pengajuan) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($pengajuan)): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading<?php echo $row['id']; ?>">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $row['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $row['id']; ?>">
                                                    <?php echo $row['nama']; ?> mengajukan keringanan pada <?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo $row['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $row['id']; ?>" data-bs-parent="#pengajuanAccordion">
                                                <div class="accordion-body">
                                                    <p><?php echo $row['alasan']; ?></p>
                                                    <hr>
                                                    <p><strong>Status saat ini: </strong><?php echo $row['status']; ?></p>
                                                    <form method="post">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label"><strong>Ubah Status:</strong></label>
                                                            <select name="status" id="status" class="form-select">
                                                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="Disetujui" <?php echo $row['status'] == 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                                                                <option value="Ditolak" <?php echo $row['status'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                                            </select>
                                                        </div>
                                                        <button type="submit" name="update_status" class="btn btn-primary"><i class="bi bi-check-circle"></i>&nbsp; Update Status</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning" role="alert">
                                        Tidak ada pengajuan keringanan ditemukan.
                                    </div>
                                <?php endif; ?>
                            </div>
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

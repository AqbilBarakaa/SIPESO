<?php
session_start();
require "../db.php"; 

if (!isset($_SESSION["siswa"])) {
    header("Location: ../login.php");
    exit;
}

$siswa_id = $_SESSION["siswa"]; 
$result = mysqli_query($kon, "SELECT * FROM siswa WHERE nisn = '$siswa_id'");

if (!$result) {
    die('Invalid query: ' . mysqli_error($kon));
}

$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    echo "Error: Siswa not found.";
    exit;
}

$kelas_id = $siswa['id_kelas'];
$result_kelas = mysqli_query($kon, "SELECT * FROM kelas WHERE id_kelas = '$kelas_id'");

if (!$result_kelas) {
    die('Invalid query: ' . mysqli_error($kon));
}

$kelas = mysqli_fetch_assoc($result_kelas);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profil Siswa</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
</head>

<body>

    <!-- HEADER -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR -->
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-person-circle"></i>&nbsp; Profil Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </nav>
        </div>
        
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-center mb-4">Profil</h2>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($siswa['foto']); ?>" alt="Profile Image" class="img-fluid rounded mb-4" />
                                </div>
                                <div class="col-md-8">
                                    <h3><?php echo htmlspecialchars($siswa['nama']); ?></h3>
                                    <p><strong>NISN:</strong> <?php echo htmlspecialchars($siswa['nisn']); ?></p>
                                    <p><strong>NIS:</strong> <?php echo htmlspecialchars($siswa['nis']); ?></p>
                                    <p><strong>Username:</strong> <?php echo htmlspecialchars($siswa['username']); ?></p>
                                    <p><strong>Kelas:</strong> <?php echo htmlspecialchars($kelas['nama_kelas']); ?></p>
                                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($siswa['alamat']); ?></p>
                                    <p><strong>No Telepon:</strong> <?php echo htmlspecialchars($siswa['no_telp']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

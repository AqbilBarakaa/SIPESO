<?php
session_start();
require "../db.php";

$page = "beasiswa";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($kon, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($kon, $_POST['deskripsi']);

    if (isset($_POST['id']) && $_POST['id'] != "") {
        $id = $_POST['id'];
        $query = "UPDATE beasiswa SET judul = ?, deskripsi = ? WHERE id = ?";
        $stmt = $kon->prepare($query);
        $stmt->bind_param("ssi", $judul, $deskripsi, $id);
        if ($stmt->execute()) {
            $message = "Data berhasil diperbarui.";
        } else {
            $message = "Terjadi kesalahan saat memperbarui data.";
        }
    } else {
        $query = "INSERT INTO beasiswa (judul, deskripsi) VALUES (?, ?)";
        $stmt = $kon->prepare($query);
        $stmt->bind_param("ss", $judul, $deskripsi);
        if ($stmt->execute()) {
            $message = "Data berhasil disimpan.";
        } else {
            $message = "Terjadi kesalahan saat menyimpan data.";
        }
    }

    header("Location: beasiswa.php?message=" . urlencode($message));
    exit;
}

if (isset($_POST["delete"])) {
    $id = mysqli_real_escape_string($kon, $_POST['id']);
    
    $query = "DELETE FROM beasiswa WHERE id = ?";
    $stmt = $kon->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Data berhasil dihapus.";
    } else {
        $message = "Terjadi kesalahan saat menghapus data: " . $kon->error;
    }
    
    header("Location: beasiswa.php?message=" . urlencode($message));
    exit;
}

$beasiswa = [];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $kon->query("SELECT * FROM beasiswa WHERE id = $id");
    $beasiswa = $result->fetch_assoc();
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>BEASISWA</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <style>
        .info-section {
            padding: 50px 0;
        }
        .info-text {
            padding: 30px;
            border-radius: 15px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<?php require "atas.php"; ?>

<!-- SIDEBAR -->
<?php require "menu.php"; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1><i class="bi bi-journal"></i>&nbsp; INFORMASI BEASISWA</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                <li class="breadcrumb-item active">INFORMASI BEASISWA</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH BEASISWA</button>
                    <br><br>
                    <?php if ($message): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $kon->query("SELECT * FROM beasiswa WHERE judul IS NOT NULL AND judul != '' ORDER BY id ASC");
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>$no</td>";
                                echo "<td>{$row['judul']}</td>";
                                echo "<td>{$row['deskripsi']}</td>";
                                echo "<td>
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#editData{$row['id']}' class='btn btn-warning btn-sm'><i class='bi bi-pencil-square'></i></button>
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#hapusData{$row['id']}' class='btn btn-danger btn-sm'><i class='bi bi-trash'></i></button>
                                </td>";
                                echo "</tr>";

                                // Modal Edit Data
                                echo "
                                <div class='modal fade' id='editData{$row['id']}' tabindex='-1'>
                                    <div class='modal-dialog modal-lg'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title'><i class='bi bi-pencil-square'></i>&nbsp; EDIT BEASISWA</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <form method='post'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <div class='form-group'>
                                                        <label>Judul</label>
                                                        <input name='judul' class='form-control' type='text' placeholder='Masukkan judul beasiswa' value='{$row['judul']}' required>
                                                    </div>
                                                    <br>
                                                    <div class='form-group'>
                                                        <label>Deskripsi</label>
                                                        <textarea name='deskripsi' class='form-control' rows='4' placeholder='Masukkan deskripsi beasiswa' required>{$row['deskripsi']}</textarea>
                                                    </div>
                                                    <br>
                                            </div>
                                            <div class='modal-footer'>
                                                <button name='submit' type='submit' class='btn btn-success'><i class='bi bi-check-circle-fill'></i>&nbsp; SAVE</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";

                                // Modal Hapus Data
                                echo "
                                <div class='modal fade' id='hapusData{$row['id']}' tabindex='-1'>
                                    <div class='modal-dialog modal-lg'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title'><i class='bi bi-trash'></i>&nbsp; HAPUS</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <h2 class='text-center'>Apakah Anda yakin ingin menghapus data ini?</h2>
                                                <form method='post'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                            </div>
                                            <div class='modal-footer'>
                                                <button name='delete' type='submit' class='btn btn-danger'><i class='bi bi-check-circle-fill'></i>&nbsp; HAPUS</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                                
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH BEASISWA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Judul</label>
                            <input name="judul" class="form-control" type="text" placeholder="Masukkan judul beasiswa" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi beasiswa" required></textarea>
                        </div>
                        <br>
                </div>
                <div class="modal-footer">
                    <button name="submit" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                    </form>
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
<script src="../assets/js/main.js"></script>

</body>
</html>
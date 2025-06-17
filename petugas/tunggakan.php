<?php
session_start();
require "../db.php";
$page = "tunggakan";

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

// Ambil id_petugas dari session (id_petugas sudah diset saat login)
$id_petugas = $_SESSION["petugas"];

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$current_month = date('m');
$current_year = date('Y');

// Proses pengiriman notifikasi
if (isset($_POST['kirim_notif'])) {
    // Ambil data dari form
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];  // Nama tidak digunakan langsung dalam query notifikasi
    $keterangan = $_POST['keterangan'];
    $judul = "Tunggakan SPP";
    $pesan = "Anda memiliki tunggakan SPP. $keterangan";
    $tanggal_kirim = date('Y-m-d H:i:s');

    // Ambil id_pembayaran terakhir siswa (jika ada)
    $pembayaran = mysqli_fetch_assoc(mysqli_query($kon, "SELECT id_pembayaran FROM pembayaran WHERE nisn='$nisn' ORDER BY tahun_dibayar DESC, bulan_dibayar DESC LIMIT 1"));
    $id_pembayaran = $pembayaran ? $pembayaran['id_pembayaran'] : 0;

    // Simpan notifikasi ke database
    $result = mysqli_query($kon, "INSERT INTO notifikasi (nisn, id_petugas, judul, pesan, status, tanggal_kirim, keterangan) 
                                  VALUES ('$nisn', '$id_petugas','$judul', '$pesan', 'terkirim', '$tanggal_kirim', '$keterangan')");

    if (!$result) {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($kon) . "</div>";
    } else {
        echo '<div class="alert alert-success">Notifikasi berhasil dikirim ke siswa!</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>DATA TUNGGAKAN</title>
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
            <h1><i class="bi bi-list-check"></i>&nbsp; DATA TUNGGAKAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">DATA TUNGGAKAN</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <a href="tunggakan.php?status=all" class="btn btn-primary mb-3">Semua Siswa</a>
                            <a href="tunggakan.php?status=paid" class="btn btn-success mb-3">Siswa yang Sudah Membayar</a>
                            <a href="tunggakan.php?status=unpaid" class="btn btn-danger mb-3">Siswa yang Belum Membayar</a>
                            <table class="table datatable">
                                <tr>
                                    <th><center>NO</center></th>
                                    <th><center>NISN</center></th>
                                    <th><center>NAMA SISWA</center></th>
                                    <th><center>STATUS</center></th>
                                    <th><center>KETERANGAN</center></th>
                                    <th><center>AKSI</center></th>
                                </tr>

                                <?php
                                $no = 0;

                                $query = "SELECT siswa.nisn, siswa.nama, 
                                          IFNULL((SELECT 'Sudah Membayar' FROM pembayaran WHERE pembayaran.nisn = siswa.nisn AND pembayaran.bulan_dibayar = '$current_month' AND pembayaran.tahun_dibayar = '$current_year' LIMIT 1), 'Belum Membayar') AS status, 
                                          IFNULL((SELECT 'Semester' FROM pembayaran WHERE pembayaran.nisn = siswa.nisn AND pembayaran.jumlah_bayar >= (SELECT nominal FROM spp WHERE spp.id_spp = siswa.id_spp) * 6 LIMIT 1), 'Bulanan') AS tipe_pembayaran,
                                          (SELECT bulan_dibayar FROM pembayaran WHERE pembayaran.nisn = siswa.nisn ORDER BY tahun_dibayar DESC, bulan_dibayar DESC LIMIT 1) AS bulan_terakhir,
                                          (SELECT tahun_dibayar FROM pembayaran WHERE pembayaran.nisn = siswa.nisn ORDER BY tahun_dibayar DESC, bulan_dibayar DESC LIMIT 1) AS tahun_terakhir
                                          FROM siswa";

                                if ($status_filter == 'paid') {
                                    $query .= " HAVING status = 'Sudah Membayar'";
                                } elseif ($status_filter == 'unpaid') {
                                    $query .= " HAVING status = 'Belum Membayar'";
                                }

                                $sql = mysqli_query($kon, $query);

                                while ($row = mysqli_fetch_array($sql)) {
                                    $no++;
                                    $status = $row["status"];
                                    $keterangan = "";

                                    $bulan_terakhir = $row['bulan_terakhir'];
                                    $tahun_terakhir = $row['tahun_terakhir'];

                                    if ($status == "Belum Membayar") {
                                        if ($bulan_terakhir == null || $tahun_terakhir == null) {
                                            $keterangan = "Belum pernah membayar SPP sama sekali";
                                        } else if ($tahun_terakhir == $current_year && $bulan_terakhir < $current_month) {
                                            $keterangan = "Belum Membayar untuk bulan " . date("F", mktime(0, 0, 0, $current_month, 10));
                                        } else if ($tahun_terakhir < $current_year) {
                                            $keterangan = "Belum Membayar sejak bulan " . date("F", mktime(0, 0, 0, $bulan_terakhir + 1, 10)) . " " . ($tahun_terakhir);
                                        } else {
                                            $keterangan = "Belum Membayar";
                                        }
                                    } else {
                                        $keterangan = "Sudah Membayar untuk bulan " . date("F", mktime(0, 0, 0, $current_month, 10));
                                    }
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no ?></td>
                                        <td><?= $row["nisn"] ?></td>
                                        <td><?= $row["nama"] ?></td>
                                        <td><?= $status ?></td>
                                        <td><?= $row["tipe_pembayaran"] ?> - <?= $keterangan ?></td>
                                        <td>
                                            <?php if ($status == "Belum Membayar") { ?>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="nisn" value="<?= $row["nisn"] ?>">
                                                    <input type="hidden" name="nama" value="<?= $row["nama"] ?>">
                                                    <input type="hidden" name="keterangan" value="<?= $keterangan ?>">
                                                    <button type="submit" name="kirim_notif" class="btn btn-primary btn-sm">BERI NOTIFIKASI TUNGGAKAN</button>
                                                </form>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/quill/quill.min.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/vendor/php-email-form/validate.js"></script>
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/js/main.js"></script>
</body>

</html>

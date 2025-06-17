<?php
session_start();
require "../db.php";
$page = "riwayatpembayaran";

if (!isset($_SESSION["admin"]))
{
    header("Location: ../login.php");
}

// PROSEDUR HAPUS DATA
if (isset($_POST["delete"])) {
    $id_pembayaran = mysqli_real_escape_string($kon, isset($_POST["id_pembayaran"]) ? $_POST["id_pembayaran"] : "");
    // Hapus data dari tabel pembayaran
    mysqli_query($kon, "DELETE FROM pembayaran WHERE id_pembayaran = '" . $id_pembayaran . "'");
    // SET AUTO_INCREMENT JADI BERURUTAN
    $sql = mysqli_query($kon, "SELECT * FROM pembayaran ORDER BY id_pembayaran");
    $no = 1;
    while ($rows = mysqli_fetch_array($sql)) {
        $id_pembayarannye = $rows["id_pembayaran"];
        // Pastikan kolom yang digunakan di sini benar
        mysqli_query($kon, "UPDATE pembayaran SET id_pembayaran = $no WHERE id_pembayaran = '" . $id_pembayarannye . "'");
        $no++;
    }
    mysqli_query($kon, "ALTER TABLE pembayaran AUTO_INCREMENT = $no");
    echo '
        <script>alert("DATA BERHASIL DIHAPUS !"); window.location = "";</script>
    ';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RIWAYAT PEMBAYARAN</title>
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
            <h1><i class="bi bi-clock-history"></i>&nbsp; RIWAYAT PEMBAYARAN</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">RIWAYAT PEMBAYARAN</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <a href="grafik.php" class="btn btn-info mb-3">Grafik Pembayaran Siswa</a>
                            <table class="table datatable">
                                <?php if (isset($_SESSION["admin"])) { ?>
                                    <a href="cetak2.php" class="btn btn-success mb-3">Cetak PDF</a>
                                <?php } ?>
                                <tr>
                                    <th><center>NO</center></th>
                                    <th><center>NAMA PETUGAS</center></th>
                                    <th><center>NISN SISWA</center></th>
                                    <th><center>NAMA SISWA</center></th>
                                    <th><center>TANGGAL BAYAR</center></th>
                                    <th><center>JUMLAH BAYAR</center></th>
                                    <?php if (isset($_SESSION["admin"]) || isset($_SESSION["petugas"])) { ?>
                                        <th><center>AKSI</center></th>
                                    <?php } ?>
                                </tr>

                                <?php
                                $no = 0;
                                $sql = mysqli_query($kon, "SELECT * FROM pembayaran ORDER BY id_pembayaran DESC");
                                while ($gb = mysqli_fetch_array($sql)) {
                                    $no++;
                                ?>

                                    <tr class="text-center">
                                        <td><?= $no ?></td>
                                        <td>
                                            <?php
                                            $kue_petugas = mysqli_query($kon, "SELECT * FROM petugas WHERE id_petugas = " . $gb["id_petugas"]);
                                            $pts = mysqli_fetch_array($kue_petugas);
                                            echo $pts['nama_petugas'];
                                            ?>
                                        </td>
                                        <td><?= $gb["nisn"] ?></td>
                                        <td>
                                            <?php
                                            $kue_siswa = mysqli_query($kon, "SELECT * FROM siswa WHERE nisn = " . $gb["nisn"]);
                                            $siswa = mysqli_fetch_array($kue_siswa);
                                            echo $siswa['nama'];
                                            ?>
                                        </td>
                                        <td><?= $gb["tgl_bayar"] ?>-<?= $gb["bulan_dibayar"] ?>-<?= $gb['tahun_dibayar'] ?></td>
                                        <td>Rp<?= number_format($gb["jumlah_bayar"], 0, "", ".") ?>,-</td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["id_pembayaran"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i>&nbsp; HAPUS</button>
                                        </td>
                                    </tr>

                                    <!-- HAPUS DATA -->
                                    <div class="modal fade" id="hapusData<?= $gb["id_pembayaran"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS HISTORY TRANSAKSI</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h2 class="text-center">
                                                        Apakah Anda yakin ingin menghapus data ini ?
                                                    </h2>
                                                    <form method="post">
                                                        <input type="hidden" name="id_pembayaran" value="<?= $gb["id_pembayaran"] ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button name="delete" type="submit" class="btn btn-danger"><i class="bi bi-check-circle-fill"></i>&nbsp; HAPUS</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

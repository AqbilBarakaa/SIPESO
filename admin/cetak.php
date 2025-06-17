<?php
// $conn = mysqli_connect("localhost", "root", "", "spptels");

/*
function tampil($hasil)
{
    global $conn;
    $lemari = mysqli_query($conn, $hasil);
    $wadah = [];
    while ($data = mysqli_fetch_assoc($lemari)) {
        $wadah[] = $data;
    }
    return $wadah;
}

// $id = $_GET['id_pembayaran'];
// $kelas = tampil("SELECT * FROM pembayaran WHERE id_pembayaran = $id")[0];

$nisn = mysqli_real_escape_string($conn, isset($_GET["nisn"]) ? $_GET["nisn"] : "");
$siswa = tampil("SELECT * FROM pembayaran WHERE nisn = " . $nisn);
*/

require "../db.php";
$nisn = mysqli_real_escape_string($kon, isset($_GET["nisn"]) ? $_GET["nisn"] : "");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">


    <title>CETAK</title>
</head>

<body>
    <div class="container">
        <div class="row align-items-center mt-5 text-center pb-5" style="border-bottom: 1px solid black">
            <div class="col-2 text-center">
                <img src="../assets/img/logo.png" alt="">
            </div>
            <div class="col-10" style="border-left: 1px solid black">
                <h2>MA AL-MARDLIYYAH PAMEKASAN</h2>
                <h5>DSN TENGAH I, TAGANGSER LAOK, WARU, PAMEKASAN</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <table class="table datatable">

                    <tr>
                        <th>
                            <center>NAMA PETUGAS</center>
                        </th>
                        <th>
                            <center>NISN SISWA</center>
                        </th>
                        <th>
                            <center>NAMA SISWA</center>
                        </th>
                        <th>
                            <center>TANGGAL BAYAR</center>
                        </th>
                        <th>
                            <center>JUMLAH BAYAR</center>
                        </th>
                    </tr>

                    <?php
                    $no = 0;
                    $sql = mysqli_query($kon, "SELECT * FROM pembayaran WHERE nisn = '" . $nisn . "'");
                    $sql = mysqli_query($kon, "SELECT * FROM pembayaran WHERE id_pembayaran = '" . $_GET['id_pembayaran'] . "'");

                    $gb = mysqli_fetch_array($sql);
                    ?>

                    <tr class="text-center">
                        <td>
                            <?php
                            $kue_petugas = mysqli_query($kon, "SELECT * FROM petugas WHERE id_petugas = '" . $gb["id_petugas"] . "'");

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
                        <td>Rp. <?= number_format($gb["jumlah_bayar"], 0, "", ".") ?>,-</td>

                    </tr>


                    <?php  ?>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 mt-5">
                <div class="row justify-content-end text-center">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12 pb-5">
                                <h5>Kepala Sekolah</h5>
                            </div>
                            <div class="col-12 mt-5 pt-3" style="border-top: 1px solid black">
                                <h4>Abdul&nbsp;Aziz</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>window.print()</script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
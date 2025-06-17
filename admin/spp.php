<?php
session_start();
require "../db.php";
$page = "spp";

if (!isset($_SESSION["admin"]))
{
    header("Location: ../login.php");
}

// PROSEDUR SIMPAN DATA
if (isset($_POST["submit"]))
{
    $tahun_ajaran = mysqli_real_escape_string($kon, isset($_POST["tahun_ajaran"]) ? $_POST["tahun_ajaran"] : "");
    $nominal = isset($_POST["nominal"]) ? $_POST["nominal"] : "";

    // --- PERUBAHAN: VALIDASI 4 DIGIT ---
    if (strlen($tahun_ajaran) > 4) {
        echo '<script>alert("MAAF, TAHUN AJARAN HARUS 4 DIGIT!"); window.location = "";</script>';
    }
    // CEK APAKAH DATA MASIH KOSONG
    elseif (empty($tahun_ajaran) or empty($nominal)){
        echo '
            <script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>
        ';
    } else {

        // CEK & VALIDASI DATA
        $kue = mysqli_query($kon, "SELECT * FROM spp WHERE tahun_ajaran = '" . $tahun_ajaran . "'");
        $cek = mysqli_fetch_array($kue);

        if ($cek > 0){
            echo '
                <script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI YANG LAIN !"); window.location = "";</script>
            ';
        } else {
            mysqli_query($kon, "INSERT INTO spp (tahun_ajaran, nominal) VALUES ('$tahun_ajaran', '$nominal')");
            echo '
                <script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>
            ';
        }
    }
}

// PROSEDUR UPDATE DATA
if (isset($_POST["update"]))
{
    $id_spp = mysqli_real_escape_string($kon, isset($_POST["id_spp"]) ? $_POST["id_spp"] : "");
    $tahun_ajaran = mysqli_real_escape_string($kon, isset($_POST["tahun_ajaran"]) ? $_POST["tahun_ajaran"] : "");
    $nominal = isset($_POST["nominal"]) ? $_POST["nominal"] : "";

    // --- PERUBAHAN: VALIDASI 4 DIGIT ---
    if (strlen($tahun_ajaran) > 4) {
        echo '<script>alert("MAAF, TAHUN AJARAN HARUS 4 DIGIT!"); window.location = "";</script>';
    }
    // CEK APAKAH DATA MASIH KOSONG
    elseif (empty($tahun_ajaran) or empty($nominal)){
        echo '
            <script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>
        ';
    } else {

            mysqli_query($kon, "UPDATE spp SET nominal = '$nominal', tahun_ajaran = '$tahun_ajaran' WHERE id_spp = '" . $id_spp . "'");
            echo '
                <script>alert("DATA BERHASIL DI-UPDATE !"); window.location = "";</script>
            ';
    }

}

// PROSEDUR HAPUS DATA
if (isset($_POST["delete"]))
{
    $id_spp = mysqli_real_escape_string($kon, isset($_POST["id_spp"]) ? $_POST["id_spp"] : "");

    // Cek dulu apakah SPP ini masih digunakan oleh siswa
    $check_siswa = mysqli_query($kon, "SELECT COUNT(*) as total FROM siswa WHERE id_spp = '$id_spp'");
    $result = mysqli_fetch_assoc($check_siswa);

    if ($result['total'] > 0) {
        // Jika masih ada siswa yang menggunakan, jangan hapus
        echo '<script>alert("DATA GAGAL DIHAPUS! SPP ini masih digunakan oleh ' . $result['total'] . ' siswa."); window.location = "";</script>';
    } else {
        // Jika tidak ada siswa yang menggunakan, baru hapus
        mysqli_query($kon, "DELETE FROM spp WHERE id_spp = '" . $id_spp . "'");
        
        // PERINGATAN: Bagian auto-increment di bawah ini bisa berbahaya jika relasi tabel tidak di-set dengan benar.
        // Saya biarkan sesuai kode asli, tetapi harap berhati-hati.
        $sql = mysqli_query($kon, "SELECT * FROM spp ORDER BY id_spp");
        $no = 1;

        while ($rows = mysqli_fetch_array($sql))
        {
            $id_sppnye = $rows["id_spp"];
            mysqli_query($kon, "UPDATE spp SET id_spp = $no WHERE id_spp = '" . $id_sppnye . "'");
            $no++;
        }

        mysqli_query($kon, "ALTER TABLE spp AUTO_INCREMENT = $no");
        
        echo '<script>alert("DATA BERHASIL DIHAPUS !"); window.location = "";</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>DATA SPP</title>
        <meta name="robots" content="noindex, nofollow" />
        <meta content="" name="description" />
        <meta content="" name="keywords" />

       <?php 
        include 'aset.php'
       ?>

    </head>
    <body>
        
        <?php require "atas.php"; ?>

        <?php require "menu.php"; ?>

        <main id="main" class="main">
            <div class="pagetitle">
                <h1><i class="bi bi-wallet2"></i>&nbsp; SPP</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">SPP</li>
                    </ol>
                </nav>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <br>
                                
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH SPP</button>

                                <br><br>
                                
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th><center>TAHUN AJARAN</center></th>
                                            <th><center>NOMINAL</center></th>
                                            <th><center>AKSI</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = mysqli_query($kon, "SELECT * FROM spp ORDER BY id_spp DESC");

                                    while ($gb = mysqli_fetch_array($sql))
                                    {
                                    ?>

                                    <tr>
                                        <td><center><?= $gb["tahun_ajaran"] ?></center></td>
                                        <td><center>Rp<?= number_format($gb["nominal"], 0, "", ".") ?>,-</center></td>
                                        <td>
                                            <center>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editData<?= $gb["id_spp"] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                                &nbsp;
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["id_spp"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                            </center>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editData<?= $gb["id_spp"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i>&nbsp; EDIT SPP</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <form method="post">
                                                        
                                                        <div class="form-group">
                                                            <label>TAHUN AJARAN</label>
                                                            <input name="tahun_ajaran" class="form-control" type="number" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" placeholder="Masukkan 4 digit Tahun Ajaran" value="<?= $gb["tahun_ajaran"] ?>" required>
                                                        </div>

                                                        <br>

                                                        <div class="form-group">
                                                            <label>NOMINAL</label>
                                                            <input name="nominal" class="form-control" type="number" placeholder="Masukkan nominal SPP (Contoh: 500000)" value="<?= $gb["nominal"] ?>" required>
                                                        </div>

                                                        <br>

                                                        <input type="hidden" name="id_spp" value="<?= $gb["id_spp"] ?>">
                                                    
                                                </div>

                                                <div class="modal-footer">
                                                    <button name="update" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="hapusData<?= $gb["id_spp"] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS SPP</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <h4 class="text-center">
                                                        Apakah Anda yakin ingin menghapus SPP<br>Tahun Ajaran <strong><?= $gb['tahun_ajaran'] ?></strong>?
                                                    </h4>

                                                    <form method="post">
                                                        <input type="hidden" name="id_spp" value="<?= $gb["id_spp"] ?>">
                                                </div>

                                                <div class="modal-footer">
                                                    <button name="delete" type="submit" class="btn btn-danger"><i class="bi bi-exclamation-triangle-fill"></i>&nbsp; HAPUS</button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <?php } ?>
                                    </tbody>
                                </table>

                                <div class="modal fade" id="tambahData" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH SPP</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="post">
                                                    <div class="form-group">
                                                        <label>TAHUN AJARAN</label>
                                                        <input name="tahun_ajaran" class="form-control" type="number" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" placeholder="Masukkan 4 digit tahun ajaran (Contoh: 2024)" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NOMINAL</label>
                                                        <input name="nominal" class="form-control" type="number" placeholder="Masukkan nominal SPP (Contoh: 500000)" required>
                                                    </div>
                                                
                                            </div>

                                            <div class="modal-footer">
                                                <button name="submit" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

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
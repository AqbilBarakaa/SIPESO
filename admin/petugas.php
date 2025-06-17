<?php
session_start();
require "../db.php";
$page = "petugas";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

// Fungsi untuk mengunggah foto
function uploadFoto($file) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Periksa apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo '<script>alert("File bukan gambar."); window.location = "";</script>';
        $uploadOk = 0;
    }

    // Periksa ukuran file
    if ($file["size"] > 500000) {
        echo '<script>alert("Maaf, ukuran file terlalu besar."); window.location = "";</script>';
        $uploadOk = 0;
    }

    // Batasi jenis file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo '<script>alert("Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan."); window.location = "";</script>';
        $uploadOk = 0;
    }

    // Periksa apakah uploadOk adalah 0
    if ($uploadOk == 0) {
        echo '<script>alert("Maaf, file Anda tidak dapat diunggah."); window.location = "";</script>';
        return false;
    // Jika semua pengecekan lolos, unggah file
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($file["name"]);
        } else {
            echo '<script>alert("Maaf, terjadi kesalahan saat mengunggah file Anda."); window.location = "";</script>';
            return false;
        }
    }
}

// PROSEDUR SIMPAN DATA
if (isset($_POST["submit"])) {
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $level = $_POST["level"];
    $foto = uploadFoto($_FILES["foto"]);

    if (empty($user) || empty($pwd) || empty($nama) || empty($level) || !$foto) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {
        $kue = mysqli_query($kon, "SELECT * FROM petugas WHERE username = '$user'");
        if (mysqli_num_rows($kue) > 0) {
            echo '<script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI YANG LAIN !"); window.location = "";</script>';
        } else {
            mysqli_query($kon, "INSERT INTO petugas (username, password, nama_petugas, level, foto) VALUES ('$user', '$pwd', '$nama', '$level', '$foto')");
            echo '<script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>';
        }
    }
}

// PROSEDUR UPDATE DATA
if (isset($_POST["update"])) {
    $id_petugas = mysqli_real_escape_string($kon, $_POST["id_petugas"]);
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $level = $_POST["level"];
    $foto = !empty($_FILES["foto"]["name"]) ? uploadFoto($_FILES["foto"]) : $_POST["foto_lama"];

    if (empty($user) || empty($pwd) || empty($nama) || empty($level) || !$foto) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {
        mysqli_query($kon, "UPDATE petugas SET username = '$user', password = '$pwd', nama_petugas = '$nama', level = '$level', foto = '$foto' WHERE id_petugas = '$id_petugas'");
        echo '<script>alert("DATA BERHASIL DI-UPDATE !"); window.location = "";</script>';
    }
}

// PROSEDUR HAPUS DATA
if (isset($_POST["delete"])) {
    $id_petugas = mysqli_real_escape_string($kon, $_POST["id_petugas"]);
    mysqli_query($kon, "DELETE FROM petugas WHERE id_petugas = '$id_petugas'");
    echo '<script>alert("DATA BERHASIL DIHAPUS !"); window.location = "";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>DATA PETUGAS</title>
        <meta name="robots" content="noindex, nofollow" />
        <meta content="" name="description" />
        <meta content="" name="keywords" />
        
        <?php include 'aset.php' ?>

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
                <h1><i class="bi bi-person-badge"></i>&nbsp; PETUGAS</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">PETUGAS</li>
                    </ol>
                </nav>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            
                            <br>
                                
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH PETUGAS</button>

                                <br><br>
                                
                                <table class="table datatable">

                                    <tr>
                                        <th><center>USERNAME</center></th>
                                        <th><center>PASSWORD</center></th>
                                        <th><center>NAMA PETUGAS</center></th>
                                        <th><center>LEVEL</center></th>
                                        <th><center>FOTO</center></th>
                                        <th><center>AKSI</center></th>
                                    </tr>
                                    
                                    <?php
                                    $sql = mysqli_query($kon, "SELECT * FROM petugas ORDER BY id_petugas DESC");

                                    while ($gb = mysqli_fetch_array($sql))
                                    {
                                    ?>

                                    <tr class="text-center">
                                        <td><?= $gb["username"] ?></td>
                                        <td><?= $gb["password"] ?></td>
                                        <td><?= $gb["nama_petugas"] ?></td>
                                        <td><?= strtoupper($gb["level"]) ?></td>
                                        <td><img src="../uploads/<?= $gb["foto"] ?>" alt="<?= $gb["nama_petugas"] ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editData<?= $gb["id_petugas"] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                            &nbsp;
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["id_petugas"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>

                                    <!-- EDIT DATA -->
                                    <div class="modal fade" id="editData<?= $gb["id_petugas"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i>&nbsp; EDIT PETUGAS</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label>USERNAME</label>
                                                            <input name="user" class="form-control" type="text" placeholder="Masukkan username" value="<?= $gb["username"] ?>" required>
                                                        </div>

                                                        <br>
                                                        
                                                        <div class="form-group">
                                                            <label>PASSWORD</label>
                                                            <input name="pwd" class="form-control" type="password" placeholder="Masukkan password" value="<?= $gb["password"] ?>" required>
                                                        </div>

                                                        <br>

                                                        <div class="form-group">
                                                            <label>NAMA PETUGAS</label>
                                                            <input name="nama" class="form-control" type="text" placeholder="Masukkan nama petugas" value="<?= $gb["nama_petugas"] ?>" required>
                                                        </div>

                                                        <br>

                                                        <div class="form-group">
                                                            <label>LEVEL</label>
                                                            <select class="form-select" name="level" required>
                                                                <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                                <?php
                                                                $level = array(
                                                                    "admin",
                                                                    "petugas"
                                                                );

                                                                foreach ($level as $lvl) :
                                                                ?>
                                                                <option <?php if($gb["level"] == $lvl){ echo "selected"; } ?> value="<?= strtoupper($lvl) ?>"><?= strtoupper($lvl) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <br>

                                                        <div class="form-group">
                                                            <label>FOTO</label>
                                                            <input name="foto" class="form-control" type="file" placeholder="Pilih foto">
                                                            <input type="hidden" name="foto_lama" value="<?= $gb["foto"] ?>">
                                                        </div>

                                                        <input type="hidden" name="id_petugas" value="<?= $gb["id_petugas"] ?>">
                                                    
                                                </div>

                                                <div class="modal-footer">
                                                    <button name="update" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- HAPUS DATA -->
                                    <div class="modal fade" id="hapusData<?= $gb["id_petugas"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS PETUGAS</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <h2 class="text-center">
                                                        Apakah Anda yakin ingin menghapus data ini ?
                                                        <br>
                                                        <?= $gb["nama_petugas"] ?>
                                                    </h2>

                                                    <form method="post">
                                                        <input type="hidden" name="id_petugas" value="<?= $gb["id_petugas"] ?>">
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

                                <!-- TAMBAH DATA -->
                                <div class="modal fade" id="tambahData" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH PETUGAS</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>USERNAME</label>
                                                        <input name="user" class="form-control" type="text" placeholder="Masukkan username" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>PASSWORD</label>
                                                        <input name="pwd" class="form-control" type="password" placeholder="Masukkan password" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NAMA PETUGAS</label>
                                                        <input name="nama" class="form-control" type="text" placeholder="Masukkan nama petugas" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>LEVEL</label>
                                                        <select class="form-select" name="level" required>
                                                            <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                            <?php
                                                            $level = array(
                                                                "ADMIN",
                                                                "PETUGAS"
                                                            );

                                                            foreach ($level as $lvl) :
                                                            ?>
                                                            <option value="<?= strtoupper($lvl) ?>"><?= strtoupper($lvl) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>FOTO</label>
                                                        <input name="foto" class="form-control" type="file" placeholder="Pilih foto" required>
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

    </body>
</html>

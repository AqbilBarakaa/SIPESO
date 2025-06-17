<?php
session_start();
require "../db.php";
$page = "siswa";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
}

$target_dir = "../uploads/";

// Prosedur Hapus Data
if (isset($_POST["delete"])) {
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    mysqli_query($kon, "DELETE FROM siswa WHERE nis = '$nis'");
    echo '<script>alert("DATA BERHASIL DIHAPUS !"); window.location = "";</script>';
}

// Prosedur Tambah Data
if (isset($_POST["submit"])) {
    $nisn = mysqli_real_escape_string($kon, $_POST["nisn"]);
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $nama_ortu = mysqli_real_escape_string($kon, $_POST["nama_ortu"]);
    $id_kelas = $_POST["id_kelas"];
    $alamat = mysqli_real_escape_string($kon, $_POST["alamat"]);
    $no_telp = mysqli_real_escape_string($kon, $_POST["no_telp"]);
    
    // Handling file upload
    $foto = "";
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        $foto = "../uploads/" . $foto; // Simpan path relatif ke foto
    }

    // =======================================================================
    // == BAGIAN VALIDASI DATA SEBELUM DISIMPAN ==
    // =======================================================================
    
    // 1. Validasi NIS terhadap Tabel SPP
    $tahun_spp = substr($nis, 0, 4);
    
    // ==============================================================================================
    // == PERBAIKAN FATAL ERROR ADA DI BARIS DI BAWAH INI ==
    // Saya mengubah `tahun` menjadi `tahun_ajaran`. Sesuaikan jika nama kolom di database Anda berbeda.
    // ==============================================================================================
    $spp_check_query = mysqli_query($kon, "SELECT * FROM spp WHERE tahun_ajaran = '$tahun_spp'");
    $spp_data = mysqli_fetch_array($spp_check_query);

    // 2. Rangkaian Pengecekan
    if (empty($nisn) || empty($nis) || empty($user) || empty($pwd) || empty($nama) || empty($id_kelas) || empty($alamat) || empty($no_telp) || empty($foto) || empty($nama_ortu)) {
        echo '<script>alert("MAAF, SEMUA DATA WAJIB DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } elseif (strlen($nisn) > 10 || strlen($nis) > 10) {
        echo '<script>alert("MAAF, NISN DAN NIS HARUS MAKSIMAL 10 ANGKA!"); window.location = "";</script>';
    } elseif (strlen($no_telp) > 12) {
        echo '<script>alert("MAAF, NO. TELP HARUS MAKSIMAL 12 ANGKA!"); window.location = "";</script>';
    } elseif (!$spp_data) { // Jika tahun dari NIS tidak terdaftar di SPP, data tidak akan diinput
        echo '<script>alert("Tahun ajaran dari NIS tidak terdaftar. Data tidak dapat diinput!"); window.location = "";</script>';
    } else {
        // Jika semua validasi lolos, lanjutkan proses
        $id_spp = $spp_data['id_spp'];
        $kue = mysqli_query($kon, "SELECT * FROM siswa WHERE username = '$user' AND password = '$pwd'");
        $cek = mysqli_fetch_array($kue);

        if ($cek > 0) {
            echo '<script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI YANG LAIN !"); window.location = "";</script>';
        } else {
            mysqli_query($kon, "INSERT INTO siswa (nisn, nis, username, password, nama, nama_ortu, id_kelas, alamat, no_telp, id_spp, foto) VALUES ('$nisn', '$nis', '$user', '$pwd', '$nama', '$nama_ortu', '$id_kelas', '$alamat', '$no_telp', '$id_spp', '$foto')");
            echo '<script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>';
        }
    }
}


// Prosedur Update Data
if (isset($_POST["update"])) {
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $nama_ortu = mysqli_real_escape_string($kon, $_POST["nama_ortu"]);
    $id_kelas = $_POST["id_kelas"];
    $alamat = mysqli_real_escape_string($kon, $_POST["alamat"]);
    $no_telp = mysqli_real_escape_string($kon, $_POST["no_telp"]);
    $id_spp = $_POST["id_spp"];
    
    // Handling file upload
    $foto = "";
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0 && !empty($_FILES["foto"]["name"])) {
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        $foto = "../uploads/" . $foto; // Simpan path relatif ke foto
    } else {
        // Jika tidak ada file foto yang diupload, gunakan foto yang ada sebelumnya
        $foto = mysqli_real_escape_string($kon, $_POST["foto_lama"]);
    }

    if (strlen($no_telp) > 12) {
        echo '<script>alert("MAAF, NO. TELP HARUS MAKSIMAL 12 ANGKA!"); window.location = "";</script>';
    } elseif (empty($user) || empty($pwd) || empty($nama) || empty($id_kelas) || empty($alamat) || empty($no_telp) || empty($nama_ortu)) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {
        $update_query = "UPDATE siswa SET username = '$user', password = '$pwd', nama = '$nama', nama_ortu = '$nama_ortu', id_kelas = '$id_kelas', alamat = '$alamat', no_telp = '$no_telp', id_spp = '$id_spp', foto = '$foto' WHERE nis = '$nis'";
        
        mysqli_query($kon, $update_query);
        echo '<script>alert("DATA BERHASIL DI-UPDATE !"); window.location = "";</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>DATA SISWA</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <?php include 'aset.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
</head>
<body>
    
    <?php require "atas.php"; ?>

    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-person"></i>&nbsp; SISWA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">SISWA</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <br>
                            
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH SISWA</button>

                            <br><br>
                            
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th><center>NISN</center></th>
                                        <th><center>NIS</center></th>
                                        <th><center>USERNAME</center></th>
                                        <th><center>NAMA LENGKAP</center></th>
                                        <th><center>ORANG TUA</center></th>
                                        <th><center>KELAS</center></th>
                                        <th><center>ALAMAT</center></th>
                                        <th><center>NO. TELP</center></th>
                                        <th><center>NOMINAL SPP</center></th>
                                        <th><center>AKSI</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = mysqli_query($kon, "SELECT * FROM siswa ORDER BY nis DESC");

                                while ($gb = mysqli_fetch_array($sql)) {
                                    $spp = mysqli_query($kon, "SELECT * FROM spp WHERE id_spp = " . $gb["id_spp"]);
                                    $sppnye = mysqli_fetch_array($spp);
                                    $nominal_spp = ($sppnye && isset($sppnye["nominal"])) ? $sppnye["nominal"] : 0;
                                ?>
                                <tr>
                                    <td><center><?= $gb["nisn"] ?></center></td>
                                    <td><center><?= $gb["nis"] ?></center></td>
                                    <td><center><?= $gb["username"] ?></center></td>
                                    <td><center><?= $gb["nama"] ?></center></td>
                                    <td><center><?= $gb["nama_ortu"] ?></center></td>
                                    <td>
                                        <center>
                                            <?php 
                                                $kelas = mysqli_query($kon, "SELECT * FROM kelas WHERE id_kelas = ". $gb["id_kelas"]);
                                                $kelasnya = mysqli_fetch_array($kelas);
                                                echo $kelasnya["nama_kelas"];
                                            ?>
                                        </center>
                                    </td>
                                    <td><center><?= $gb["alamat"] ?></center></td>
                                    <td><center><?= $gb["no_telp"] ?></center></td>
                                    <td>
                                        <center>
                                            Rp<?= number_format($nominal_spp, 0, "", ".") ?>,-
                                        </center>
                                    </td>
                                    <td><center>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editData<?= $gb["nis"] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["nis"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                    </center></td>
                                </tr>

                                <div class="modal fade" id="editData<?= $gb["nis"] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square"></i>&nbsp; EDIT SISWA</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>NISN</label>
                                                        <input name="nisn" class="form-control" type="text" placeholder="Masukkan NISN sekolah" value="<?= $gb["nisn"] ?>" disabled>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NIS</label>
                                                        <input name="nis2" class="form-control" type="text" placeholder="Masukkan NIS sekolah" value="<?= $gb["nis"] ?>" disabled>
                                                    </div>

                                                    <br>

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
                                                        <label>NAMA SISWA</label>
                                                        <input name="nama" class="form-control" type="text" placeholder="Masukkan nama siswa" value="<?= $gb["nama"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NAMA ORANG TUA</label>
                                                        <input name="nama_ortu" class="form-control" type="text" placeholder="Masukkan nama orang tua siswa" value="<?= $gb["nama_ortu"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>KELAS</label>
                                                        <select class="form-select" name="id_kelas" required>
                                                            <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                            <?php
                                                            $kelas_all = mysqli_query($kon, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

                                                            while ($kls = mysqli_fetch_array($kelas_all)) {
                                                            ?>
                                                            <option 
                                                                <?php if($kls["id_kelas"] == $gb["id_kelas"]) { 
                                                                    echo "selected"; } 
                                                                ?> 
                                                                value="<?= $kls["id_kelas"] ?>"><?= $kls["nama_kelas"] ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>ALAMAT</label>
                                                        <textarea name="alamat" class="form-control" rows="7" placeholder="Masukkan alamat siswa" required><?= $gb["alamat"] ?></textarea>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NO. TELP</label>
                                                        <input name="no_telp" class="form-control" type="text" pattern="\d*" maxlength="12" placeholder="Masukkan nomor telepon siswa" value="<?= $gb["no_telp"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>FOTO (Kosongkan jika tidak diubah)</label>
                                                        <input name="foto" class="form-control" type="file">
                                                        <input type="hidden" name="foto_lama" value="<?= $gb["foto"] ?>">
                                                        <p><img src="<?= $gb["foto"] ?>" width="100"></p>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NOMINAL SPP</label>
                                                        <input type="text" name="nominal_spp_display" class="form-control" value="Rp<?= number_format($nominal_spp, 0, "", ".") ?>,-" disabled>
                                                        <input type="hidden" name="id_spp" value="<?= $gb["id_spp"] ?>">
                                                    </div>

                                                    <input type="hidden" name="nis" value="<?= $gb["nis"] ?>">
                                                
                                            </div>

                                            <div class="modal-footer">
                                                <button name="update" type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i>&nbsp; UPDATE</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="hapusData<?= $gb["nis"] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS SISWA</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <h4 class="text-center">
                                                    Apakah Anda yakin ingin menghapus data<br>
                                                    <strong><?= htmlspecialchars($gb["nama"]) ?></strong>?
                                                </h4>

                                                <form method="post">
                                                    <input type="hidden" name="nis" value="<?= $gb["nis"] ?>">
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
                                            <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH SISWA</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label>NISN</label>
                                                    <input name="nisn" class="form-control" type="text" pattern="\d*" maxlength="10" placeholder="Masukkan 10 digit NISN" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NIS</label>
                                                    <input name="nis" id="nis" class="form-control" type="text" pattern="\d*" maxlength="10" placeholder="4 digit pertama adalah tahun ajaran (Contoh: 2025...)" required>
                                                </div>

                                                <br>

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
                                                    <label>NAMA LENGKAP</label>
                                                    <input name="nama" class="form-control" type="text" placeholder="Masukkan nama lengkap siswa" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NAMA ORANG TUA</label>
                                                    <input name="nama_ortu" class="form-control" type="text" placeholder="Masukkan nama lengkap orang tua siswa" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>KELAS</label>
                                                    <select class="form-select" name="id_kelas" required>
                                                        <option selected disabled value="">--- SILAHKAN PILIH ---</option>
                                                        <?php
                                                        $kelas = mysqli_query($kon, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

                                                        while ($kls = mysqli_fetch_array($kelas)) {
                                                        ?>
                                                        <option value="<?= $kls["id_kelas"] ?>"><?= $kls["nama_kelas"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>ALAMAT</label>
                                                    <textarea name="alamat" class="form-control" rows="7" placeholder="Masukkan alamat siswa" required></textarea>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NO. TELP</label>
                                                    <input name="no_telp" class="form-control" type="text" pattern="\d*" maxlength="12" placeholder="Masukkan nomor telepon siswa (maksimal 12 digit)" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>FOTO</label>
                                                    <input name="foto" class="form-control" type="file" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NOMINAL SPP (Otomatis berdasarkan NIS)</label>
                                                    <span id="nominal_spp"></span>
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

    <script>
        $(document).ready(function(){
            // Fungsi untuk mengambil nominal SPP berdasarkan 4 digit pertama NIS
            $("#nis").on('keyup', function(){
                var nis_prefix = $(this).val().substring(0, 4);

                if (nis_prefix.length === 4){
                    $.ajax({
                        url: "nominal_spp.php", // Pastikan file ini ada di direktori yang sama
                        method: "POST",
                        data: {sppnya: nis_prefix},
                        success: function(data){
                            $("#nominal_spp").html(data);
                        }
                    });
                } else {
                    // Reset jika NIS kurang dari 4 digit
                    $("#nominal_spp").html('<input type="text" class="form-control" value="Input 4 digit NIS untuk melihat nominal" disabled>');
                }
            });

            // NOMINAL SPP DEFAULT saat modal pertama kali dibuka
            $('#tambahData').on('shown.bs.modal', function () {
                $("#nominal_spp").html('<input type="text" class="form-control" value="Input 4 digit NIS untuk melihat nominal" disabled>');
                // Kosongkan form saat modal dibuka kembali
                $(this).find('form')[0].reset();
            });
        });
    </script>

</body>
</html>
<?php
session_start();
require "../db.php";
$page = "kelas";

if (!isset($_SESSION["admin"]))
{
	header("Location: ../login.php");
}

// PROSEDUR SIMPAN DATA
if (isset($_POST["submit"]))
{
    $kelas = mysqli_real_escape_string($kon, isset($_POST["kelas"]) ? $_POST["kelas"] : "");
    $jurusan = isset($_POST["jurusan"]) ? $_POST["jurusan"] : "";

    // CEK APAKAH DATA MASIH KOSONG
    if (empty($kelas) or empty($jurusan)){
        echo '
            <script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>
        ';
    } else {

        // CEK & VALIDASI DATA
        $kue = mysqli_query($kon, "SELECT * FROM kelas WHERE nama_kelas = '" . $kelas . "'");
        $cek = mysqli_fetch_array($kue);

        if ($cek > 0){
            echo '
                <script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI YANG LAIN !"); window.location = "";</script>
            ';
        } else {

            mysqli_query($kon, "INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES ('$kelas', '$jurusan')");
            echo '
                <script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>
            ';

        }

    }
}


// PROSEDUR UPDATE DATA
if (isset($_POST["update"]))
{
    $id_kelas = mysqli_real_escape_string($kon, isset($_POST["id_kelas"]) ? $_POST["id_kelas"] : "");
    $kelas = mysqli_real_escape_string($kon, isset($_POST["kelas"]) ? $_POST["kelas"] : "");
    $jurusan = isset($_POST["jurusan"]) ? $_POST["jurusan"] : "";

    // CEK APAKAH DATA MASIH KOSONG
    if (empty($kelas) or empty($jurusan)){
        echo '
            <script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>
        ';
    } else {

        // mysqli_query($kon, "INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES ('$kelas', '$jurusan')");
        
        mysqli_query($kon, "UPDATE kelas SET nama_kelas = '$kelas', kompetensi_keahlian = '$jurusan' WHERE id_kelas = '" . $id_kelas . "'");
        echo '
            <script>alert("DATA BERHASIL DI-UPDATE !"); window.location = "";</script>
        ';

    }
}


// PROSEDUR HAPUS DATA
if (isset($_POST["delete"]))
{
    $id_kelas = mysqli_real_escape_string($kon, isset($_POST["id_kelas"]) ? $_POST["id_kelas"] : "");

    mysqli_query($kon, "DELETE FROM kelas WHERE id_kelas = '" . $id_kelas . "'");
    
    // SET AUTO_INCREMENT JADI BERURUTAN
    $sql = mysqli_query($kon, "SELECT * FROM kelas ORDER BY id_kelas");
    $no = 1;

    while ($rows = mysqli_fetch_array($sql))
    {
        $id_kelasnye = $rows["id_kelas"];
        mysqli_query($kon, "UPDATE kelas SET id_kelas = $no WHERE id_kelas = '" . $id_kelasnye . "'");
        $no++;
    }

    mysqli_query($kon, "ALTER TABLE kelas AUTO_INCREMENT = $no");
    
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
        <title>DATA KELAS</title>
        <meta name="robots" content="noindex, nofollow" />
        <meta content="" name="description" />
        <meta content="" name="keywords" />
     <?php 
        include 'aset.php'
     ?>

    </head>
    <body>
        
    	<!-- HEADER!!! -->
    	<?php require "atas.php"; ?>

    	<!-- SIDEBAR!!! -->
        <?php require "menu.php"; ?>

        <main id="main" class="main">
            <div class="pagetitle">
            <h1><i class="bi bi-pass"></i>&nbsp; KELAS</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">KELAS</li>
                    </ol>
                </nav>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            	
                            	<br>
                                
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH KELAS</button>

                                <br><br>
                                
                                <table class="table datatable">

                                    <tr>
	                                    <th><center>NAMA KELAS</center></th>
	                                    <th><center>KOMPETENSI KEAHLIAN</center></th>
	                                    <th><center>AKSI</center></th>
                                    </tr>
                                    
                                    <?php
                                    $sql = mysqli_query($kon, "SELECT * FROM kelas ORDER BY id_kelas DESC");

                                    while ($gb = mysqli_fetch_array($sql))
                                    {
                                    ?>

                                    <tr>
                                        <td><center><?= $gb["nama_kelas"] ?></center></td>
                                        <td><center><?= $gb["kompetensi_keahlian"] ?></center></td>
                                        <td>
                                            <center>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editData<?= $gb["id_kelas"] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                            &nbsp;
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["id_kelas"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                            </center>
                                        </td>
                                    </tr>

                                    <!-- EDIT DATA -->
                                    <div class="modal fade" id="editData<?= $gb["id_kelas"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i>&nbsp; EDIT DATA</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <form method="post">
                                                        <div class="form-group">
                                                            <label>NAMA KELAS</label>
                                                            <input name="kelas" class="form-control" type="text" placeholder="Masukkan nama kelas" value="<?= $gb["nama_kelas"] ?>" required>
                                                        </div>

                                                        <br>

                                                        <div class="form-group">
                                                            <label>KOMPETENSI KEAHLIAN</label>
                                                            <select class="form-select" name="jurusan" required>
                                                                <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                                <?php
                                                                $jurusan = array(
                                                                    "MIPA",
                                                                    "IPS"
                                                                );

                                                                foreach ($jurusan as $jrs) :
                                                                ?>
                                                                <option 
                                                                    <?php if($gb["kompetensi_keahlian"] == $jrs){ 
                                                                        echo "selected"; 
                                                                        } 
                                                                    ?> 
                                                                    value="<?= $jrs ?>"><?= $jrs ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="id_kelas" value="<?= $gb["id_kelas"] ?>">
                                                    
                                                </div>

                                                <div class="modal-footer">
                                                    <button name="update" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- HAPUS DATA -->
                                    <div class="modal fade" id="hapusData<?= $gb["id_kelas"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS KELAS</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                    <h2 class="text-center">
                                                        Apakah Anda yakin ingin menghapus data ini ?
                                                        <br>
                                                        <?= $gb["nama_kelas"] ?>
                                                    </h2>

                                                    <form method="post">
                                                        <input type="hidden" name="id_kelas" value="<?= $gb["id_kelas"] ?>">
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
                                                <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH KELAS</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="post">
                                                    <div class="form-group">
                                                        <label>NAMA KELAS</label>
                                                        <input name="kelas" class="form-control" type="text" placeholder="Masukkan nama kelas" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>KOMPETENSI KEAHLIAN</label>
                                                        <select class="form-select" name="jurusan" required>
                                                            <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                            <?php
                                                            $jurusan = array(
                                                                "MIPA",
                                                                "IPS"
                                                            );

                                                            foreach ($jurusan as $jrs) :
                                                            ?>
                                                            <option value="<?= $jrs ?>"><?= $jrs ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
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
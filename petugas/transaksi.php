<?php
session_start();
require "../db.php";
$page = "transaksi";
if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $id_petugas = $_POST['id_petugas'];
    $nisn = $_POST['nisn'];
    $tgl_bayar = $_POST['tgl_bayar']; // Format: d-m-Y
    $bulan_dibayar = date('m', strtotime($tgl_bayar));
    $tahun_dibayar = date('Y', strtotime($tgl_bayar));
    $id_spp = $_POST['id_spp'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $payment_type = $_POST['payment_type'];
    $angsuran = isset($_POST['angsuran']) && !empty($_POST['angsuran']) ? (int)$_POST['angsuran'] : null;
    $promo_code = !empty($_POST['promo_code']) ? mysqli_real_escape_string($kon, $_POST['promo_code']) : null;

    $semester_start_month = ((int)$bulan_dibayar >= 7 && (int)$bulan_dibayar <= 12) ? 7 : 1;
    $semester_end_month = ((int)$bulan_dibayar >= 7 && (int)$bulan_dibayar <= 12) ? 12 : 6;

    // === VALIDASI TUMPANG TINDIH & ANGSURAN ===
    // Cek jika siswa sedang berjalan angsuran di semester ini
    $q_check_any = mysqli_query($kon, "
      SELECT payment_type, angsuran, bulan_dibayar 
      FROM pembayaran 
      WHERE nisn='$nisn'
        AND tahun_dibayar='$tahun_dibayar'
        AND bulan_dibayar BETWEEN '$semester_start_month' AND '$semester_end_month'
        AND angsuran IS NOT NULL
        LIMIT 1
    ");
    if (mysqli_num_rows($q_check_any) > 0) {
        $row_any = mysqli_fetch_assoc($q_check_any);
        // Jika siswa sudah angsuran, maka metode mana pun harus sesuai dan tidak boleh semester
        if ($row_any['payment_type'] === 'bulanan') {
            if ($payment_type !== 'bulanan' || ($angsuran !== null && $angsuran != $row_any['angsuran'])) {
                echo '<script>
                    alert("Siswa sudah memulai angsuran ' . $row_any['angsuran'] . 'x. Tidak boleh ganti metode pembayaran atau jenis angsuran!");
                    window.location="transaksi.php";
                </script>';
                exit;
            }
        }
    }

    if ($payment_type == 'semester') {
        $q_check_monthly = mysqli_query($kon, "
          SELECT id_pembayaran 
          FROM pembayaran 
          WHERE nisn='$nisn'
            AND tahun_dibayar='$tahun_dibayar'
            AND bulan_dibayar BETWEEN '$semester_start_month' AND '$semester_end_month'
            AND payment_type='bulanan' LIMIT 1
        ");
        if (mysqli_num_rows($q_check_monthly) > 0) {
            echo '<script>alert("Pembayaran bulanan sudah dimulai di semester ini. Pembayaran via semester tidak diizinkan."); window.location = "transaksi.php";</script>';
            exit;
        }

        if ($promo_code) {
            $promo_query = mysqli_query($kon, "SELECT * FROM code_beasiswa WHERE code='$promo_code' AND used=FALSE LIMIT 1");
            if (mysqli_num_rows($promo_query) > 0) {
                $promo = mysqli_fetch_assoc($promo_query);
                $discount_amount = $promo['discount_amount'];
                $spp_query = mysqli_query($kon, "
                  SELECT spp.nominal 
                  FROM siswa JOIN spp ON siswa.id_spp = spp.id_spp 
                  WHERE siswa.nisn='$nisn'
                ");
                if(mysqli_num_rows($spp_query) > 0) {
                    $spp = mysqli_fetch_assoc($spp_query);
                    $jumlah_bayar = ($spp['nominal'] * 6) - $discount_amount;
                } else {
                    echo '<script>alert("Data SPP siswa tidak ditemukan.");window.location="transaksi.php";</script>';
                    exit;
                }
            } else {
                echo '<script>alert("KODE BEASISWA TIDAK VALID ATAU SUDAH DIGUNAKAN!"); window.location = "transaksi.php";</script>';
                exit;
            }
        }

    } elseif ($payment_type == 'bulanan') {
        $q_check_semester = mysqli_query($kon, "
          SELECT id_pembayaran 
          FROM pembayaran 
          WHERE nisn='$nisn'
            AND tahun_dibayar='$tahun_dibayar'
            AND bulan_dibayar BETWEEN '$semester_start_month' AND '$semester_end_month'
            AND payment_type='semester' LIMIT 1
        ");
        if (mysqli_num_rows($q_check_semester) > 0) {
            echo '<script>alert("Semester ini sudah lunas. Pembayaran bulanan tidak diperlukan."); window.location = "transaksi.php";</script>';
            exit;
        }

        $cek_angsuran = mysqli_query($kon, "
          SELECT * FROM pembayaran 
          WHERE nisn='$nisn'
            AND bulan_dibayar='$bulan_dibayar'
            AND tahun_dibayar='$tahun_dibayar'
            AND payment_type='bulanan'
        ");
        $jumlah_transaksi = 0;
        $jenis_angsuran_berjalan = "";
        while ($data = mysqli_fetch_assoc($cek_angsuran)) {
            if ($data['angsuran'] == 6 || $data['angsuran'] == 12) {
                $jumlah_transaksi++;
                $jenis_angsuran_berjalan = $data['angsuran'];
            } elseif (empty($data['angsuran'])) {
                $jenis_angsuran_berjalan = "lunas";
            }
        }
        if ($jenis_angsuran_berjalan === "lunas") {
            echo '<script>alert("Siswa sudah membayar lunas untuk bulan ini!"); window.location = "transaksi.php";</script>';
            exit;
        }
        if ($jenis_angsuran_berjalan && $angsuran != $jenis_angsuran_berjalan) {
            echo '<script>alert("Siswa sedang melakukan angsuran '.$jenis_angsuran_berjalan.'x. Tidak bisa mengubah metode!"); window.location = "transaksi.php";</script>';
            exit;
        }
        if (($angsuran == 6 && $jumlah_transaksi >= 6) || ($angsuran == 12 && $jumlah_transaksi >= 12)) {
            echo '<script>alert("Angsuran '.$angsuran.'x untuk bulan ini sudah lunas."); window.location = "transaksi.php";</script>';
            exit;
        }
    }

    // === INSERT KE TABEL PEMBAYARAN ===
    $query = "
      INSERT INTO pembayaran (
        id_petugas, nisn, tgl_bayar, bulan_dibayar, 
        tahun_dibayar, id_spp, jumlah_bayar, payment_type, angsuran
      ) VALUES (
        '$id_petugas','$nisn','$tgl_bayar','$bulan_dibayar',
        '$tahun_dibayar','$id_spp','$jumlah_bayar','$payment_type',
        ". ($angsuran !== null ? "'$angsuran'" : "NULL") ."
      )
    ";

    if (mysqli_query($kon, $query)) {
        if ($promo_code) {
            mysqli_query($kon, "UPDATE code_beasiswa SET used=TRUE WHERE code='$promo_code'");
        }
        echo '<script>alert("Pembayaran berhasil."); window.location="transaksi.php";</script>';
    } else {
        echo '<script>alert("Terjadi kesalahan saat menyimpan: '.mysqli_real_escape_string($kon, mysqli_error($kon)).'"); window.location="transaksi.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>TRANSAKSI</title>
    <meta name="robots" content="noindex, nofollow" />
    <?php include 'aset.php'; ?>
</head>
<body>
    <?php require "atas.php"; ?>
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-receipt-cutoff"></i>&nbsp; TRANSAKSI</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">TRANSAKSI</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" onsubmit="return validateForm();" class="mt-4">
                                <div class="form-group">
                                    <label>NAMA PETUGAS</label>
                                    <?php 
                                        $petugas = $_SESSION["petugas"];
                                        $kue_petugas = mysqli_query($kon, "SELECT * FROM petugas WHERE id_petugas = '$petugas'");
                                        $pts = mysqli_fetch_array($kue_petugas);
                                    ?>
                                    <input name="nama_petugas" type="text" class="form-control" value="<?= htmlspecialchars($pts['nama_petugas']) ?>" disabled>
                                    <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($pts['id_petugas']) ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>NAMA SISWA</label>
                                    <select class="form-select" name="nisn" id="nisn" required>
                                        <option value="" selected disabled>--- SILAHKAN PILIH ---</option>
                                        <?php
                                        $nisn_siswa = mysqli_query($kon, "SELECT * FROM siswa ORDER BY nama ASC");
                                        while ($nisn_data = mysqli_fetch_array($nisn_siswa)) {
                                        ?>
                                        <option value="<?= $nisn_data["nisn"] ?>"><?= htmlspecialchars($nisn_data["nama"]) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="bulan_dibayar">BULAN BAYAR</label>
                                    <input type="text" name="bulan_dibayar_display" class="form-control" value="<?= date('m') ?>" disabled>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="">TAHUN BAYAR</label>
                                    <input name="tahun_dibayar_display" class="form-control" type="number" value="<?= date('Y')?>" disabled required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="payment_type">JENIS PEMBAYARAN</label>
                                    <select class="form-select" name="payment_type" id="payment_type" required>
                                        <option value="bulanan">Bulanan</option>
                                        <option value="semester">Semester</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group" id="angsuran_field" style="display:block;">
                                    <label for="angsuran">ANGSURAN</label>
                                    <select class="form-select" name="angsuran" id="angsuran">
                                        <option value="">Tidak ingin melakukan angsuran</option>
                                        <option value="6">6x / Bulan</option>
                                        <option value="12">12x / Bulan</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group" id="promo_code_field" style="display:none;">
                                    <label for="promo_code">Kode Beasiswa</label>
                                    <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Masukkan Kode Beasiswa">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="jumlah_bayar">JUMLAH BAYAR</label>
                                    <span id="jumlah_bayar"></span>
                                </div>
                                
                                <input name="tgl_bayar" class="form-control" type="hidden" value="<?=date('d-m-Y') ?>" readonly>
                                
                                <center>
                                    <div class="mt-3">
                                        <button name="submit" type="submit" class="btn btn-success px-5"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- SCRIPTS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function validateForm() {
            var nisn = document.getElementById("nisn").value;
            if (!nisn) {
                alert("Silakan pilih siswa terlebih dahulu.");
                return false;
            }
            return true;
        }

        $(document).ready(function(){
            // --- BAGIAN PERBAIKAN ---
            function checkPaymentStatus(data) {
                // Cek apakah respons dari server berisi pesan error (dalam div.alert)
                if (data.trim().startsWith('<div class="alert')) {
                    // Jika ya, nonaktifkan tombol SAVE
                    $("button[name='submit']").prop("disabled", true);
                } else {
                    // Jika tidak, aktifkan tombol (selama siswa sudah dipilih)
                    toggleSubmitButton();
                }
            }
            // --- AKHIR PERBAIKAN ---

            function toggleSubmitButton() {
                const nisn = $("#nisn").val();
                $("button[name='submit']").prop("disabled", !nisn);
            }

            toggleSubmitButton();
            $("#nisn").on("change", function() {
                toggleSubmitButton();
                $("#payment_type").trigger('change');
            });

            $("#payment_type, #angsuran").on('change', function(){
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();
                var angsuran = $("#angsuran").val();

                if (paymentType === "bulanan") {
                    $("#angsuran_field").show();
                    $("#promo_code_field").hide();
                    $("#promo_code").val('');
                } else if (paymentType === "semester") {
                    $("#angsuran_field").hide();
                    $("#promo_code_field").show();
                    $("#angsuran").val('');
                }

                if (!nisn) return;
                
                $.post("payment_type.php", { payment_type: paymentType, nisn: nisn, angsuran: angsuran }, function(data){
                    $("#jumlah_bayar").html(data);
                    checkPaymentStatus(data); // Panggil fungsi pengecekan
                });
            });

            $("#promo_code").on('keyup', function(){
                var promoCode = $(this).val();
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();

                if (!nisn || paymentType !== "semester") return;
                
                if(!promoCode) {
                    $.post("payment_type.php", { payment_type: 'semester', nisn: nisn }, function(data){
                        $("#jumlah_bayar").html(data);
                        checkPaymentStatus(data); // Panggil fungsi pengecekan
                    });
                    return;
                }

                $.post("validate_promo.php", { promo_code: promoCode, nisn: nisn }, function(data){
                    try {
                        var response = JSON.parse(data);
                        if (response.valid) {
                             let formattedAmount = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(response.new_amount);
                             $("#jumlah_bayar").html('<input type="text" class="form-control" value="' + formattedAmount + '" disabled> <input type="hidden" name="jumlah_bayar" value="' + response.new_amount + '"> <input type="hidden" name="id_spp" value="' + response.id_spp + '">');
                             toggleSubmitButton(); // Aktifkan tombol jika promo valid
                        } else {
                            // Jika promo tidak valid, bisa ditambahkan logika untuk menonaktifkan tombol
                            $("button[name='submit']").prop("disabled", true);
                        }
                    } catch(e) {
                        console.log("Error parsing response:", e);
                    }
                });
            });

            $("#jumlah_bayar").html('<input type="text" class="form-control" value="-" disabled> <input type="hidden" name="jumlah_bayar" value="0"> <input type="hidden" name="id_spp" value="0">');
            
            $("#payment_type").trigger('change');
        });
    </script>
</body>
</html>

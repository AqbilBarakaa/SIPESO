<?php
require "../db.php";

if (isset($_POST['payment_type']) && isset($_POST['nisn'])) {
    $payment_type = $_POST['payment_type'];
    $nisn = $_POST['nisn'];
    $angsuran = isset($_POST['angsuran']) ? $_POST['angsuran'] : null;
    $current_month = date('m');
    $current_year = date('Y');

    // Fetch the SPP nominal value
    $spp_query = mysqli_query($kon, "SELECT spp.nominal FROM siswa JOIN spp ON siswa.id_spp = spp.id_spp WHERE siswa.nisn = '$nisn'");
    $spp = mysqli_fetch_array($spp_query);
    $nominal_spp = $spp['nominal'];

    // 🟡 Validasi global: apakah sudah lunas total?
    $total_all_paid_query = mysqli_query($kon, "SELECT SUM(jumlah_bayar) AS total_all_paid FROM pembayaran WHERE nisn = '$nisn'");
    $total_all_paid_result = mysqli_fetch_array($total_all_paid_query);
    $total_all_paid = $total_all_paid_result['total_all_paid'] ?? 0;

    if ($total_all_paid >= $nominal_spp) {
        echo '
            <div class="alert alert-success" role="alert">
                Siswa telah melunasi SPP. Anda tidak dapat melakukan pembayaran lagi.
            </div>
        ';
        return; // hentikan proses lebih lanjut
    }

    // Initialize total_payment variable
    $total_payment = 0;

    // Check if the student has already paid for the current month
    $paid_query = mysqli_query($kon, "SELECT SUM(jumlah_bayar) AS total_paid FROM pembayaran WHERE nisn = '$nisn' AND tahun_dibayar = '$current_year' AND bulan_dibayar = '$current_month'");
    $paid_result = mysqli_fetch_array($paid_query);
    $total_paid_current_month = $paid_result['total_paid'] ?? 0;

    if ($payment_type == 'bulanan') {
        // Calculate monthly payment based on installment plan
        if ($angsuran === '6') {
            $monthly_payment = $nominal_spp / 6;
            $total_angsur = 6 * $monthly_payment;
        } elseif ($angsuran === '12') {
            $monthly_payment = $nominal_spp / 12;
            $total_angsur = 12 * $monthly_payment;
        } else {
            $monthly_payment = $nominal_spp; // Default if no installment selected or "Tidak ingin melakukan angsuran"
            $total_angsur = $monthly_payment;
        }

        $total_payment += $monthly_payment;

        // Check if user has already paid the full amount for this month, only if not in an installment plan
        if (empty($angsuran) && $total_paid_current_month >= $nominal_spp) {
            echo '
                <div class="alert alert-info" role="alert">
                    Siswa sudah membayar untuk bulan ini. Anda tidak dapat melakukan pembayaran lagi.
                </div>
            ';
        } else {
            // Check if user has paid the full installment
            $total_paid_query = mysqli_query($kon, "SELECT SUM(jumlah_bayar) AS total_paid FROM pembayaran WHERE nisn = '$nisn' AND payment_type = 'bulanan' AND angsuran = '$angsuran'");
            $total_paid_result = mysqli_fetch_array($total_paid_query);
            $total_paid_angsur = $total_paid_result['total_paid'] ?? 0;

            if (!empty($angsuran) && $total_paid_angsur >= $total_angsur) {
                echo '
                    <div class="alert alert-info" role="alert">
                        Angsuran siswa tersebut sudah lunas. Anda tidak dapat melakukan pembayaran lagi.
                    </div>
                ';
            } else {
                echo '
                    <input type="text" class="form-control" name="jumlah_bayar_display" id="jumlah_bayar_display" value="Rp' . number_format($total_payment, 0, ",", ".") . ',-" disabled>
                    <input type="hidden" name="jumlah_bayar" value="' . $total_payment . '">
                ';
            }
        }
    } elseif ($payment_type == 'semester') {
        // 🔒 Cek apakah siswa sedang memiliki angsuran berjalan (6x atau 12x)
        $cek_angsuran_query = mysqli_query($kon, "
            SELECT angsuran, SUM(jumlah_bayar) AS total_bayar 
            FROM pembayaran 
            WHERE nisn = '$nisn' AND payment_type = 'bulanan' AND (angsuran = '6' OR angsuran = '12') 
            GROUP BY angsuran
        ");

        while ($row = mysqli_fetch_array($cek_angsuran_query)) {
            $angsuran_dipilih = $row['angsuran'];
            $dibayar = $row['total_bayar'];
            $harus_dibayar = $nominal_spp;

            if ($dibayar < $harus_dibayar) {
                echo '
                    <div class="alert alert-danger" role="alert">
                        Siswa masih memiliki angsuran ' . $angsuran_dipilih . ' bulan yang belum lunas. Tidak bisa membayar dengan metode semester sebelum melunasi angsuran.
                    </div>
                ';
                return;
            }
        }

        // Calculate remaining amount for full semester
        $total_payment = $nominal_spp * 6;

        $total_paid_query = mysqli_query($kon, "SELECT SUM(jumlah_bayar) AS total_paid FROM pembayaran WHERE nisn = '$nisn' AND tahun_dibayar = '$current_year'");
        $total_paid_result = mysqli_fetch_array($total_paid_query);
        $total_paid = $total_paid_result['total_paid'] ?? 0;

        if ($total_paid >= $nominal_spp * 6) {
            echo '
                <div class="alert alert-info" role="alert">
                    Siswa sudah membayar untuk semester ini.
                </div>
            ';
        } else {
            $remaining_amount = $total_payment - $total_paid;
            echo '
                <input type="text" class="form-control" name="jumlah_bayar_display" id="jumlah_bayar_display" value="Rp' . number_format($remaining_amount, 0, ",", ".") . ',-" disabled>
                <input type="hidden" name="jumlah_bayar" value="' . $remaining_amount . '">
            ';
        }
    }
}
?>

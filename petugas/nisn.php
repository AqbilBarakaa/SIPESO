<?php
require "../db.php";

if (isset($_POST["nisnnya"])) {
    $nisnnya = $_POST["nisnnya"];

    // Fetch student details based on NISN
    $sql = mysqli_query($kon, "SELECT * FROM siswa WHERE nisn = '$nisnnya'");
    $row = mysqli_fetch_array($sql);
    $nis = $row["nis"];
    $nis4d = substr($nis, 0, 4);

    // Fetch SPP details based on year of admission
    $sql_spp = mysqli_query($kon, "SELECT * FROM spp WHERE tahun_ajaran = '$nis4d'");
    $row_spp = mysqli_fetch_array($sql_spp);

    // Calculate total unpaid amount and unpaid months
    $unpaid_query = mysqli_query($kon, "SELECT bulan_dibayar, tahun_dibayar FROM pembayaran WHERE nisn = '$nisnnya' AND status = 'unpaid' ORDER BY tahun_dibayar ASC, bulan_dibayar ASC");
    $unpaid_months = [];
    $total_unpaid = 0;

    while ($unpaid = mysqli_fetch_array($unpaid_query)) {
        $unpaid_months[] = $unpaid['bulan_dibayar'] . '-' . $unpaid['tahun_dibayar'];
        $total_unpaid += $row_spp['nominal'];  // Assuming each month has the same payment amount
    }

    // Calculate the total amount including any unpaid dues
    $total_amount = $total_unpaid + $row_spp['nominal'];

    echo '
        <input type="hidden" name="id_spp" value="' . $row_spp["id_spp"] . '">
    ';

    echo '
        <input type="hidden" name="unpaid_amount" value="' . $total_unpaid . '">
    ';

    echo '
        <input type="hidden" name="nominal_spp" value="' . $row_spp["nominal"] . '">
    ';

    echo '
        <input type="hidden" name="total_amount" value="' . $total_amount . '">
    ';

    echo '
        <input type="text" class="form-control" value="Rp. ' . number_format($total_amount, 2, ',', '.') . ',-" disabled>
    ';

    echo '
        <input type="hidden" name="jumlah_bayar" value="' . $total_amount . '">
    ';

    // Display unpaid months and total dues if any
    if (!empty($unpaid_months)) {
        echo '
            <div class="alert alert-warning" role="alert">
                Siswa memiliki tunggakan pada bulan berikut: ' . implode(", ", $unpaid_months) . '. Total tunggakan: Rp. ' . number_format($total_unpaid, 2, ',', '.') . '.
            </div>
        ';
    }
}
?>

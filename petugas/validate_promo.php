<?php
require "../db.php";

if (isset($_POST['promo_code']) && isset($_POST['nisn'])) {
    $promo_code = $_POST['promo_code'];
    $nisn = $_POST['nisn'];

    // Fetch promo details
    $promo_query = "SELECT * FROM code_beasiswa WHERE code = '$promo_code' AND used = FALSE LIMIT 1";
    $promo_result = mysqli_query($kon, $promo_query);

    if (mysqli_num_rows($promo_result) > 0) {
        $promo = mysqli_fetch_assoc($promo_result);
        $discount_amount = $promo['discount_amount'];

        // Fetch SPP details
        $spp_query = mysqli_query($kon, "SELECT spp.nominal FROM siswa JOIN spp ON siswa.id_spp = spp.id_spp WHERE siswa.nisn = '$nisn'");
        $spp = mysqli_fetch_assoc($spp_query);
        $nominal_spp = $spp['nominal'];

        $new_amount = $nominal_spp * 6 - $discount_amount; // Assuming the semester fee is 6 months

        echo json_encode(['valid' => true, 'new_amount' => $new_amount]);
    } else {
        echo json_encode(['valid' => false, 'message' => 'Kode promo tidak valid atau sudah digunakan.']);
    }
} else {
    echo json_encode(['valid' => false, 'message' => 'Data tidak lengkap.']);
}
?>

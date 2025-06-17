<?php
error_reporting(0);
require "../db.php";
$sppnya = isset($_POST["sppnya"]) ? $_POST["sppnya"] : "";
$sppnya1 = substr($sppnya, 0, 4);

$sql = mysqli_query($kon, "SELECT * FROM spp WHERE tahun_ajaran = '" . $sppnya1 . "'");
$row = mysqli_fetch_array($sql);

echo '
    <input type="text" class="form-control" name="nominal_spp" id="nominal_spp" value="Rp' . number_format($row["nominal"]) . ',-" disabled>
';

echo '
    <input type="hidden" name="id_spp" value="' . $row["id_spp"] . '">
';

?>
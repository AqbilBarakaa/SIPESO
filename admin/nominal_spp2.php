<?php
error_reporting(0);
require "../db.php";
$sppnya2 = isset($_POST["sppnya2"]) ? $_POST["sppnya2"] : "";
$sppnya1 = substr($sppnya2, 0, 4);

$sql = mysqli_query($kon, "SELECT * FROM spp WHERE tahun_ajaran = '" . $sppnya1 . "'");
$row = mysqli_fetch_array($sql);

echo '
    <input type="text" class="form-control" name="nominal_spp2" id="nominal_spp2" value="Rp' . number_format($row["nominal"]) . ',-" disabled>
';

echo '
    <input type="hidden" name="id_spp2" value="' . $row["id_spp"] . '">
';

?>
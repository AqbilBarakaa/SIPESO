<?php
session_start();
require "db.php";
$page = 'siswa';

$nominal_sppnya = 0;
$id_sppnya = 0;

if((!isset($_SESSION['admin'])) && (!isset($_SESSION['petugas'])) && (!isset($_SESSION['siswa'])))
{
    header("Location: login.php");
}

if(isset($_POST["submit"]))
{
    $nisn = nt_rand(10000000000, 999999999999);
    $nis = mysqli_real_escape_string($kon, isset($_POST["nis"]) ? $_POST["nis"] : "");
    $user = mysqli_real_escape_string($kon, isset($_POST["user"]) ? $_POST["user"] : "");
    $pwd = mysqli_real_escape_string($kon, isset($_POST["pwd"]) ? $_POST["pwd"] : "");
    $nama = mysqli_real_escape_string($kon, isset($_POST["nama"]) ? $_POST["nama"] : "");
    $id_kelas = mysqli_real_escape_string($kon, isset($_POST["id_kelas"]) ? $_POST["id_kelas"] : "");
    $alamat = mysqli_real_escape_string($kon, isset($_POST["alamat"]) ? $_POST["alamat"] : "");
    $no_telp = mysqli_real_escape_string($kon, isset($_POST["no_telp"]) ? $_POST["no_telp"] : "");
    $id_spp = isset($_POST["id_spp"]) ? $_POST["id_spp"] : "";

    if(empty($nis) or empty($user) or empty($pwd) or empty($nama) or empty($id_kelas) or empty($alamat) or empty($no_telp)){
        echo '
            <script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI ISI TERLEBIH DAHULU!"); window.location = "";</script>       
        ';
    }else{

        $kue = mysqli_query($kon, "SELECT * FROM siswa WHERE username = '" . $user . "' AND password = '" . $pwd . "'");
        $cek = mysqli_fetch_array($kue);

        if($cek > 0){
            echo '
                <script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI DATA YANG LAIN"); window.location = "";</script>
            ';
        }
    }
}
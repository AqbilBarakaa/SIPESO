<?php
session_start();
error_reporting(0);

if ($_SESSION["siswa"])
{
	unset($_SESSION["siswa"]);
	header("Location: ../login.php");
}

else
{
	header("Location: ../login.php");
}

?>
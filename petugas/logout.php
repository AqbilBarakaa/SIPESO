<?php
session_start();
error_reporting(0);

if ($_SESSION["petugas"])
{
	unset($_SESSION["petugas"]);
	header("Location: ../login.php");
}

else
{
	header("Location: ../login.php");
}

?>
<?php
session_start();

if ($_SESSION["admin"])
{
	unset($_SESSION["admin"]);
	header("Location: ../login.php");
}

else
{
	header("Location: ../login.php");
}

?>
<?php
	session_start();
	$_SESSION['kartu_id'] = $_POST['id'];
	echo '<script language="javascript">window.location = "photo.php";</script>';
?>
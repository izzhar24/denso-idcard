<?php
	session_start();
	date_default_timezone_set('Asia/Jakarta');
	include "assets/vendor/engine/engine.php";
	//echo '<pre>';
	//print_r($_GET);
	//print_r($_SESSION);
	$data = $db->query("select * from tbl_template where md5(id)='".$_GET['id']."'")->fetchAll();
	//print_r($data);
	
	$_SESSION['kartu_background'] = $data[0]['dokumen'];
	
	//membuat IDCard
	header("Content-type: image/png");
	$string = "Mohammad Ludfi";
	$im     = imagecreatefrompng("assets/img/template1.png");
	$orange = imagecolorallocate($im, 0, 0, 0);
	$px     = 65;
	imagestring($im, 15, $px, 124, $string, $orange);
	imagepng($im);
	imagedestroy($im);
	
	//echo '<script language="javascript">window.location = "background.php";</script>';
?>
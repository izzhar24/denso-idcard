<?php
	session_start();
	date_default_timezone_set('Asia/Jakarta');
	//echo '<pre>';
	//print_r($_POST);
	//print_r($_SESSION);
	
	function base64_to_jpeg($base64_string, $output_file) {
		// open the output file for writing
		$ifp = fopen( $output_file, 'wb' ); 
	
		// split the string on commas
		// $data[ 0 ] == "data:image/png;base64"
		// $data[ 1 ] == <actual base64 string>
		$data = explode( ',', $base64_string );
	
		// we could add validation here with ensuring count( $data ) > 1
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );
	
		// clean up the file resource
		fclose( $ifp ); 
	
		return $output_file; 
	}
	
	$nama_file = $_SESSION['kartu_id'].date("YmdHis");
	file_put_contents('assets/photo/'.$nama_file.'.png', file_get_contents($_POST['image']));
	$_SESSION['kartu_photo'] = $nama_file.'.png';
	echo '<script language="javascript">window.location = "background.php";</script>';
?>
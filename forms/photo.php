<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Denso - ID Card Printed</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
  <script language="javascript">
  	function cekKartu(id) {	
		document.getElementById('data_pasien').innerHTML = '<img src="assets/img/proses.gif">';
		document.getElementById('idCard').disabled = true;
		setTimeout(getKartu, 1000);
	}
	

  	function getKartu(id) {
		var url = "kartu_insert.php";
		var id = document.getElementById('idCard').value;
		var data = {id:id};

		$('#data_pasien').load(url,data, function(){
			$('#data_pasien').fadeIn('fast');
		});
		
		document.getElementById('idCard').disabled = false;
		document.getElementById("idCard").focus();
	}
  </script>
  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

  <!-- =======================================================
  * Template Name: MyResume - v2.1.0
  * Template URL: https://bootstrapmade.com/free-html-bootstrap-template-my-resume/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>



  <!-- ======= Header ======= -->
  <header id="header" class="d-flex flex-column justify-content-center">

    <nav class="nav-menu">
      <ul>
        <li><a href="index.php"><i class="bx bx-home"></i> <span>Halaman Utama</span></a></li>
        <li><a href="kartu.php"><i class="bx bx-server"></i> <span>Baca Kartu</span></a></li>
        <li class="active"><a href="photo.php"><i class="bx bx-user"></i> <span>Photo / Selfie</span></a></li>
        <li><a href="background.php"><i class="bx bx-book-content"></i> <span>Pilih Background</span></a></li>
        <li><a href="#"><i class="bx bx-file-blank"></i> <span>Preview / Cetak</span></a></li>
      </ul>
    </nav><!-- .nav-menu -->

  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">

        <div align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td>
                        <form method="POST" action="photo_simpan.php">
                                <div id="my_camera" style="margin-top: 10px;" enctype="multipart/form-data"></div>
                                <input type=button value="Take Snapshot" onClick="take_snapshot()">
                                <input type="hidden" name="image" class="image-tag">
                                <input type="submit" value="Simpan Photo" />
                        </form>
                    </td>
                    <td align="right">
                    	<div id="results">Your captured image will appear here...</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
  </section><!-- End Hero -->

  </main><!-- End #main -->

  <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
	//alert(screen.height);
    Webcam.set({
        width: 490,
        height: 390,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
  
    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?? 'Denso - ID Card Printed' ?></title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= asset('img/favicon.png') ?>" rel="icon">
    <link href="<?= asset('img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


    <link href="<?= asset('vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/icofont/icofont.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/boxicons/css/boxicons.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/venobox/venobox.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/owl.carousel/assets/owl.carousel.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/aos/aos.css') ?>" rel="stylesheet">

    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <?= renderPush('style') ?>
</head>

<body>

    <!-- ======= Mobile nav toggle button ======= -->
    <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>


    <!-- ======= Header ======= -->
    <header id="header" class="d-flex flex-column justify-content-center">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>


    </header>
    <!-- End Header -->

    <main>
        <?= $content ?>
    </main>

    <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="<?= asset('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('vendor/jquery.easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= asset('vendor/php-email-form/validate.js') ?>"></script>
    <script src="<?= asset('vendor/waypoints/jquery.waypoints.min.js') ?>"></script>
    <script src="<?= asset('vendor/counterup/counterup.min.js') ?>"></script>
    <script src="<?= asset('vendor/isotope-layout/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= asset('vendor/venobox/venobox.min.js') ?>"></script>
    <script src="<?= asset('vendor/owl.carousel/owl.carousel.min.js') ?>"></script>
    <script src="<?= asset('vendor/typed.js/typed.min.js') ?>"></script>
    <script src="<?= asset('vendor/aos/aos.js') ?>"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Template Main JS File -->
    <script src="<?= asset('js/main.js') ?>"></script>
    <?php if (!empty($_SESSION['success'])): ?>
        <script>
            $(document).ready(function() {
                toastr.success("<?= $_SESSION['success'] ?>");
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <script>
            $(document).ready(function() {
                toastr.error("<?= $_SESSION['error'] ?>");
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?= renderPush('scripts') ?>
</body>

</html>
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Luong Thuc - AcmaTvirus" />
    <!-- Document Title -->
    <title><?= (!empty($SEO->title)) ? $SEO->title : getSetting('data_seo')->meta_title; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= TEMPLATE_DEFAULT ?>images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= TEMPLATE_DEFAULT ?>images/favicon.ico" type="image/x-icon">

    <!-- FontsOnline -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,500,700,800,900,300,100' rel='stylesheet'
        type='text/css'>
    <style>
        @font-face {
            font-family: "Ionicons";
            src: url("https://code.ionicframework.com/ionicons/2.0.1/fonts/ionicons.eot?v=2.0.1");
            src: url("https://code.ionicframework.com/ionicons/2.0.1/fonts/ionicons.eot?v=2.0.1#iefix") format("embedded-opentype"), url("https://code.ionicframework.com/ionicons/2.0.1/fonts/ionicons.ttf?v=2.0.1") format("truetype"), url("https://code.ionicframework.com/ionicons/2.0.1/fonts/ionicons.woff?v=2.0.1") format("woff"), url("http://code.ionicframework.com/ionicons/2.0.1/fonts/ionicons.svg?v=2.0.1#Ionicons") format("svg");
    </style>
    <!-- StyleSheets -->
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/main.css">
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/style.css">
    <link rel="stylesheet" href="<?= TEMPLATE_DEFAULT ?>css/responsive.css">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="<?= TEMPLATE_DEFAULT ?>rs-plugin/css/settings.css" media="screen" />

    <!-- JavaScripts -->
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/modernizr.js"></script>
</head>

<body>
    <!-- LOADER ===========================================-->
    <div id="loader">
        <div class="loader">
            <div class="position-center-center">
                <div id="preloader6"> <span></span> <span></span> <span></span> <span></span> </div>
            </div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrap">
        @include('default/_header')
        <?= $main_content; ?>
        @include('default/_footer')
    </div>
    <!-- End Page Wrapper -->

    <!-- JavaScripts -->
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/jquery/jquery.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/wow.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/bootstrap.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/own-menu.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/flexslider/jquery.flexslider-min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/jquery.countTo.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/jquery.isotope.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/jquery.bxslider.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/owl.carousel.min.js"></script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/vendors/jquery.sticky.js"></script>

    <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
    <script type="text/javascript" src="<?= TEMPLATE_DEFAULT ?>rs-plugin/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="<?= TEMPLATE_DEFAULT ?>rs-plugin/js/jquery.themepunch.revolution.min.js">
    </script>
    <script src="<?= TEMPLATE_DEFAULT ?>js/main.js"></script>
</body>

</html>

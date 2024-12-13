<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <title><?=$title?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?=DOMAIN_NAME?>assets/img/favicon.png" rel="icon">
  <link href="<?=DOMAIN_NAME?>assets/img/favicon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?=DOMAIN_NAME?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?=DOMAIN_NAME?>assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Datapicker -->
  <link rel="stylesheet" href="<?=DOMAIN_NAME?>assets/vendor/datepicker/datepicker.material.css">

  <!-- Unpkg | Upload file with preview -->
  <link rel="stylesheet" href="<?=DOMAIN_NAME?>/assets/vendor/unpkg/main.css">
  
  <? if (isset($section) && isset($subSection) && $section === "recorridos" && $subSection === "Listado"): ?>
    <!-- PWA -->
    <link
      rel="manifest"
      href="data:application/manifest+json,{%22name%22%3A%22TurApp%22%2C%22short_name%22%3A%22TurApp%22%2C%22description%22%3A%22Aplicaci%C3%B3n%20de%20gesti%C3%B3n%20de%20salidas%20de%20excursiones%20para%20gu%C3%ADas%22%2C%22manifest_version%22%3A1%2C%22icons%22%3A%5B%7B%22src%22%3A%22https%3A%2F%2Fdv-proyectofinal.infy.uk%2Fassets%2Fimg%2Ffavicon.png%22%2C%22sizes%22%3A%2232x32%22%2C%22type%22%3A%22image%2Fpng%22%7D%2C%7B%22src%22%3A%22https%3A%2F%2Fdv-proyectofinal.infy.uk%2Fassets%2Fimg%2Flogo.png%22%2C%22sizes%22%3A%2250x50%22%2C%22type%22%3A%22image%2Fpng%22%7D%2C%7B%22src%22%3A%22https%3A%2F%2Fdv-proyectofinal.infy.uk%2Fassets%2Fimg%2Flogo-md.png%22%2C%22sizes%22%3A%22144x144%22%2C%22type%22%3A%22image%2Fpng%22%7D%5D%2C%22start_url%22%3A%22https%3A%2F%2Fdv-proyectofinal.infy.uk%2Fpwa%2Flogin.html%22%2C%22background_color%22%3A%22%23dddddd%22%2C%22display%22%3A%22standalone%22}"
    />
  <? endif; ?>

  <!-- Template Main CSS File -->
  <link href="<?=DOMAIN_NAME?>assets/css/style.css?v=<?=date("Ymd_His", filemtime(PATH_SERVER.'assets/css/style.css'))?>" rel="stylesheet">


  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
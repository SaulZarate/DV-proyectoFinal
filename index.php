<? require_once __DIR__ . "/config/init.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Favicons -->
  <link href="<?=DOMAIN_NAME?>assets/img/favicon.png" rel="icon">
  <link href="<?=DOMAIN_NAME?>assets/img/favicon.png" rel="apple-touch-icon">

  <title>Inicio | <?=APP_NAME?></title>

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


  <link href="<?=DOMAIN_NAME?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?=DOMAIN_NAME?>assets/css/app.public.css">

</head>
<body>
  <header class="container-fluid shadow">

    <div class="header__contentText text-white">
      <h1>Bienvenido a TurApp!</h1>
      <p>Contamos con salidas para todo tipo de gustos</p>
    </div>

    <div class="contentNav container pt-3 sticky-top">
      <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">
            <img src="<?=DOMAIN_NAME?>assets/img/logo.png" alt="logo de tur app" height="70" width="70">
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" href="/">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Calendario</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Salidas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Preguntas frecuentes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contacto</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main class="my-3">
    <p><span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut fuga quia dignissimos vero doloribus, ad ipsum ex. Molestiae, fugit tenetur! Aspernatur eos soluta adipisci, voluptates ipsum rerum blanditiis ut reiciendis?</span><span>Libero impedit sit possimus esse voluptatem suscipit rerum praesentium eius cupiditate numquam eveniet commodi corrupti alias delectus, molestias iusto veritatis nisi corporis accusamus dignissimos est perspiciatis. Illum earum eos aspernatur?</span></p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora laudantium optio fuga quia explicabo alias quod quaerat praesentium iure? Nostrum ipsum distinctio tempore velit tenetur quaerat sit nulla. Totam, esse.</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic, culpa non? Et perspiciatis incidunt voluptas nam voluptatem ipsa accusamus, sequi corrupti magnam quidem, unde similique esse laborum voluptatum, temporibus recusandae!</p>
  </main>

  <footer>

  </footer>

  <script src="<?=DOMAIN_NAME?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
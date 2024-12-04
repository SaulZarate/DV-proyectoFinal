<header class="container-fluid shadow">

    <div class="header__contentText text-white">
        <h1>Bienvenido a TurApp!</h1>
        <p class="d-none d-md-block">Contamos con salidas para todo tipo de gustos</p>
    </div>

    <div class="contentNav pt-3">
        <nav class="navbar navbar-expand-lg navbar-dark shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="<?= DOMAIN_NAME ?>assets/img/logo.png" alt="logo de tur app" height="70" width="70">
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
                            <a class="nav-link" href="<?=DOMAIN_NAME?>public/calendario">Calendario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=DOMAIN_NAME?>public/salidas">Salidas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=DOMAIN_NAME?>public/preguntas-frecuentes">Preguntas frecuentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=DOMAIN_NAME?>public/contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
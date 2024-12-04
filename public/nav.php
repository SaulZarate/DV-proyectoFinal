<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= DOMAIN_NAME ?>">
            <img src="<?= DOMAIN_NAME ?>assets/img/logo.png" alt="logo de tur app" height="70" width="70">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?=DOMAIN_NAME?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=strtolower($title)=="calendario" ? "active" : ""?>" href="<?=DOMAIN_NAME?>public/calendario">Calendario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=strtolower($title)=="salidas" ? "active" : ""?>" href="<?=DOMAIN_NAME?>public/salidas">Salidas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=strtolower($title)=="preguntas frecuentes" ? "active" : ""?>" href="<?=DOMAIN_NAME?>public/preguntas-frecuentes">Preguntas frecuentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?=strtolower($title)=="contacto" ? "active" : ""?>" href="<?=DOMAIN_NAME?>public/contacto">Contacto</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
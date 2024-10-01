<? 
require_once __DIR__ . "/../config/init.php"; 
$title .= " | Login";
?>

<!DOCTYPE html>
<html lang="es">

<? require_once PATH_SERVER . "/helpers/sections/head.php" ?>

<body>

    <main class="bg-light">
        <div class="shadow" style="
            background-image: url('<?=DOMAIN_NAME?>assets/img/login-fondo.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            background-position: top;
        ">
            <div class="container">

                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                                <!-- LOGO -->
                                <div class="d-flex justify-content-center py-4">
                                    <img src="<?=DOMAIN_NAME . "assets/img/logo-md.png"?>" alt="Logo de la aplicación turapp" height="250">
                                </div>

                                <div class="card bg-light my-3">
                                    <div class="card-body">
                                        <form class="row pt-4 g-3" id="formLogin">

                                            <div class="col-12">
                                                <label for="email" class="form-label"><i class="fas fa-envelope me-1"></i>E-mail</label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="pepito@gmail.com" required>
                                            </div>

                                            <div class="col-12">
                                                <label for="password" class="form-label"><i class="fas fa-unlock-alt me-1"></i>Contraseña</label>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="*******" required>
                                            </div>

                                            <!-- <div class="col-12">
                                                <input class="form-check-input" type="checkbox" name="recordarUsuario">
                                                <small class="text-secondary">Recordar usuario</small>
                                            </div> -->

                                            <input type="hidden" name="action" value="login">

                                            <div class="col-12">
                                                <button class="btn btn-success w-100" type="button" onclick="handlerSubmit()"><i class="fas fa-sign-in-alt me-1"></i>Iniciar sesión</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

            </div>
            
        </div>
    </main><!-- End #main -->


    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>
    <script>

        document.addEventListener("DOMContentLoaded", e => {

            const inputMail = document.getElementById("email")
            const inputPassword = document.getElementById("password")


            inputMail.addEventListener("keyup", function(event) {
                
                if(!isEmailValid(inputMail.value)) inputMail.classList.add("is-invalid")
                else inputMail.classList.remove("is-invalid")

                if (event.keyCode === 13) inputPassword.focus()
            });

            inputPassword.addEventListener("keyup", function(event) {
                
                if(inputPassword.value.length == 0) inputPassword.classList.add("is-invalid")
                else inputPassword.classList.remove("is-invalid")
                
                if (event.keyCode === 13) handlerSubmit()
            });

        })

        function handlerSubmit(){
            const data = new FormData(document.getElementById("formLogin"))
            
            /* printFormData(data) */

            fetch(
                "<?=DOMAIN_NAME?>admin/process.php", 
                {
                    method: "POST",
                    body: data,
                }
            )
            .then(res => res.json())
            .then(response => {
                console.log(response)

                if(response.status === "OK"){
                    location.href = response.redirection
                }else{
                    const {title, message, type} = response
                    Swal.fire(title, message, type)
                }
            })

        }
    </script>
</body>

</html>
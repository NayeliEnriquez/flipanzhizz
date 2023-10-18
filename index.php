<?php
    date_default_timezone_set("America/Mexico_City");
    $yearr = date('Y');

    if(isset($_GET["user_mail"])){
        $usuario_rh = $_GET['user_mail'];
    }else{
        $usuario_rh = '';
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="images/logo_only.png">
        <title>Novag RH | Index</title>
        <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
        </style>
        <link href="css/navbar-top-fixed.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet">
        <script type="text/javascript" src="js/rh.js"></script>
        <!-- include the script -->
        <script src="js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <body class="text-center">
        <?php
            $name_self = basename($_SERVER['PHP_SELF']);
            include('php/navs/nav_logout.php');
        ?>
        <main class="form-signin w-100 m-auto">
            <img class="mb-4" src="images/logo_1.png" alt="" width="190" height="190">
            <div class="form-floating">
                <input type="email" class="form-control" id="inp_user_rh" placeholder="name@example.com" value="<?php echo($usuario_rh); ?>" autocomplete="off">
                <label for="inp_user_rh">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="inp_rh_Password" placeholder="Password">
                <label for="inp_rh_Password">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" id="chk_ver_pass"> Mostrar contraseña
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" onclick="login_btn()" id="btn_login">Iniciar sesi&oacute;n</button>
            <div id="response_login"></div>
            <p class="mt-5 mb-3 text-muted">© Copyright <?php echo($yearr); ?></p>
        </main>
        <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#chk_ver_pass').click(function () {
                    if ($('#chk_ver_pass').is(':checked')) {
                        $('#inp_rh_Password').attr('type', 'text');
                    } else {
                        $('#inp_rh_Password').attr('type', 'password');
                    }
                });
            });
        </script>
    </body>
</html>
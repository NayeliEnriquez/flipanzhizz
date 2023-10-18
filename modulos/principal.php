<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Index</title>
        <!--<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">-->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
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
    
        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <script type="text/javascript" src="../js/rh.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <body>
        <?php
            include('navs/header_sys.php');
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                    $name_self = basename($_SERVER['PHP_SELF']);
                    include('navs/nav_menu.php');
                ?>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Pagina principal</h1>
                    </div>
                    <h2>Con acceso a:</h2>
                    <div class="bd-example">
                        <div class="accordion" id="accordionExample">
                            <?php
                                $p1_session = strpos($permisos_session, "1");
                                if ($p1_session !== false) {
                                    $p1_html = 'href="sistema.php"';
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Sistema
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Sistema.</strong><br>Modulo para la creaci&oacute;n de usuarios(crear, modificar y eliminar), ver areas y asignar encargado de las mismas y visualizar los empleados.
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

                                $p2_session = strpos($permisos_session, "2");
                                if ($p2_session !== false) {
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Personal
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Personal.</strong><br>Modulo para ver el personal asignado a cargo en el cual se puede modificar y ver horarios.
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

                                $p3_session = strpos($permisos_session, "3");
                                if ($p3_session !== false) {
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Solicitudes
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Solicitudes.</strong><br>Modulo para ver las solicitudes del personal asignado(aprobar, rechazar y ver historial).
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

                                $p4_session = strpos($permisos_session, "4");
                                if ($p4_session !== false) {
                                    $p4_html = 'href="reportes.php"';
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Reportes
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Reportes.</strong><br>Modulo contiene los diferentes reportes personalizados para su uso.
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

                                $p5_session = strpos($permisos_session, "5");
                                if ($p5_session !== false) {
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Nomina
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Nomina.</strong><br>Modulo para la revision de las horas trabajadas de todos los empleados.
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

                                $p6_session = strpos($permisos_session, "6");
                                if ($p6_session !== false) {
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        Supervisor de producci&oacute;n
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <strong>Modulo Supervisor de producci&oacute;n.</strong><br>Modulo para la aprobaci&oacute;n de horas extra de los empleados.
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

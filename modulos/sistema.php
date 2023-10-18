<?php
    include ('../php/conn.php');
?>
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
                        <h1 class="h2">Sistema</h1>
                    </div>
                    <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Usuarios</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab_area-tab" data-bs-toggle="pill" data-bs-target="#tab_area" type="button" role="tab" aria-controls="tab_area" aria-selected="false">Areas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab_employee-tab" data-bs-toggle="pill" data-bs-target="#tab_employee" type="button" role="tab" aria-controls="tab_employee" aria-selected="false">Empleados</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab_jerarquia-tab" data-bs-toggle="pill" data-bs-target="#tab_jerarquia" type="button" role="tab" aria-controls="tab_jerarquia" aria-selected="false" disabled>Jerarqu&iacute;as</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <button type="button" class="btn btn-success" onclick="tabla_usuarios()">Ver usuarios</button>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <div class="bd-callout bd-callout-info">
                                        <p>La clave generica es <mark><strong>abc123</strong></mark>,<br>solo para usuarios nuevos o contraseñas reestablecidas.</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Mdl_new_user">
                                        Crear usuario
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div id="response_usuarios"></div>
                                </div>
                            </div>

                            <!-- Modal nuevo usuario -->
                            <div class="modal fade" id="Mdl_new_user" tabindex="-1" aria-labelledby="Mdl_new_userLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="Mdl_new_userLabel">Nuevo usuario</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <!--<label for="inp_namefull" class="form-label">Nombre completo</label>
                                                <input type="text" class="form-control" id="inp_namefull" placeholder="Nombre completo" autocomplete="off">-->
                                                <label for="inp_namefull" class="form-label">Numero de empleado</label>
                                                <input class="form-control" list="datalistOptions" id="inp_namefull" placeholder="Busqueda...">
                                                <datalist id="datalistOptions">
                                                    <?php
                                                        $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                                        $exe_1 = sqlsrv_query($cnx, $query1);
                                                        while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                                            $emp_code_1 = $fila_1['emp_code'];
                                                            $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                                            $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                                            echo '
                                                                <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                                            ';
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inp_email" class="form-label">Correo electr&oacute;nico</label>
                                                <input type="email" class="form-control" id="inp_email" placeholder="name@example.com" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inp_email_confirm" class="form-label">Confirmar correo electr&oacute;nico</label>
                                                <input type="email" class="form-control" id="inp_email_confirm" placeholder="name@example.com" autocomplete="off">
                                            </div>
                                            <div class="row">
                                                <center><label class="form-label">Permisos</label></center>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p1">
                                                        <label class="form-check-label" for="chk_p1">Sistema</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p2">
                                                        <label class="form-check-label" for="chk_p2">Personal</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p3" checked>
                                                        <label class="form-check-label" for="chk_p3">Solicitudes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p4">
                                                        <label class="form-check-label" for="chk_p4">Reportes</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p5">
                                                        <label class="form-check-label" for="chk_p5">Nomina</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p6">
                                                        <label class="form-check-label" for="chk_p6">Supervisor de producci&oacute;n</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chk_p7">
                                                        <label class="form-check-label" for="chk_p7">Incapacidades</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div id="response_nuser"></div>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary" onclick="nuevo_usuario()">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_area" role="tabpanel" aria-labelledby="tab_area-tab" tabindex="0">
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <button type="button" class="btn btn-success" onclick="tabla_areas()">Ver &Aacute;reas</button>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                </div>
                                <div class="col-md-1 col-sm-12">
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <!-- Button trigger modal -->
                                    <!--<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Mdl_new_user">
                                        Crear usuario
                                    </button>-->
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div id="response_areas"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_employee" role="tabpanel" aria-labelledby="tab_employee-tab" tabindex="0">
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <button type="button" class="btn btn-success" onclick="tabla_empleados()">Ver empleados</button>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <button type="button" class="btn btn-info" onclick="tabla_supers()">Ver supervisores</button>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <button type="button" class="btn btn-warning" onclick="tabla_direc()">Ver directivos</button>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div id="response_empleados"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_jerarquia" role="tabpanel" aria-labelledby="tab_jerarquia-tab" tabindex="0">
                            D
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="mdl_asg_nomina" tabindex="-1" aria-labelledby="mdl_asg_nomina_lbl" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdl_asg_nomina_lbl">Asignar tipo de nomina</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="response_tipos_nominas"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="tipos_nominas(`unknown`, 2)">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal2 -->
        <div class="modal fade" id="mdl_change_boss" tabindex="-1" aria-labelledby="mdl_change_bossLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdl_change_bossLabel">Cambiar de jefe directo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="response_cambia_boss"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn_update_boss" onclick="update_boss()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal3 -->
        <div class="modal fade" id="mdl_nuevo_super" tabindex="-1" aria-labelledby="mdl_nuevo_superLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdl_nuevo_superLabel">Cambiar de jefe directo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label for="inp_num_empl_sup" class="form-label">Numero de empleado</label>
                                <input class="form-control" list="datalistOptions" id="inp_num_empl_sup" placeholder="Busqueda..." onkeyup="busca_emp_sup(event)">
                                <datalist id="datalistOptions">
                                    <?php
                                        $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                        $exe_1 = sqlsrv_query($conn, $query1);
                                        while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                            $emp_code_1 = $fila_1['emp_code'];
                                            $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                            $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                            echo '
                                                <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                            ';
                                        }
                                    ?>
                                </datalist>
                            </div>
                        </div>
                        <div id="response_super_emp">
                            <input type="hidden" id="inp_fname_sup" value="">
                            <input type="hidden" id="inp_lname_sup" value="">
                            <input type="hidden" id="inp_depto_sup" value="">
                            <input type="hidden" id="inp_pos_sup" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn_new_super" onclick="nuevo_super()">Registrar</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!--DataTable-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    </body>
</html>

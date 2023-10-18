<?php
    include ('../php/conn.php');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Nomina</title>
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
        <script type="text/javascript" src="../js/rh_nomina.js"></script>
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
                        <h1 class="h2">Nomina</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="inp_num_empl" class="form-label">Numero de empleado</label>
                            <input class="form-control" list="datalistOptions" id="inp_num_empl" placeholder="Busqueda...">
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
                        <div class="col-md-4 col-sm-12">
                            <label for="slc_deptos" class="form-label">Departamentos</label>
                            <select class="form-select" id="slc_deptos">
                                <option value='0' selected>Selecciona un departamento</option>
                            <?php
                                $query_deptos = "SELECT id, dept_name, parent_dept_id FROM [zkbiotime].[dbo].[personnel_department] ORDER BY dept_name ASC";
                                $exe_deptos = sqlsrv_query($conn, $query_deptos);
                                while ($fila_deptos = sqlsrv_fetch_array($exe_deptos, SQLSRV_FETCH_ASSOC)) {
                                    $id_bd = $fila_deptos['id'];
                                    $dept_name = utf8_encode($fila_deptos['dept_name']);
                                    $parent_dept_id = $fila_deptos['parent_dept_id'];

                                    $id_bd_depto = ($parent_dept_id != NULL) ? $parent_dept_id : $id_bd ;
                                    echo '<option value="'.$id_bd.'">'.$dept_name.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-warning position-relative" onclick="limpiar_pantalla()">
                                Limpiar pantalla
                            </button>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-success position-relative" onclick="busqueda_rh()">
                                Buscar
                            </button>
                        </div>
                        <!--<div class="col-md-3 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-success position-relative" onclick="men_nom('sem')">
                                Nomina Semanal
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-primary position-relative" onclick="men_nom('qui')">
                                Nomina Quincenal
                            </button>
                        </div>-->
                    </div>
                    <br>
                    <!--<div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div id="response_menu_nom"></div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div id="response_get_nomina"></div>-->
                    <hr class="my-4">
                    <div id="response_busqueda_rh"></div>
                    <hr class="my-4">
                    <div id="response_info_rh"></div>
                    <div id="response_info2_rh"></div>
                    <br><br><br>
                </main>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="Modal_vacaciones" tabindex="-1" aria-labelledby="Modal_vacacionesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Modal_vacacionesLabel">Resumen de vacaciones</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!--<div id="resaponse_vacaciones_modal"></div>-->
                        <div id="response_vacaciones_rh"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="save_dias()">Guardar</button>
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
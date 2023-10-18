<?php
    include ('../php/conn.php');
    session_start();
    $num_empleado_session = $_SESSION['num_empleado_a'];
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
        <script>
            const agregarFila = () => {
                const table_z = document.getElementById('tb_fechas_v')
                const rowCount_z = table_z.rows.length
                document.getElementById('tb_fechas_v').insertRow(-1).innerHTML = '<td><center>'+rowCount_z+'</center></td><td><center><input class="form-control" type="date" name="inp_arrdates[]"></center></td>'
            }
            
            const eliminarFila = () => {
                const table = document.getElementById('tb_fechas_v')
                const rowCount = table.rows.length
                
                if (rowCount <= 1)
                    alertify.warning('No se puede eliminar el encabezado.')
                else
                table.deleteRow(rowCount -1)
            }
        </script>
    </head>
    <body onload="new_solicitudes()">
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
                        <h1 class="h2">Solicitudes</h1>
                    </div>
                    <?php
                        $revisar_rh = "SELECT
                                p_dept.dept_name
                            FROM
                                personnel_employee p_emp
                            INNER JOIN
                                personnel_department p_dept
                            ON
                                p_emp.department_id = p_dept.id
                            WHERE
                                p_emp.emp_code = '$num_empleado_session'
                            ";
                        $exe_rh = sqlsrv_query($conn, $revisar_rh);
                        $fila_rh = sqlsrv_fetch_array($exe_rh, SQLSRV_FETCH_ASSOC);
                        $dept_name = $fila_rh['dept_name'];
                        $val_dept = strpos($dept_name, 'HUMANOS');
                        if ($val_dept !== false) {
                    ?>
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <br>
                            <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Busquedas
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="rh_tabla_vacaciones()">Vacaciones nuevas</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="rh_tb_dia()">Solicitudes del dia</a></li>
                                    <!--<li><a class="dropdown-item" href="#">Something else here</a></li>-->
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label for="inp_f_busqueda" class="form-label">Ver solicitudes del: </label>
                            <input id="inp_f_busqueda" class="form-control" type="date">
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label for="inp_num_empl_rh" class="form-label"># empleado</label>
                            <input class="form-control" list="datalistOptions" id="inp_num_empl_rh" placeholder="Busqueda...">
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
                        <div class="col-md-3 col-sm-12">
                            <label for="slc_formato" class="form-label">Formatos de solicitud</label>
                            <select class="form-select" id="slc_formato" required>
                                <!--<option value="ALL" selected>Todos</option>-->
                                <option value="rh_solicitudes">Formato ausencia</option>
                                <option value="rh_salida" selected>Formato Entrada/Salida</option>
                                <option value="rh_vacaciones">Formato vacaciones</option>
                            </select>
                        </div>
                        <div class="col-md-1 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-success" onclick="busqueda_full_rh()">Buscar</button>&nbsp;
                        </div>
                        <div class="col-md-1 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-info" onclick="limpia_full_rh()">Limpiar</button>
                        </div>
                    </div>
                    <?php
                        }else{
                    ?>
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <button type="button" class="btn btn-success position-relative" onclick="tabla_solicitudes()">
                                Ver solicitudes
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="spn_tot_nuev">
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <button type="button" class="btn btn-info position-relative" onclick="tabla_calendario()">
                                Ver calendario
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <button type="button" class="btn btn-primary position-relative" onclick="tabla_solicitudes_h()">
                                Ver historico solicitudes
                            </button>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div id="response_solicitudes">
                                <div class="ratio ratio-1x1">
                                    <iframe src="../calendario/" title="Calendario" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="modal fade" id="Mdl_dias_vac_RH" tabindex="-1" aria-labelledby="Mdl_dias_vac_RHLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Mdl_dias_vac_RHLabel">Actualizar dias de vacaciones</h1>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body">
                        <div id="response_vacaciones_rh"></div>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>-->
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

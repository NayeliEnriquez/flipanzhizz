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
        <script>
            function download_formatos(slc_formato, inp_folio, inp_num_empl_h) {
                var parametros={
                    "slc_formato":slc_formato,
                    "inp_folio":inp_folio,
                    "inp_num_empl_h":inp_num_empl_h
                }
                $.ajax({
                    data: parametros,
                    url: "../public_formats/script/download_formatos.php",
                    type: "POST",
                    error: function(){
                        alert("Error inesperado.");
                    },
                    success: function(data){
                        console.log(data)
                        setTimeout(function(){
                            window.open("../public_formats/script/"+data);
                        }, 1000);
                    }
                })
            }
        </script>
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
                        <h1 class="h2">Incapacidades</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <!--<button type="button" class="btn btn-outline-info" onclick="ver_hist_inc()">Ver historial de incapacidades</button>-->
                        </div>
                        <div class="col-md-3 col-sm-12"></div>
                        <div class="col-md-3 col-sm-12"></div>
                        <div class="col-md-3 col-sm-12">
                            <button type="button" class="btn btn-outline-success" id="btn_n_inc">Nueva incapacidad</button>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div id="response_inca" style="display: none;">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label for="inp_num_empl_in" class="form-label">Numero de empleado</label>
                                <input class="form-control" list="datalistOptions" id="inp_num_empl_in" placeholder="Busqueda...">
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
                            <div class="col-md-4 col-sm-12">
                            </div>
                            <div class="col-md-2 col-sm-12">
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <br>
                                <button type="button" class="btn btn-outline-primary" onclick="btn_busca_empleado()">Buscar</button>
                            </div>
                        </div>
                        <div id="response_emp_inc">
                        </div>
                    </div>
                    <div id="tabla_inc_his">
                        <table class="table table-dark table-hover align-middle" id="tb_incapacidades">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"># Emp</th>
                                    <th scope="col">Nombre completo</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Fecha inicial</th>
                                    <th scope="col">Fecha final</th>
                                    <th scope="col">Dias totales</th>
                                    <th scope="col">Creado por</th>
                                    <th scope="col">Observaciones</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql_a = "SELECT * FROM rh_solicitudes WHERE rh_solicitudes.tipo_permiso = '3' AND estatus = '1'";
                                    $exe_a = sqlsrv_query($cnx, $sql_a);
                                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                        $id_a = $fila_a['id'];                          $id_empleado_a = $fila_a['id_empleado'];        $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                                        $tipo_goce_a = $fila_a['tipo_goce'];            
                                        $f_ini_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini'])))), 5, 10);
                                        $f_fin_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin'])))), 5, 10);
                                        $observaciones_a = $fila_a['observaciones'];    
                                        $f_solicitud_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud'])))), 5, 10);
                                        $id_solicitante_a = $fila_a['id_solicitante'];
                                        $id_depto_a = $fila_a['id_depto'];              $estatus_a = $fila_a['estatus'];                $historico_a = $fila_a['historico'];
                                        $hr_in_a = $fila_a['hr_in'];                    $hr_out_a = $fila_a['hr_out'];                  $tipo_permiso_a = $fila_a['tipo_permiso'];
                                        $tot_dias_inc_a = $fila_a['tot_dias_inc'];      $file_evidencia_a = $fila_a['file_evidencia'];

                                        $query_b = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code LIKE '$id_empleado_a'";
                                        $exe_b = sqlsrv_query($cnx, $query_b);
                                        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                        $first_name_b = utf8_encode($fila_b['first_name']);
                                        $last_name_b = utf8_encode($fila_b['last_name']);

                                        $query_c = "SELECT * FROM personnel_department WHERE id = '$id_depto_a'";
                                        $exe_c = sqlsrv_query($cnx, $query_c);
                                        $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
                                        $dept_name_c = utf8_encode($fila_c['dept_name']);

                                        echo '
                                            <tr>
                                                <td>'.$id_a.'</td>
                                                <td>'.$id_empleado_a.'</td>
                                                <td>'.$last_name_b.' '.$first_name_b.'</td>
                                                <td>'.$dept_name_c.'</td>
                                                <td>'.$f_ini_a.'</td>
                                                <td>'.$f_fin_a.'</td>
                                                <td>'.$tot_dias_inc_a.'</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_'.$id_a.'" aria-expanded="false" aria-controls="collapse_'.$id_a.'">
                                                        Historial
                                                    </button>
                                                    <div class="collapse" id="collapse_'.$id_a.'">
                                                        <div class="card text-bg-secondary card-body">
                                                        '.$historico_a.'
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>'.$observaciones_a.'</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="borrar_inc('.$id_a.')">Borrar <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"># Emp</th>
                                    <th scope="col">Nombre completo</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Fecha inicial</th>
                                    <th scope="col">Fecha final</th>
                                    <th scope="col">Dias totales</th>
                                    <th scope="col">Creado por</th>
                                    <th scope="col">Observaciones</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </main>
            </div>
        </div>
        
        <!-- Modal -->
        <!--<div class="modal fade" id="Modal_vacaciones" tabindex="-1" aria-labelledby="Modal_vacacionesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="Modal_vacacionesLabel">Resumen de vacaciones</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="response_vacaciones_rh"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="save_dias()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>-->
      <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <!--DataTable-->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
      <script>
         const response_inca = document.getElementById("response_inca");
         const tabla_inc_his = document.getElementById("tabla_inc_his");
         const btn_n_inc = document.getElementById("btn_n_inc");
         btn_n_inc.onclick = function () {
               if (response_inca.style.display !== "none") {
                  response_inca.style.display = "none";
                  tabla_inc_his.style.display = "block";
               } else {
                  response_inca.style.display = "block";
                  tabla_inc_his.style.display = "none";
               }
         }

         $(document).ready(function() {
            $('#tb_incapacidades').DataTable({
               "language": {
                  "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
               },
               dom: 'Bfrtip',
               buttons: [{
                  extend: 'excelHtml5',
                  title: 'reporte_incapacidades',
                  text:'Descargar Excel',
                  className: 'btn btn-success',//***Le das las clases de boton para que tenga estilo
                  exportOptions: {
                     columns: [ 0,1,2,3,4,5,6,8]//***Indica las columnas que va a mostrar el excel
                  }
               }]
            });
         });
      </script>
    </body>
</html>
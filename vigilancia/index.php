<?php
    include ('../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $yearr = date('Y');
    /*$f_y_h = date('Y-m-d H:m:s');
    $f_y_h = date("Y-m-d H:m:s",strtotime($f_y_h."- 1 days")); */
    $f_y_h_today = date('Y-m-d');
    $f_y_h = date("Y-m-d",strtotime($f_y_h_today."- 1 days")); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Vigilancia</title>
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../js/formatos.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <script>
        function vigilancia_opc(id_bd) {
            var parametros={
                "id_bd":id_bd
            }
            
            $.ajax({
                data: parametros,
                url: "vigilancia_opc.php",
                type: "POST",
                beforeSend: function(){
                    $("#response_vigilancia").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#response_vigilancia").empty();
                    $("#response_vigilancia").append(data);
                }
            })
        }
        function save_vigilancia() {
            inp_id_bd_s = document.getElementById("inp_id_bd_s").value;
            inp_texto = document.getElementById("inp_texto").value;
            inp_name_v = document.getElementById("inp_name_v").value;

            var parametros={
                "inp_id_bd_s":inp_id_bd_s,
                "inp_texto":inp_texto,
                "inp_name_v":inp_name_v
            }
            
            $.ajax({
                data: parametros,
                url: "save_vigilancia.php",
                type: "POST",
                beforeSend: function(){
                    $("#btn_save_v").hide();
                    alertify.warning('Enviando datos...')
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    console.log(data)
                    if (data == 1) {
                        alertify.success('Comentarios guardados')
                        //$("#btn_save_v").show();
                        setTimeout(function(){
                            location.reload();  
                        }, 2500);
                    }else{
                        //$("#response_vigilancia").append(data);
                        $("#btn_save_v").show();
                    }
                }
            })
        }
    </script>
    <body class="text-center" onload="atm_vigilancia()">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><img src="../images/novag_logo_small.png" width="75" height="35"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                </div>
            </div>
        </nav>
        <div class="container">
            <br><br><br>
            <div class="col-md-12 col-lg-12">
                <h4 class="mb-3">Solicitudes de entrada y salida</h4>
                <div class="row">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <!--<button class="btn btn-primary me-md-2" type="button">Button</button>-->
                        <button class="btn btn-outline-info" type="button" onclick="actualizar_vigilancia()">Actualizar</button>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row">
                    <div id="vigilancia_tbody">
                        <table class="table table-dark table-hover align-middle" id="tb_vigilancia" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empleado</th>
                                    <th>Tipo de ausencia</th>
                                    <th>Asunto</th>
                                    <th>Fecha de permiso E/S</th>
                                    <th>Hora de permiso E/S</th>
                                    <th>Departamento</th>
                                    <th>Fecha de solicitud</th>
                                    <th>Estatus</th>
                                    <th>Informes</th>
                                    <th>Vigilancia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $k = 0;
                                    //$query_a = "SELECT TOP(25) * FROM dbo.rh_salida WHERE dbo.rh_salida.estatus = '1' ORDER BY f_permiso DESC, h_permiso ASC";
                                    $query_a = "SELECT * FROM dbo.rh_salida WHERE dbo.rh_salida.estatus = '1' AND f_permiso >= '$f_y_h' AND f_permiso <= '$f_y_h_today' ORDER BY f_permiso DESC, h_permiso ASC";
                                    $exe_a = sqlsrv_query($cnx, $query_a);
                                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                        $k++;
                                        $id_db = $fila_a['id'];
                                        $id_empleado_a = $fila_a['id_empleado'];
                                        $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                                        $asunto_a = $fila_a['asunto'];
                                        $asunto_a_txt = ($asunto_a == '1') ? "Asunto de trabajo" : "Asunto personal" ;
                                        $f_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_permiso']))));
                                        $f_permiso_a = substr($f_permiso_a, 5, 10);
                                        $h_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_permiso']))));
                                        $h_permiso_a = substr($h_permiso_a, 16, 8);
                                        $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
                                        $f_solicitud_a = substr($f_solicitud_a, 5, 10);
                                        $historico_a = $fila_a['historico'];
                                        $estatus_a = $fila_a['estatus'];
                                        switch ($estatus_a) {
                                            case '0':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-watch"><circle cx="12" cy="12" r="7"></circle><polyline points="12 9 12 12 13.5 13.5"></polyline><path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83m.01-10.7l.35-3.83A2 2 0 0 1 9.83 1h4.35a2 2 0 0 1 2 1.82l.35 3.83"></path></svg>';
                                                break;
                                            
                                            case '1':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>';
                                                break;
                                            
                                            case '2':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>';
                                                break;
                                        }

                                        //***
                                        $query_b = "
                                            SELECT
                                                personnel_employee.id,
                                                personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
                                                personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name,
                                                dbo.personnel_employee.hire_date
                                            FROM
                                                personnel_employee
                                            INNER JOIN
                                                personnel_department
                                            ON
                                                personnel_employee.department_id = personnel_department.id
                                            INNER JOIN
                                                personnel_position
                                            ON
                                                personnel_employee.position_id = personnel_position.id
                                            WHERE
                                                personnel_employee.emp_code = '$id_empleado_a'
                                        ";
                                        $exe_b = sqlsrv_query($conn, $query_b);
                                        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                        $first_name_b = $fila_b['first_name'];
                                        $last_name_b = $fila_b['last_name'];
                                        $dept_name_b = $fila_b['dept_name'];
                                        
                                        echo '
                                            <tr>
                                                <td>'.$id_db.'</td>
                                                <td>'.$id_empleado_a.' - '.$last_name_b.' '.$first_name_b.'</td>
                                                <td>'.$tipo_ausencia_a.'</td>
                                                <td>'.$asunto_a_txt.'</td>
                                                <td>'.$f_permiso_a.'</td>
                                                <td>'.$h_permiso_a.'</td>
                                                <td>'.$dept_name_b.'</td>
                                                <td>'.$f_solicitud_a.'</td>
                                                <td>'.$sts_desc.'</td>
                                                <td>'.$historico_a.'</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="vigilancia_opc('.$id_db.')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                    $query_a = "SELECT * FROM dbo.rh_salida WHERE dbo.rh_salida.estatus = '3' AND f_permiso >= '$f_y_h' AND f_permiso <= '$f_y_h_today' ORDER BY f_permiso DESC, h_permiso ASC";
                                    $exe_a = sqlsrv_query($cnx, $query_a);
                                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                        $k++;
                                        $id_db = $fila_a['id'];
                                        $id_empleado_a = $fila_a['id_empleado'];
                                        $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                                        $asunto_a = $fila_a['asunto'];
                                        $asunto_a_txt = ($asunto_a == '1') ? "Asunto de trabajo" : "Asunto personal" ;
                                        $f_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_permiso']))));
                                        $f_permiso_a = substr($f_permiso_a, 5, 10);
                                        $h_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_permiso']))));
                                        $h_permiso_a = substr($h_permiso_a, 16, 8);
                                        $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
                                        $f_solicitud_a = substr($f_solicitud_a, 5, 10);
                                        $historico_a = $fila_a['historico'];
                                        $estatus_a = $fila_a['estatus'];
                                        switch ($estatus_a) {
                                            case '0':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-watch"><circle cx="12" cy="12" r="7"></circle><polyline points="12 9 12 12 13.5 13.5"></polyline><path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83m.01-10.7l.35-3.83A2 2 0 0 1 9.83 1h4.35a2 2 0 0 1 2 1.82l.35 3.83"></path></svg>';
                                                break;
                                            
                                            case '1':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>';
                                                break;
                                            
                                            case '2':
                                                $sts_desc = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>';
                                                break;
                                        }

                                        //***
                                        $query_b = "
                                            SELECT
                                                personnel_employee.id,
                                                personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
                                                personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name,
                                                dbo.personnel_employee.hire_date
                                            FROM
                                                personnel_employee
                                            INNER JOIN
                                                personnel_department
                                            ON
                                                personnel_employee.department_id = personnel_department.id
                                            INNER JOIN
                                                personnel_position
                                            ON
                                                personnel_employee.position_id = personnel_position.id
                                            WHERE
                                                personnel_employee.emp_code = '$id_empleado_a'
                                        ";
                                        $exe_b = sqlsrv_query($conn, $query_b);
                                        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                        $first_name_b = $fila_b['first_name'];
                                        $last_name_b = $fila_b['last_name'];
                                        $dept_name_b = $fila_b['dept_name'];
                                        
                                        echo '
                                            <tr>
                                                <td>'.$id_db.'</td>
                                                <td>'.$id_empleado_a.' - '.$last_name_b.' '.$first_name_b.'</td>
                                                <td>'.$tipo_ausencia_a.'</td>
                                                <td>'.$asunto_a_txt.'</td>
                                                <td>'.$f_permiso_a.'</td>
                                                <td>'.$h_permiso_a.'</td>
                                                <td>'.$dept_name_b.'</td>
                                                <td>'.$f_solicitud_a.'</td>
                                                <td>'.$sts_desc.'</td>
                                                <td>'.$historico_a.'</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="vigilancia_opc('.$id_db.')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Empleado</th>
                                    <th>Tipo de ausencia</th>
                                    <th>Asunto</th>
                                    <th>Fecha de permiso E/S</th>
                                    <th>Hora de permiso E/S</th>
                                    <th>Departamento</th>
                                    <th>Fecha de solicitud</th>
                                    <th>Estatus</th>
                                    <th>Informes</th>
                                    <th>Vigilancia</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--***-->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Observaciones</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="response_vigilancia"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn_save_v" onclick="save_vigilancia()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/form-validation.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!--DataTable-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
	    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css"></script>-->
    </body>
</html>
<script type="text/javascript">
    var r = 0;
    $(document).ready(function () {
        $('#tb_vigilancia').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            initComplete: function () {
                this.api()
                    .columns()
                    .every(function () {
                        r++;
                        if ((r == 2) || (r == 3) || (r == 5) || (r == 7)) {
                            var column = this;
                            var select = $('<select class="form-select form-select-sm"><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
        
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.append('<option value="' + d + '">' + d + '</option>');
                                });
                        }                        
                    });
            },
        });
    });
    //***//
    function actualizar_vigilancia() {
        $.ajax({
            //data: parametros,
            url: "actualizar_vigilancia.php",
            type: "POST",
            beforeSend: function(){
                alertify.warning('Actualizando tabla...')
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                alertify.success('Tabla actualizada')
                $("#vigilancia_tbody").empty();
                $("#vigilancia_tbody").append(data);
                setTimeout(function(){
                    atm_vigilancia();
                }, 1000);
            }
        })
    }
    //***//
    function atm_vigilancia() {
        setTimeout(function(){
            actualizar_vigilancia();
        }, 600000);//600000 = 10 minuto    
    }
</script>
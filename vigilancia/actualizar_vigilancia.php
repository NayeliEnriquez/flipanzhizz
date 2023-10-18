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
    include ('../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $yearr = date('Y');
    /*$f_y_h = date('Y-m-d H:m:s');
    $f_y_h = date("Y-m-d H:m:s",strtotime($f_y_h."- 1 days")); */
    $f_y_h_today = date('Y-m-d');
    $f_y_h = date("Y-m-d",strtotime($f_y_h_today."- 1 days")); 
    $k = 0;
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
</script>
<table class="table table-dark table-hover align-middle" id="tb_sol_dia">
    <thead>
        <tr>
            <th scope="col"><center>Folio</center></th>
            <th scope="col">Empleado</th>
            <th scope="col">Tipo de solicitud</th>
            <th scope="col">Fecha de solicitud</th>
            <th scope="col">Estatus</th>
            <th scope="col">Detalles</th>
        </tr>
    </thead>
    <tbody>
<?php
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    include ('../../php/conn.php');

    $sql_vacas = "SELECT * FROM rh_vacaciones WHERE rh_vacaciones.f_solicitud >= '$fecha_now 00:00:00.000' AND rh_vacaciones.f_solicitud <= '$fecha_now 23:59:59.000' AND estatus != '2' ORDER BY rh_vacaciones.f_solicitud ASC";
    $exe_vacas = sqlsrv_query($cnx, $sql_vacas);
    while ($fila_vacas = sqlsrv_fetch_array($exe_vacas, SQLSRV_FETCH_ASSOC)) {
        $folio = $fila_vacas['id'];
        $num_empl = $fila_vacas['id_empleado'];
        $query_name = "SELECT
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
                personnel_employee.emp_code = '$num_empl'
        ";
        $exe_name = sqlsrv_query($conn, $query_name);
        $fila_name = sqlsrv_fetch_array($exe_name, SQLSRV_FETCH_ASSOC);
        $full_name = $num_empl." - ".utf8_encode($fila_name['last_name'])." ".utf8_encode($fila_name['first_name']);

        $f_solicitud = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_vacas['f_solicitud'])))), 5, 16);
        $estatus = $fila_vacas['estatus'];
        switch ($estatus) {
            case '0':
                $estatus_desc = 'En espera';
                break;

            case '1':
                $estatus_desc = 'Aprobado';
                break;

            case '3':
                $estatus_desc = 'Aprobado';
                break;
        }

        echo "
            <tr>
                <td><strong>".$folio."</strong></td>
                <td>".$full_name."</td>
                <td>Vacaciones</td>
                <td>".$f_solicitud." hrs</td>
                <td>".$estatus_desc."</td>
                <td>
                    <center>
                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud_RH' onclick='ver_solicitud_rh(".$folio.", `rh_vacaciones`, `ver`)'>Detalles</button>
                    </center>
                </td>
            </tr>
        ";
    }

    $sql_salida = "SELECT * FROM rh_salida WHERE rh_salida.f_solicitud >= '$fecha_now 00:00:00.000' AND rh_salida.f_solicitud <= '$fecha_now 23:59:59.000' AND estatus != '2' ORDER BY rh_salida.f_solicitud ASC";
    $exe_salida = sqlsrv_query($cnx, $sql_salida);
    while ($fila_salida = sqlsrv_fetch_array($exe_salida, SQLSRV_FETCH_ASSOC)) {
        $folio = $fila_salida['id'];
        $num_empl = $fila_salida['id_empleado'];
        $tipo_ausencia = $fila_salida['tipo_ausencia'];
        $query_name = "SELECT
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
                personnel_employee.emp_code = '$num_empl'
        ";
        $exe_name = sqlsrv_query($conn, $query_name);
        $fila_name = sqlsrv_fetch_array($exe_name, SQLSRV_FETCH_ASSOC);
        $full_name = $num_empl." - ".utf8_encode($fila_name['last_name'])." ".utf8_encode($fila_name['first_name']);

        $f_solicitud = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_salida['f_solicitud'])))), 5, 16);
        $estatus = $fila_salida['estatus'];
        switch ($estatus) {
            case '0':
                $estatus_desc = 'En espera';
                break;

            case '1':
                $estatus_desc = 'Aprobado';
                break;

            case '3':
                $estatus_desc = 'Aprobado';
                break;
        }

        echo "
            <tr>
                <td><strong>".$folio."</strong></td>
                <td>".$full_name."</td>
                <td>Solicitud de ".$tipo_ausencia."</td>
                <td>".$f_solicitud." hrs</td>
                <td>".$estatus_desc."</td>
                <td>
                    <center>
                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud_RH' onclick='ver_solicitud_rh(".$folio.", `rh_salida`, `ver`)'>Detalles</button>
                    </center>
                </td>
            </tr>
        ";
    }
    
    $sql_ausencia = "SELECT * FROM rh_solicitudes WHERE rh_solicitudes.f_solicitud >= '$fecha_now 00:00:00.000' AND rh_solicitudes.f_solicitud <= '$fecha_now 23:59:59.000' AND estatus != '2' ORDER BY rh_solicitudes.f_solicitud ASC";
    $exe_ausencia = sqlsrv_query($cnx, $sql_ausencia);
    while ($fila_ausencia = sqlsrv_fetch_array($exe_ausencia, SQLSRV_FETCH_ASSOC)) {
        $folio = $fila_ausencia['id'];
        $num_empl = $fila_ausencia['id_empleado'];
        $tipo_ausencia = $fila_ausencia['tipo_ausencia'];
        $query_name = "SELECT
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
                personnel_employee.emp_code = '$num_empl'
        ";
        $exe_name = sqlsrv_query($conn, $query_name);
        $fila_name = sqlsrv_fetch_array($exe_name, SQLSRV_FETCH_ASSOC);
        $full_name = $num_empl." - ".utf8_encode($fila_name['last_name'])." ".utf8_encode($fila_name['first_name']);

        $f_solicitud = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_ausencia['f_solicitud'])))), 5, 16);
        $estatus = $fila_ausencia['estatus'];
        switch ($estatus) {
            case '0':
                $estatus_desc = 'En espera';
                break;

            case '1':
                $estatus_desc = 'Aprobado';
                break;

            case '3':
                $estatus_desc = 'Aprobado';
                break;
        }

        echo "
            <tr>
                <td><strong>".$folio."</strong></td>
                <td>".$full_name."</td>
                <td>Solicitud de ".$tipo_ausencia."</td>
                <td>".$f_solicitud." hrs</td>
                <td>".$estatus_desc."</td>
                <td>
                    <center>
                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud_RH' onclick='ver_solicitud_rh(".$folio.", `rh_solicitudes`, `ver`)'>Detalles</button>
                    </center>
                </td>
            </tr>
        ";
    }
?>
    </tbody>
    <tfoot>
        <tr>
            <th scope="col"><center>Folio</center></th>
            <th scope="col">Empleado</th>
            <th scope="col">Tipo de solicitud</th>
            <th scope="col">Fecha de solicitud</th>
            <th scope="col">Estatus</th>
            <th scope="col">Detalles</th>
        </tr>
    </tfoot>
</table>

<div class="modal fade" id="Mdl_ver_solicitud_RH" tabindex="-1" aria-labelledby="Mdl_ver_solicitud_RHLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Mdl_ver_solicitud_RHLabel">Ver solicitud</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response_ver_solicitud_rh"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <div id="response_ver_solicitud_rh_buttons"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_sol_dia').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            "order": [3, 'asc'],
            initComplete: function () {
                this.api().columns().every(function () {
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
                            .data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                    }                        
                });
            },
        });
    });
</script>
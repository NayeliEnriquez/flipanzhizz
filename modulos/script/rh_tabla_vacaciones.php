<table class="table table-dark table-hover align-middle" id="tb_vacas_n">
    <thead>
        <tr>
            <th scope="col"><center>Folio</center></th>
            <th scope="col">Empleado</th>
            <th scope="col">Fecha de solicitud</th>
            <th scope="col">Estatus</th>
            <th scope="col">Detalles</th>
        </tr>
    </thead>
    <tbody>
<?php
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('../../php/conn.php');

    $sql_vacas_n = "SELECT * FROM rh_vacaciones WHERE 
        rh_vacaciones.rh_status = '0' AND rh_vacaciones.estatus = '1' 
        OR 
        rh_vacaciones.rh_status IS NULL AND rh_vacaciones.estatus = '1' 
        OR 
        rh_vacaciones.estatus = '3' AND rh_vacaciones.rh_status IS NULL
        OR
        rh_vacaciones.estatus = '3' AND rh_vacaciones.rh_status = '0'
        ORDER BY f_solicitud ASC";
    $exe_vacas_n = sqlsrv_query($cnx, $sql_vacas_n);
    while ($fila_vacas_n = sqlsrv_fetch_array($exe_vacas_n, SQLSRV_FETCH_ASSOC)) {
        $folio = $fila_vacas_n['id'];
        $num_empl = $fila_vacas_n['id_empleado'];
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

        $f_solicitud = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_vacas_n['f_solicitud'])))), 5, 16);
        $estatus = $fila_vacas_n['estatus'];
        switch ($estatus) {
            case '1':
                $estatus_desc = 'Aprobado por jefe inmediato';
                break;
            
            case '2':
                $estatus_desc = 'Rechazado';
                break;

            case '3':
                $estatus_desc = 'Aprobado por jefe inmediato';
                break;
        }

        echo "
            <tr>
                <td><strong>".$folio."</strong></td>
                <td>".$full_name."</td>
                <td>".$f_solicitud." hrs</td>
                <td>".$estatus_desc."</td>
                <td>
                    <center>
                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud_RH' onclick='ver_solicitud_rh(".$folio.", `rh_vacaciones`, `aprob`)'>Detalles</button>
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
        $('#tb_vacas_n').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }/*,
            "order": [3, 'desc']*/
        });
    });
</script>
<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $inp_num_empl = $_POST['inp_num_empl_v'];

    $query1 = "
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
            personnel_employee.emp_code = '$inp_num_empl'
    ";
    $exe_1 = sqlsrv_query($cnx, $query1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    if ($fila_1 == NULL) {
        //echo "5";
        echo '
        <br>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <div>
                <h4>Sin coincidencias, favor de intentarlo nuevamente o contactar a su administrador de sistema.</h4>
            </div>
        </div>
        ';
        exit();
    }
    $id_bd_1 = $fila_1['id'];
    $first_name_1 = $fila_1['first_name'];
    $last_name_1 = $fila_1['last_name'];
    $department_id_1 = $fila_1['department_id'];
    $dept_name_1 = $fila_1['dept_name'];
    $position_id_1 = $fila_1['position_id'];
    $position_name_1 = $fila_1['position_name'];
    //$hire_date_1 = $fila_1['hire_date'];
    $hire_date_1 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_1['hire_date']))));
    $hire_date_1 = substr($hire_date_1, 5, 10);
    echo '
        <input type="hidden" id="inp_id_empleado" value="'.$inp_num_empl.'">
        <input type="hidden" id="inp_id_depto" value="'.$department_id_1.'">
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="inp_fname" class="form-label">Nombre(s)</label>
                <input type="text" class="form-control" id="inp_fname" value="'.utf8_encode($first_name_1).'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_lname" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="inp_lname" value="'.utf8_encode($last_name_1).'" readonly>
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="inp_depto" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto" value="'.$dept_name_1.'" readonly>
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="inp_pos" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos" value="'.$position_name_1.'" readonly>
            </div>
            <div class="col-md-2 col-sm-12">
                <br>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdl_vacaciones" title="Disponibilidad de vacaciones" onclick="ver_vacaciones_dsp('.$inp_num_empl.')">
                    Detalle vacaciones <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                </button>
                <!--<button type="button" class="btn btn-warning" title="Disponibilidad de vacaciones">
                    Detalle vacaciones <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                </button>-->
            </div>
        </div>
    ';
?>
        <br>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <table border="1" class="table table-striped" id="tb_fechas_v">
                    <thead>
                        <tr>
                            <th><center>#</center></th>
                            <th><center>Fecha</center></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <div class="form-group">
                    <button type="button" class="btn btn-primary mr-2" onclick="agregarFila()">Agregar fecha</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarFila()">Eliminar fecha</button>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_f_regreso" class="form-label">Reincorporandose el dia</label>
                <input id="inp_f_regreso" class="form-control" type="date" required>
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="inp_f_hire" class="form-label">Fecha de ingreso</label>
                <input id="inp_f_hire" class="form-control" type="date" value="<?php echo($hire_date_1); ?>" readonly>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="alert alert-info" role="alert">
                    <center>Coloque su jornada laboral actual</center>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_lun" value="Lunes">
                    <label class="form-check-label" for="chk_lun">Lunes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_mar" value="Martes">
                    <label class="form-check-label" for="chk_mar">Martes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_mie" value="Miercoles">
                    <label class="form-check-label" for="chk_mie">Miercoles</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_jue" value="Jueves">
                    <label class="form-check-label" for="chk_jue">Jueves</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_vie" value="Viernes">
                    <label class="form-check-label" for="chk_vie">Viernes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_sab" value="Sabado">
                    <label class="form-check-label" for="chk_sab">Sabado</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="chk_dom" value="Domingo">
                    <label class="form-check-label" for="chk_dom">Domingo</label>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="inp_hora_i" class="form-label">Horario de entrada</label>
                <input id="inp_hora_i" class="form-control" type="time" required>
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="inp_hora_s" class="form-label">Horario de salida</label>
                <input id="inp_hora_s" class="form-control" type="time" required>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <br>
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs"></textarea>
                    <label for="txt_obs">Observaciones</label>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <br>
                <div id="response_empleado_vaca">
                    <!--<button class="w-100 btn btn-danger btn-lg" id="btn_send" onclick="frm_ausencias_vz(1)">Enviar solicitud(TEST)</button>--><!--***CODIGO ORIGINAL***-->
                    <button class="w-100 btn btn-primary btn-lg" id="btn_send" onclick="frm_vacaciones_new(1)">Enviar solicitud</button>
                </div>
            </div>
        </div>
        <br>
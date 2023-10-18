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
        echo "5";
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
                <input type="text" class="form-control" id="inp_fname" value="'.$first_name_1.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_lname" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="inp_lname" value="'.$last_name_1.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_depto" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto" value="'.$dept_name_1.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_pos" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos" value="'.$position_name_1.'" readonly>
            </div>
        </div>
    ';
?>
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="inp_finicial" class="form-label">Fecha inicial</label>
                <input id="inp_finicial" class="form-control" type="date" value="<?php echo($fecha_now); ?>" min="<?php echo($fecha_now); ?>" required>
                <div class="invalid-feedback">
                    Colocar fecha inicial.
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_ffinal" class="form-label">Fecha final</label>
                <input id="inp_ffinal" class="form-control" type="date" value="<?php echo($fecha_now); ?>" min="<?php echo($fecha_now); ?>" required>
                <div class="invalid-feedback">
                    Colocar fecha final.
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_hora_i" class="form-label">Horario de entrada</label>
                <input id="inp_hora_i" class="form-control" type="time" required>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_hora_s" class="form-label">Horario de salida</label>
                <input id="inp_hora_s" class="form-control" type="time" required>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="inp_f_hire" class="form-label">Fecha de ingreso</label>
                <input id="inp_f_hire" class="form-control" type="date" value="<?php echo($hire_date_1); ?>" readonly>
            </div>
            <div class="col-md-4 col-sm-12">
                <br>
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs"></textarea>
                    <label for="txt_obs">Observaciones</label>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <br>
                <button class="w-100 btn btn-primary btn-lg" id="btn_send" onclick="frm_ausencias_v(1)">Enviar solicitud</button>
            </div>
        </div>
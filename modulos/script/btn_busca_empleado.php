<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $inp_num_empl = $_POST['inp_num_empl_in'];

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
    $exe_1 = sqlsrv_query($conn, $query1);
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
        <label for="inp_fini" class="form-label">Fecha inicial</label>
        <input type="date" class="form-control" id="inp_fini" value="<?php echo($fecha_now); ?>">
    </div>
    <div class="col-md-3 col-sm-12">
        <label for="inp_ffin" class="form-label">Fecha final</label>
        <input type="date" class="form-control" id="inp_ffin" required>
    </div>
    <div class="col-md-2 col-sm-12">
        <label for="inp_tdias" class="form-label">D&iacute;as totales</label>
        <input type="number" min="1" class="form-control" id="inp_tdias" required>
    </div>
    <div class="col-md-4 col-sm-12">
        <label for="inp_file" class="form-label">Evidencia</label>
        <input type="file" class="form-control" id="inp_file" accept=".pdf">
        <p style="font-size: 9px;"><b>(Opcional, archivo maximo de 25MB en formato PDF)</b></p>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-sm-12">
        <div class="form-floating">
            <textarea class="form-control" placeholder="Destino y motivo" id="txt_obs"></textarea>
            <label for="txt_obs">Observaciones o comentarios</label>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <div id="response_emp_inc_div">
            <button type="button" class="w-100 btn btn-primary btn-lg" id="response_emp_inc_btn" onclick="btn_guarda_inc()">Guardar</button>
        </div>
    </div>
</div>
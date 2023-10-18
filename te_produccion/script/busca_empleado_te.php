<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $inp_num_empl = $_POST['inp_num_empl_te'];

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
    <div class="col-md-4 col-sm-12">
        <label for="inp_fecha_textra" class="form-label">Fecha del TE</label>
        <input id="inp_fecha_textra" class="form-control" type="date" value="<?php echo($fecha_now); ?>" required>
    </div>
    <div class="col-md-4 col-sm-12">
        <label for="slc_tot_te" class="form-label">Total de horas extra</label>
        <select id="slc_tot_te" class="form-select" aria-label="Total de horas extra">
            <option value="1" selected>1 hora</option>
            <?php
                for ($i=2; $i < 13; $i++) { 
                    echo '<option value="'.$i.'">'.$i.' horas</option>';
                }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-sm-12">
        <label for="inp_n_orden" class="form-label">Numero de orden</label>
        <input id="inp_n_orden" class="form-control" type="text" required>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-9 col-sm-12">
    </div>
    <div class="col-md-3 col-sm-12">
        <br>
        <button class="w-100 btn btn-primary btn-lg" id="btn_send_te" onclick="frm_tiempo_extra()">Enviar registro</button>
        <div id="response_empleado_te">
        </div>
    </div>
</div>
<div id="response_te_send">
</div>
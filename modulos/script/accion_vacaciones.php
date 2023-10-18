<?php
    /*while($post = each($_POST)){
		echo $post[0]." = ".$post[1]."||";
	}*/
    date_default_timezone_set("America/Mazatlan");
    session_start();
    include ('../../php/conn.php');
    $name_a_session = $_SESSION['name_a'];
    $num_empleado_a_session = $_SESSION['num_empleado_a'];
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $year_current = date('Y');

    $mov = $_POST['mov'];
    $data_a = $_POST['data_a'];
    $data_b = $_POST['data_b'];

    switch ($mov) {
        case '1':
            $sql_a = "SELECT id_empleado FROM $data_b WHERE id = '$data_a'";
            $exe_a = sqlsrv_query($cnx, $sql_a);
            $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
            $num_empleado = $fila_a['id_empleado'];

            break;
        
        default:
            $num_empleado = $data_a;
            break;
    }

    $query1 = "SELECT
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
            personnel_employee.emp_code = '$num_empleado'
    ";
    $exe_1 = sqlsrv_query($conn, $query1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    $id_bd_1 = $fila_1['id'];
    $first_name_1 = $fila_1['first_name'];
    $last_name_1 = $fila_1['last_name'];
    $department_id_1 = $fila_1['department_id'];
    $dept_name_1 = $fila_1['dept_name'];
    $position_id_1 = $fila_1['position_id'];
    $position_name_1 = $fila_1['position_name'];
    $hire_date_1 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_1['hire_date'])))), 5, 10);

    $sql_b = "SELECT * FROM rh_employee_gen WHERE CLAVE = '$num_empleado'";
    $exe_b = sqlsrv_query($cnx, $sql_b);
    $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
    if ($fila_b != NULL) {
        $year_current_menos = date("Y",strtotime($year_current."- 1 year"));
        $sql_c = "SELECT y_$year_current_menos, y_$year_current FROM rh_employee_gen WHERE CLAVE = '$num_empleado'";
        $exe_c = sqlsrv_query($cnx, $sql_c);
        $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
        $year_a = $fila_c['y_'.$year_current_menos];
        if (is_null($year_a)) {
            $year_a = 0;
        }
        $year_b = $fila_c['y_'.$year_current];
        if (is_null($year_b)) {
            $year_b = 0;
        }
    } else {
        echo "<br>SIN INFO";
    }

    echo '
        <input type="hidden" id="inp_id_empleado_rh" value="'.$num_empleado.'">
        <input type="hidden" id="inp_id_depto_rh" value="'.$department_id_1.'">
        <br>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label for="inp_fname" class="form-label">Nombre(s)</label>
                <input type="text" class="form-control" id="inp_fname" value="'.utf8_encode($first_name_1).'" readonly>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="inp_lname" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="inp_lname" value="'.utf8_encode($last_name_1).'" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <label for="inp_depto" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto" value="'.$dept_name_1.'" readonly>
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="inp_pos" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos" value="'.$position_name_1.'" readonly>
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="inp_pos" class="form-label">Fecha contrataci&oacute;n</label>
                <input type="date" class="form-control" id="inp_pos" value="'.$hire_date_1.'" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="ciclo_a" class="form-label">Ciclo '.$year_current_menos.'</label>
                <input type="number" min="0" class="form-control" id="ciclo_a" value="'.$year_a.'" style="background-color: #9FEAE5;">
                <input type="hidden" id="year_ciclo_a" value="'.$year_current_menos.'">
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="ciclo_b" class="form-label">Ciclo '.$year_current.'</label>
                <input type="number" min="0" class="form-control" id="ciclo_b" value="'.$year_b.'" style="background-color: #9FEAE5;">
                <input type="hidden" id="year_ciclo_b" value="'.$year_current.'">
            </div>
        </div>
    ';
?>
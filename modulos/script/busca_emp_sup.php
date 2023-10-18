<?php
    include ('../../php/conn.php');
    $inp_num_empl_sup = $_POST['inp_num_empl_sup'];

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
            personnel_employee.emp_code = '$inp_num_empl_sup'
    ";
    $exe_1 = sqlsrv_query($conn, $query1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    if ($fila_1 == NULL) {
        echo "5";
        exit();
    }
    $first_name_1 = $fila_1['first_name'];
    $last_name_1 = $fila_1['last_name'];
    $dept_name_1 = $fila_1['dept_name'];
    $position_name_1 = $fila_1['position_name'];
    echo '
        <br>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label for="inp_fname_sup" class="form-label">Nombre(s)</label>
                <input type="text" class="form-control" id="inp_fname_sup" value="'.$first_name_1.'" readonly>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="inp_lname_sup" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="inp_lname_sup" value="'.$last_name_1.'" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <label for="inp_depto_sup" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto_sup" value="'.$dept_name_1.'" readonly>
            </div>
            <div class="col-md-6 col-sm-12">
                <label for="inp_pos_sup" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos_sup" value="'.$position_name_1.'" readonly>
            </div>
        </div>
    ';
?>
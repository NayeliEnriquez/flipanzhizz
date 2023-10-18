<?php
    include ('../../php/conn.php');
    $emp_code = $_POST['emp_code'];

    $query_a = "SELECT
            pe.first_name, pe.last_name, pe.emp_code,
            pe.department_id, pe.position_id, pe.hire_date,
            pd.dept_name, ps.position_name, pd.emp_code_charge, pd.parent_dept_id
        FROM
            dbo.personnel_employee pe
        INNER JOIN
            dbo.personnel_department pd
        ON
            pe.department_id = pd.id
        INNER JOIN
            dbo.personnel_position ps
        ON
            pe.position_id = ps.id
        WHERE
            pe.emp_code = '$emp_code'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $first_name_a = utf8_encode($fila_a['first_name']);
    $last_name_a = utf8_encode($fila_a['last_name']);
    $emp_code_a = $fila_a['emp_code'];
    $hire_date_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date']))));
    $hire_date_a = substr($hire_date_a, 5, 10);
    $dept_name_a = $fila_a['dept_name'];
    $position_name_a = $fila_a['position_name'];

    $query_gen = "SELECT NOMINA AS pago, PUESTO AS puesto_novag FROM dbo.rh_employee_gen WHERE CLAVE = '$emp_code_a'";
    $exe_gen = sqlsrv_query($cnx, $query_gen);
    $fila_gen = sqlsrv_fetch_array($exe_gen, SQLSRV_FETCH_ASSOC);
    if ($fila_gen == null) {
        $pago_a = 'No info';
        $puesto_novag_a = 'No info';
        $full_name = $last_name_a." ".$first_name_a;
        $sql_inserta = "INSERT INTO [dbo].[rh_employee_gen] ([CLAVE], [NOMBRE COMPLETO], [PUESTO], [DEPARTAMENTO]) VALUES ('$emp_code_a', '$full_name', '$position_name_a', '$dept_name_a')";
        sqlsrv_query($cnx, $sql_inserta);
    } else {
        $pago_a = $fila_gen['pago'];
        if ((empty($pago_a)) || ($pago_a == null)) {
            $pago_a = 'No info';
        }
        $puesto_novag_a = $fila_gen['puesto_novag'];
        if ((empty($puesto_novag_a)) || ($puesto_novag_a == null)) {
            $puesto_novag_a = 'No info';
        }
    }
    $emp_code_charge_a = $fila_a['emp_code_charge'];
    $parent_dept_id_a = $fila_a['parent_dept_id'];

    $sql_A = "SELECT * FROM rh_jefe_directo WHERE num_emp = '$emp_code_a'";
    $exe_A = sqlsrv_query($cnx, $sql_A);
    $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
    $num_emp = $fila_A['num_emp'];
    $num_emp_boss = $fila_A['num_emp_boss'];

    if ($num_emp == null) {
        if (empty($emp_code_charge_a)) {
            if (!empty($parent_dept_id_a)) {
                $query_c = "
                    SELECT 
                        pd.emp_code_charge, pe.first_name, pe.last_name
                    FROM 
                        personnel_department pd
                    INNER JOIN
                        dbo.personnel_employee pe
                    ON
                        pd.emp_code_charge = pe.emp_code
                    WHERE 
                        pd.id = '$parent_dept_id_a'";
                $exe_c = sqlsrv_query($cnx, $query_c);
                $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
                $first_name_jefe = utf8_encode($fila_c['first_name']);
                $last_name_jefe = utf8_encode($fila_c['last_name']);
                $emp_code_charge_jefe = $fila_c['emp_code_charge'];
                if (!empty($last_name_jefe)) {
                    $desc_jefe = $emp_code_charge_jefe." - ".$last_name_jefe." ".$first_name_jefe;
                } else {
                    $desc_jefe = "No info";
                }
            }else{
                $desc_jefe = "No info";
            }
        } else {
            $query_b = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code LIKE '$emp_code_charge_a'";
            $exe_b = sqlsrv_query($cnx, $query_b);
            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
            $first_name_jefe = $fila_b['first_name'];
            $last_name_jefe = $fila_b['last_name'];
            if (!empty($last_name_jefe)) {
                $desc_jefe = $emp_code_charge_a." - ".$last_name_jefe." ".$first_name_jefe;
            } else {
                $desc_jefe = "No info";
            }
        }
    }else{
        $query_B = "SELECT
                pe.first_name, pe.last_name, pe.emp_code
            FROM
                dbo.personnel_employee pe
            WHERE
                pe.emp_code = '$num_emp_boss'";
        $exe_B = sqlsrv_query($cnx, $query_B);
        $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
        $first_name_boss = utf8_encode($fila_B['first_name']);
        $last_name_boss = utf8_encode($fila_B['last_name']);

        if ($first_name_boss == null) {
            $query_C = "SELECT * FROM rh_directivos WHERE CLAVE = '$num_emp_boss'";
            $exe_B = sqlsrv_query($cnx, $query_B);
            $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
            $first_name_boss = utf8_encode($fila_B['NOMBRE COMPLETO']);

            $desc_jefe = $num_emp_boss." - ".$first_name_boss;
        }else{
            $desc_jefe = $num_emp_boss." - ".$last_name_boss." ".$first_name_boss;
        }
    }
?>
<br>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <label for="inp_fname" class="form-label">Nombre(s)</label>
        <input type="text" class="form-control" id="inp_fname" value="<?php echo(utf8_encode($first_name_a)); ?>" readonly>
        <input type="hidden" id="inp_num_empl_cat" value="<?php echo($emp_code_a); ?>">
    </div>
    <div class="col-md-6 col-sm-12">
        <label for="inp_lname" class="form-label">Apellido(s)</label>
        <input type="text" class="form-control" id="inp_lname" value="<?php echo(utf8_encode($last_name_a)); ?>" readonly>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <label for="inp_depto" class="form-label">Departamento</label>
        <input type="text" class="form-control" id="inp_depto" value="<?php echo($dept_name_a); ?>" readonly>
    </div>
    <div class="col-md-6 col-sm-12">
        <label for="inp_pos" class="form-label">Posici&oacute;n</label>
        <input type="text" class="form-control" id="inp_pos" value="<?php echo($position_name_a); ?>" readonly>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <label for="inp_boss_yet" class="form-label">Jefe inmediato</label>
        <input type="text" class="form-control" id="inp_boss_yet" value="<?php echo($desc_jefe); ?>" readonly>
    </div>
    <div class="col-md-6 col-sm-12">
        <label for="inp_num_empl_boss" class="form-label">Nuevo jefe inmediato</label>
        <input class="form-control" list="datalistOptions" id="inp_num_empl_boss" placeholder="Busqueda...">
        <datalist id="datalistOptions">
            <?php
                $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                $exe_1 = sqlsrv_query($conn, $query1);
                while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                    $emp_code_1 = $fila_1['emp_code'];
                    $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                    $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                    echo '
                        <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                    ';
                }
                $query2 = "SELECT [NOMBRE COMPLETO], [CLAVE] FROM [rh_novag_system].[dbo].[rh_directivos]";
                $exe_1 = sqlsrv_query($conn, $query1);
                while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                    $emp_code_1 = $fila_1['CLAVE'];
                    $first_name_1 = trim(utf8_encode($fila_1['NOMBRE COMPLETO']));
                    echo '
                        <option value="'.$emp_code_1.'">'.$first_name_1.'</option>
                    ';
                }
            ?>
        </datalist>
    </div>
</div>
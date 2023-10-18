<?php
    include ('../../php/conn.php');
    $id_depto = $_POST['id_depto'];
    $query_a = "SELECT * FROM personnel_department WHERE id = '$id_depto'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $dept_code_a = $fila_a['dept_code'];
    $dept_name_a = $fila_a['dept_name'];
    $parent_dept_id_a = $fila_a['parent_dept_id'];
    if (empty($parent_dept_id_a)) {
        $v_pdi_desc = 'No aplica';
    }else{
        $query_b = "SELECT dept_name FROM personnel_department WHERE id = '$parent_dept_id_a'";
        $exe_b = sqlsrv_query($cnx, $query_b);
        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
        $v_pdi_desc = $fila_b['dept_name'];
    }
    $emp_code_charge_a = $fila_a['emp_code_charge'];
    if ($emp_code_charge_a == '0') {
        $emp_code_charge_a = '';
    }
    $html_emp = '
        <div class="mb-3">
            <label for="inp_code_emp" class="form-label">Encargado del depto</label>
            <input class="form-control" list="datalistOptions" id="inp_code_emp" placeholder="Sin empleado asignado" value="'.$emp_code_charge_a.'">
            <datalist id="datalistOptions">';

        if (empty($emp_code_charge_a)) {
            $query_e = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
            $exe_e = sqlsrv_query($cnx, $query_e);
            while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                $emp_code_e = $fila_e['emp_code'];
                $first_name_e = trim(utf8_encode($fila_e['first_name']));
                $last_name_e = trim(utf8_encode($fila_e['last_name']));
                $html_emp .= '
                    <option value="'.$emp_code_e.'">'.$last_name_e.' '.$first_name_e.'</option>
                ';
            }
        }else{
            /*$query_c = "SELECT first_name, last_name, emp_code FROM personnel_employee WHERE emp_code LIKE '$emp_code_charge_a'";
            $exe_c = sqlsrv_query($cnx, $query_c);
            $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
            $first_name_c = $fila_c['first_name'];
            $last_name_c = $fila_c['last_name'];
            $v_ecc_desc = $first_name_c." ".$last_name_c;

            $html_emp .= '
                <option value="'.$emp_code_charge_a.'" selected>'.$last_name_c.' '.$first_name_c.'</option>
            ';*/
            $query_e = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
            $exe_e = sqlsrv_query($cnx, $query_e);
            while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                $emp_code_e = $fila_e['emp_code'];
                $first_name_e = trim(utf8_encode($fila_e['first_name']));
                $last_name_e = trim(utf8_encode($fila_e['last_name']));
                $html_emp .= '
                    <option value="'.$emp_code_e.'">'.$last_name_e.' '.$first_name_e.'</option>
                ';
            }
        }
    $html_emp .= '
            </datalist>
        </div>
    ';
    $sub_emp_code_charge_a = $fila_a['sub_emp_code_charge'];
    if ($sub_emp_code_charge_a == '0') {
        $sub_emp_code_charge_a = '';
    }
    $html_emp_sub = '
        <div class="mb-3">
            <label for="inp_code_emp_sub" class="form-label">Sub encargado del depto</label>
            <input class="form-control" list="datalistOptions" id="inp_code_emp_sub" placeholder="Sin empleado asignado" value="'.$sub_emp_code_charge_a.'">
            <datalist id="datalistOptions">';
    if (empty($sub_emp_code_charge_a)) {
        $query_e = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
        $exe_e = sqlsrv_query($cnx, $query_e);
        while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
            $emp_code_e = $fila_e['emp_code'];
            $first_name_e = trim(utf8_encode($fila_e['first_name']));
            $last_name_e = trim(utf8_encode($fila_e['last_name']));
            $html_emp_sub .= '
                <option value="'.$emp_code_e.'">'.$last_name_e.' '.$first_name_e.'</option>
            ';
        }
    }else{
        /*$query_d = "SELECT first_name, last_name, emp_code FROM personnel_employee WHERE emp_code LIKE '$sub_emp_code_charge_a'";
        $exe_d = sqlsrv_query($cnx, $query_d);
        $fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC);
        $first_name_d = $fila_d['first_name'];
        $last_name_d = $fila_d['last_name'];
        $v_ecc_desc = $first_name_d." ".$last_name_d;

        $html_emp_sub .= '
            <option value="'.$sub_emp_code_charge_a.'" selected>'.$last_name_d.' '.$first_name_d.'</option>
        ';*/
        $query_e = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
        $exe_e = sqlsrv_query($cnx, $query_e);
        while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
            $emp_code_e = $fila_e['emp_code'];
            $first_name_e = trim(utf8_encode($fila_e['first_name']));
            $last_name_e = trim(utf8_encode($fila_e['last_name']));
            $html_emp_sub .= '
                <option value="'.$emp_code_e.'">'.$last_name_e.' '.$first_name_e.'</option>
            ';
        }
    }
    $html_emp .= '
            </datalist>
        </div>
    ';
?>
<div class="mb-3">
    <input type="hidden" id="id_depto" value="<?php echo $id_depto; ?>">
    <label for="inp_name_dpt" class="form-label">Departamento</label>
    <input class="form-control" type="text" id="inp_name_dpt" value="<?php echo $dept_name_a; ?>" readonly>
</div>
<div class="mb-3">
    <label for="inp_name_dpt_parent" class="form-label">Departamento principal</label>
    <input class="form-control" type="text" id="inp_name_dpt_parent" value="<?php echo $v_pdi_desc; ?>" readonly>
</div>
<?php
    echo($html_emp);
    echo($html_emp_sub);
?>

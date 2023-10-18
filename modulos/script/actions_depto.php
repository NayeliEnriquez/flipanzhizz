<?php
    include ('../../php/conn.php');
    $tipo_mov = $_POST['tipo_mov'];
    $id_depto = $_POST['id_depto'];
    $inp_code_emp = $_POST['inp_code_emp'];
    $inp_code_emp_sub = $_POST['inp_code_emp_sub'];

    switch ($tipo_mov) {
        case '1':
            $query_a = "UPDATE personnel_department SET emp_code_charge = '$inp_code_emp', sub_emp_code_charge = '$inp_code_emp_sub' WHERE id = '$id_depto'";
            if ($exe_a = sqlsrv_query($cnx, $query_a)) {
                echo 1;
            } else {
                echo 2;
            }
            
            break;
        
        default:
            # code...
            break;
    }
?>
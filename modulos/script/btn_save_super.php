<?php
    include ('../../php/conn.php');
    session_start();
    $sesion_num_emp = $_SESSION['num_empleado_a'];
    $inp_num_empl_sup = $_POST['inp_num_empl_sup'];
    $inp_fname_sup = $_POST['inp_fname_sup'];
    $inp_lname_sup = $_POST['inp_lname_sup'];
    $inp_depto_sup = $_POST['inp_depto_sup'];
    $inp_pos_sup = $_POST['inp_pos_sup'];

    $sql_revisa = "SELECT COUNT(id) AS sup_emp FROM rh_supers_emps WHERE num_emp_asign = '$inp_num_empl_sup'";
    $exe_revisa = sqlsrv_query($cnx, $sql_revisa);
    $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
    $sup_emp = $fila_revisa['sup_emp'];
    if ($sup_emp > 0) {
        $sql_revisa2 = "SELECT * FROM rh_supers_emps WHERE num_emp_asign = '$inp_num_empl_sup'";
        $exe_revisa2 = sqlsrv_query($cnx, $sql_revisa2);
        $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
        $num_emp_asign2 = $fila_revisa2['num_emp_asign'];
        $id_super2 = $fila_revisa2['id_super'];
        if ($id_super2 != $sesion_num_emp) {
            $query1 = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code = '$id_super2'";
            $exe_1 = sqlsrv_query($conn, $query1);
            $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);

            echo "1|".$fila_1['last_name']." ".$fila_1['first_name'];
        } else {
            echo "2|";
        }
        
    } else {
        $sql_inserta = "INSERT INTO rh_supers_emps (id_super, num_emp_asign) VALUES ('$sesion_num_emp', '$inp_num_empl_sup')";
        sqlsrv_query($cnx, $sql_inserta);
        echo "3|";
    }
    
?>
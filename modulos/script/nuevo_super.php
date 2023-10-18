<?php
    include ('../../php/conn.php');

    $inp_num_empl_sup = $_POST['inp_num_empl_sup'];
    $inp_fname_sup = $_POST['inp_fname_sup'];
    $inp_lname_sup = $_POST['inp_lname_sup'];

    $sql_a = "SELECT COUNT(id) AS existe FROM [rh_novag_system].[dbo].[rh_supervisores] WHERE num_emp = '$inp_num_empl_sup'";
    $exe_a = sqlsrv_query($cnx, $sql_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $v_existe = $fila_a['existe'];

    if ($v_existe > 0) {
        echo 1;
    } else {
        $sql_b = "INSERT INTO [dbo].[rh_supervisores] ([num_emp],[full_name],[id_horario]) VALUES ('$inp_num_empl_sup','$inp_lname_sup $inp_fname_sup','0')";
        sqlsrv_query($cnx, $sql_b);
        echo 2;
    }
?>
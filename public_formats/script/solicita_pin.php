<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $num_empl = $_POST['num_empl'];
    $v_pin = $_POST['v_pin'];

    $sql_a = "SELECT COUNT(id) AS revisa FROM [rh_novag_system].[dbo].[rh_emp_pin] WHERE rh_emp_pin.num_emp = '$num_empl' AND rh_emp_pin.pin = '$v_pin'";
    $exe_a = sqlsrv_query($cnx, $sql_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $revisa = $fila_a['revisa'];

    if ($revisa == 0) {
        echo 1;//***PIN INCORRECTO
    } else {
        echo 2;//***CORRECTO
    }
    
?>
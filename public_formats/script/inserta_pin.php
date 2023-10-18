<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $num_empl = $_POST['num_empl'];
    $v_pin = $_POST['v_pin'];

    if (is_numeric($v_pin)) {
        $sql_a = "INSERT INTO [dbo].[rh_emp_pin] ([num_emp], [pin]) VALUES ('$num_empl', '$v_pin')";
        $exe_a = sqlsrv_query($cnx, $sql_a);

        echo 2;
    } else {
        echo 1;
    }
    
?>
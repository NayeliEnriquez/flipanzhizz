<?php
    include ('../../php/conn.php');

    $num_empl = $_POST['num_empl'];

    $sql_del = "DELETE FROM rh_emp_pin WHERE num_emp = '$num_empl'";
    if (sqlsrv_query($cnx, $sql_del)) {
        echo "1";
    }else{
        echo "2";
    }
?>
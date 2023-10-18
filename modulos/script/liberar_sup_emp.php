<?php
    include ('../../php/conn.php');
    $emp_code = $_POST['emp_code'];

    $sql_delete = "DELETE FROM rh_supers_emps WHERE num_emp_asign = '$emp_code'";
    sqlsrv_query($cnx, $sql_delete);

    echo "1";
?>
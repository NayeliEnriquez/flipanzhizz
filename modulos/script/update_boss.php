<?php
    include ('../../php/conn.php');
    $inp_num_empl_cat = $_POST['inp_num_empl_cat'];
    $inp_num_empl_boss = $_POST['inp_num_empl_boss'];

    $sql_A = "SELECT * FROM rh_jefe_directo WHERE num_emp = '$inp_num_empl_cat'";
    $exe_A = sqlsrv_query($cnx, $sql_A);
    $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
    $num_emp = $fila_A['num_emp'];
    $num_emp_boss = $fila_A['num_emp_boss'];

    if ($num_emp == null) {
        $sql_B = "INSERT INTO [dbo].[rh_jefe_directo] ([num_emp], [num_emp_boss]) VALUES ('$inp_num_empl_cat', '$inp_num_empl_boss')";
    }else{
        echo $sql_B = "UPDATE [dbo].[rh_jefe_directo] SET [num_emp_boss] = '$inp_num_empl_boss' WHERE [num_emp] = '$num_emp'";
    }

    sqlsrv_query($cnx, $sql_B);

?>
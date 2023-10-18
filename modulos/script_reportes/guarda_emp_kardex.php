<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $inp_num_empl_k = $_POST['inp_num_empl_k'];
    $slc_kardex_v = $_POST['slc_kardex_v'];

    $query1 = "SELECT COUNT(CLAVE) AS coincidencias FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$inp_num_empl_k' AND kardex_type = '$slc_kardex_v'";
    $exe_1 = sqlsrv_query($cnx, $query1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    $coincidencias = $fila_1['coincidencias'];
    if ($coincidencias > 0) {
        echo 1;//***Ya esta registrado
    }else{
        $query2 = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET kardex_type = '$slc_kardex_v' WHERE CLAVE = '$inp_num_empl_k'";
        if (sqlsrv_query($cnx, $query2)) {
            echo 2;
        }
    }
?>
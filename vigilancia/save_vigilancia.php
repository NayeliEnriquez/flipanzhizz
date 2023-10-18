<?php
    include ('../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_full = date('Y-m-d H:i:s');

    $inp_id_bd_s = $_POST['inp_id_bd_s'];
    $inp_texto = $_POST['inp_texto'];
    $inp_name_v = $_POST['inp_name_v'];

    $query_insert = "INSERT INTO [dbo].[rh_vigilancia]
            (id_salida, texto_vigilancia, name_oficial, fecha_insert)
        VALUES
            ('$inp_id_bd_s', '$inp_texto', '$inp_name_v', '$fecha_full')";

    if (sqlsrv_query($cnx, $query_insert)) {
        echo 1;
    } else {
        echo 2;
    }
    
?>
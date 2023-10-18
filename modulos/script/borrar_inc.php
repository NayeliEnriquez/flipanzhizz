<?php
    include ('../../php/conn.php');
    $id_ausencia = $_POST['id_ausencia'];

    $sql_a = "SELECT abstractexception_ptr_id FROM rh_solicitudes WHERE rh_solicitudes.id= '$id_ausencia'";
    $exe_a = sqlsrv_query($cnx, $sql_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $abstractexception_ptr_id_a = $fila_a['abstractexception_ptr_id'];

    $sql_b = "DELETE FROM [zkbiotime].[dbo].[att_leave] WHERE [abstractexception_ptr_id] = '$abstractexception_ptr_id_a'";
    sqlsrv_query($conn, $sql_b);
    $sql_c = "DELETE FROM [zkbiotime].[dbo].[workflow_abstractexception]  WHERE id = '$abstractexception_ptr_id_a'";
    sqlsrv_query($conn, $sql_c);

    $sql_d = "UPDATE rh_solicitudes SET rh_solicitudes.estatus = '2' WHERE rh_solicitudes.id = '$id_ausencia'";
    sqlsrv_query($cnx, $sql_d);
    
    echo(1);
?>
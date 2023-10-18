<?php
    include ('../../php/conn.php');
    $id_zk = $_POST['id_zk'];
    $v_pregunta = $_POST['v_pregunta'];
    
    $sql_update = "UPDATE [zkbiotime].[dbo].[personnel_employee] SET [email] = '$v_pregunta' WHERE [id] = '$id_zk'";
    if (sqlsrv_query($conn, $sql_update)) {
        echo 1;
    }
?>
<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."<br>";
	}*/
    session_destroy();
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('conn.php');
    $inp_user_rh = $_POST['inp_user_rh'];
    $inp_rh_Password = $_POST['inp_rh_Password'];


    $query_a = "SELECT TOP(1) * FROM rh_user_sys WHERE email = '$inp_user_rh' COLLATE SQL_Latin1_General_CP1_CS_AS AND password = '$inp_rh_Password' COLLATE SQL_Latin1_General_CP1_CS_AS";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $id_bd_a = $fila_a['id'];
    $name_a = $fila_a['name'];
    $email_a = $fila_a['email'];
    $permisos_a = $fila_a['permisos'];
    $num_empleado_a = $fila_a['num_empleado'];
    $updated_at_a = $fila_a['updated_at'];

    if (!empty($name_a)) {
        if ($updated_at_a == null) {
            echo "3|".$id_bd_a;
        }else{
            session_start();
            $_SESSION['name_a'] = $name_a;
            $_SESSION['email_a'] = $email_a;
            $_SESSION['permisos_a'] = $permisos_a;
            $_SESSION['num_empleado_a'] = $num_empleado_a;
            echo "1|".$id_bd_a;
        }
    }else{
        $query_b = "SELECT TOP(1) * FROM dbo.rh_dir_gen WHERE emp_code = '$inp_user_rh' AND password = '$inp_rh_Password'";
        $exe_b = sqlsrv_query($cnx, $query_b);
        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
        $id_bd_a = $fila_b['id'];
        $name_a = $fila_b['name_full'];
        $email_a = '';
        $permisos_a = '3|';
        $num_empleado_a = $inp_user_rh;
        if (!empty($name_a)) {
            session_start();
            $_SESSION['name_a'] = $name_a;
            $_SESSION['email_a'] = $email_a;
            $_SESSION['permisos_a'] = $permisos_a;
            $_SESSION['num_empleado_a'] = $num_empleado_a;
            echo "1|".$id_bd_a;
        }else{
            echo "2|".$id_bd_a;
        }
    }
?>
<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."||";
	}*/
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('../../php/conn.php');

    $num_empleado = $_POST['inp_namefull'];
    $inp_email = $_POST['inp_email'];
    $chk_p1 = $_POST['chk_p1'];
    $chk_p2 = $_POST['chk_p2'];
    $chk_p3 = $_POST['chk_p3'];
    $chk_p4 = $_POST['chk_p4'];
    $chk_p5 = $_POST['chk_p5'];
    $chk_p6 = $_POST['chk_p6'];
    $chk_p7 = $_POST['chk_p7'];
    $str_permisos = '';

    $query_a = "SELECT COUNT(id) AS cont_tot FROM rh_user_sys WHERE email = '$inp_email' OR num_empleado = '$num_empleado'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $cont_tot_a = $fila_a['cont_tot'];
    if ($cont_tot_a > 0) {
        echo "2";
    }else{
        $str_permisos .= ($chk_p1 == 'true') ? "1|" : "";
        $str_permisos .= ($chk_p2 == 'true') ? "2|" : "";
        $str_permisos .= ($chk_p3 == 'true') ? "3|" : "";
        $str_permisos .= ($chk_p4 == 'true') ? "4|" : "";
        $str_permisos .= ($chk_p5 == 'true') ? "5|" : "";
        $str_permisos .= ($chk_p6 == 'true') ? "6|" : "";
        $str_permisos .= ($chk_p7 == 'true') ? "7|" : "";

        $query_b = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code = '$num_empleado'";
        $exe_b = sqlsrv_query($cnx, $query_b);
        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
        $first_name_1 = trim(utf8_encode($fila_b['first_name']));
        $last_name_1 = trim(utf8_encode($fila_b['last_name']));

        $full_name = $first_name_1." ".$last_name_1;


        $query_c = "
            INSERT INTO dbo.rh_user_sys(
                name, email, email_verified_at,
                password, created_at, permisos,
                num_empleado)
            VALUES
                ('$full_name', '$inp_email', '$inp_email',
                'abc123', '$fecha_hora_now', '$str_permisos',
                '$num_empleado')";
        
        sqlsrv_query($cnx, $query_c);
        echo "1";
    }
?>
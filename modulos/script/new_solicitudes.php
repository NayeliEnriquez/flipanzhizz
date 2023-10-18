<?php
    include ('../../php/conn.php');
    session_start();
    /*while($post = each($_SESSION)){
		echo $post[0]."=".$post[1]."||";
	}*/
    $num_empleado_session = $_SESSION['num_empleado_a'];

    $cadena_dg = "7050, 2382, 7046, 2729, 7048, 7051, 7035, 7042, 7049, 2983, 2822";

    $val_dg = strpos($cadena_dg, $num_empleado_session);

    if ($val_dg !== false) {
        $query_c = "SELECT COUNT(id) AS tot_solicitudes FROM rh_solicitudes WHERE estatus = '3'";
        $exe_c = sqlsrv_query($cnx, $query_c);
        $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
        $tot_solicitudes = $fila_c['tot_solicitudes'];

        $query_d = "SELECT COUNT(id) AS tot_salida FROM rh_salida WHERE estatus = '3'";
        $exe_d = sqlsrv_query($cnx, $query_d);
        $fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC);
        $tot_salida = $fila_d['tot_salida'];

        $query_e = "SELECT COUNT(id) AS tot_vacaciones FROM rh_vacaciones WHERE estatus = '3'";
        $exe_e = sqlsrv_query($cnx, $query_e);
        $fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC);
        $tot_vacaciones = $fila_e['tot_vacaciones'];
    }else{
        $sql_super = "SELECT COUNT(id) AS super_conf FROM rh_supervisores WHERE num_emp = '$num_empleado_session'";
        $exe_super = sqlsrv_query($cnx, $sql_super);
        $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
        $super_conf = $fila_super['super_conf'];
        if ($super_conf > 0) {
            $in_emps = '';
            $sql_super_emp = "SELECT [id], [id_super], [num_emp_asign] FROM [rh_novag_system].[dbo].[rh_supers_emps] WHERE id_super = '$num_empleado_session'";
            $exe_super_emp = sqlsrv_query($cnx, $sql_super_emp);
            while ($fila_super_emp = sqlsrv_fetch_array($exe_super_emp, SQLSRV_FETCH_ASSOC)) {
                $in_emps .= "'".$fila_super_emp['num_emp_asign']."', ";
            }

            if (empty($in_emps)) {
               $query_search2 = "SELECT * FROM rh_jefe_directo WHERE num_emp_boss = '$num_empleado_session'";
               $exe_search2 = sqlsrv_query($cnx, $query_search2);
               while ($fila_search2 = sqlsrv_fetch_array($exe_search2, SQLSRV_FETCH_ASSOC)) {
                  $in_emps .= "'".$fila_search2['num_emp']."', ";
               }
            }

            $in_emps = substr($in_emps, 0, -2);

            $query_c = "SELECT COUNT(id) AS tot_solicitudes FROM rh_solicitudes WHERE id_empleado IN (".$in_emps.") AND estatus = '0'";
            $exe_c = sqlsrv_query($cnx, $query_c);
            $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
            $tot_solicitudes = $fila_c['tot_solicitudes'];

            $query_d = "SELECT COUNT(id) AS tot_salida FROM rh_salida WHERE id_empleado IN (".$in_emps.") AND estatus = '0'";
            $exe_d = sqlsrv_query($cnx, $query_d);
            $fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC);
            $tot_salida = $fila_d['tot_salida'];

            $query_e = "SELECT COUNT(id) AS tot_vacaciones FROM rh_vacaciones WHERE id_empleado IN (".$in_emps.") AND estatus = '0'";
            $exe_e = sqlsrv_query($cnx, $query_e);
            $fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC);
            $tot_vacaciones = $fila_e['tot_vacaciones'];

        }else{
            $query_a = "SELECT * FROM personnel_department WHERE emp_code_charge = '$num_empleado_session' OR sub_emp_code_charge = '$num_empleado_session'";
            $exe_a = sqlsrv_query($cnx, $query_a);
            $in_a = '';
            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                $id_bd_a = $fila_a['id'];
                $in_a .= "'".$id_bd_a."', ";
            }

            $in_a = substr($in_a, 0, -2);
            //$query_b = "SELECT * FROM personnel_department WHERE parent_dept_id IN (".$in_a.")";
            /*$query_b = "SELECT * FROM personnel_department WHERE dept_code IN (".$in_a.") AND emp_code_charge = '$num_empleado_session'";
            $exe_b = sqlsrv_query($cnx, $query_b);
            $in_a = '';
            while ($fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
                $id_bd_b = $fila_b['id'];
                $in_a .= "'".$id_bd_b."', ";
            }
            
            $in_a = substr($in_a, 0, -2);*/

            $query_c = "SELECT COUNT(id) AS tot_solicitudes FROM rh_solicitudes WHERE id_depto IN (".$in_a.") AND estatus = '0'";
            $exe_c = sqlsrv_query($cnx, $query_c);
            $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
            $tot_solicitudes = $fila_c['tot_solicitudes'];

            $query_d = "SELECT COUNT(id) AS tot_salida FROM rh_salida WHERE id_depto IN (".$in_a.") AND estatus = '0'";
            $exe_d = sqlsrv_query($cnx, $query_d);
            $fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC);
            $tot_salida = $fila_d['tot_salida'];

            $query_e = "SELECT COUNT(id) AS tot_vacaciones FROM rh_vacaciones WHERE id_depto IN (".$in_a.") AND estatus = '0'";
            $exe_e = sqlsrv_query($cnx, $query_e);
            $fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC);
            $tot_vacaciones = $fila_e['tot_vacaciones'];
        }
    }
        
    $totales = $tot_solicitudes+$tot_salida+$tot_vacaciones;

    echo($totales);
?>
<?php
    $str_permisos = '';
    $query_per2 = "SELECT COUNT(*) AS tot_reg FROM [rh_novag_system].[dbo].[rh_salida] WHERE id_empleado = '$emp_code_b' AND f_permiso = '$f_recorre' AND estatus != '0'";
    $exe_per2 = sqlsrv_query($cnx, $query_per2);
    $fila_per2 = sqlsrv_fetch_array($exe_per2, SQLSRV_FETCH_ASSOC);
    $tot_reg_per2 = $fila_per2['tot_reg'];
    //echo "<br>Dia: ".$f_recorre." |Permiso: Salida |Total: ".$tot_reg_per2;
    if ($tot_reg_per2 > 0) {//***Permisos de entrada o salida
        $query_per2_b = "SELECT * FROM [rh_novag_system].[dbo].[rh_salida] WHERE id_empleado = '$emp_code_b' AND f_permiso = '$f_recorre'";
        $exe_per2_b = sqlsrv_query($cnx, $query_per2_b);
        while ($fila_per2_b = sqlsrv_fetch_array($exe_per2_b, SQLSRV_FETCH_ASSOC)) {
            $tipo_ausencia_per2 = $fila_per2_b['tipo_ausencia'];
            $estatus_per2 = $fila_per2_b['estatus'];
            $h_permiso_per2 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_per2_b['h_permiso']))));
            $h_permiso_per2 = substr($h_permiso_per2, 16, 8);
            switch ($estatus_per2) {
                case '0':
                    $estatus_per2_str = "En espera";
                    break;
                
                case '1':
                    $estatus_per2_str = "Aprobada";
                    break;

                case '2':
                    $estatus_per2_str = "Rechazada";
                    break;

                default:
                    $estatus_per2_str = "Unknow";
                    break;
            }
            $str_permisos = "Solicitud de tipo ".$tipo_ausencia_per2." a las ".$h_permiso_per2."hrs - Estatus: <b>".$estatus_per2_str."</b>";
        }
    }else{
        $query_per4 = "SELECT COUNT(*) AS tot_reg FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_b' AND YEAR(f_solicitud) = '".date("Y", strtotime($f_recorre))."' AND MONTH(f_solicitud) = '".date("m", strtotime($f_recorre))."' AND estatus = '1'";
        $exe_per4 = sqlsrv_query($cnx, $query_per4);
        $fila_per4 = sqlsrv_fetch_array($exe_per4, SQLSRV_FETCH_ASSOC);
        $tot_reg_per4 = $fila_per4['tot_reg'];
        //echo "<br>Dia: ".$f_recorre." |Permiso: Solicitudes |Total: ".$tot_reg_per4;
        if ($tot_reg_per4 > 0) {//***Permiso de dia completo
            $query_per4 = "SELECT * FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_b' AND YEAR(f_solicitud) = '".date("Y", strtotime($f_recorre))."' AND MONTH(f_solicitud) = '".date("m", strtotime($f_recorre))."' AND estatus = '1'";
            $exe_per4 = sqlsrv_query($cnx, $query_per4);
            while ($fila_per4 = sqlsrv_fetch_array($exe_per4, SQLSRV_FETCH_ASSOC)) {
                $f_ini_4 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_per4['f_ini']))));
                $f_ini_4 = substr($f_ini_4, 5, 10);
                $f_fin_4 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_per4['f_fin']))));
                $f_fin_4 = substr($f_fin_4, 5, 10);
                $tipo_ausencia_4 = $fila_per4['tipo_ausencia'];
                $tipo_permiso_4 = $fila_per4['tipo_permiso'];
                switch ($tipo_permiso_4) {
                    case '1':
                        $tipo_permiso_4_str = "Por paternidad";
                        break;
                    
                    case '2':
                        $tipo_permiso_4_str = "Por defunsión";
                        break;

                    case '3':
                        $tipo_permiso_4_str = "Por incapacidad";
                        break;

                    case '4':
                        $observaciones = $fila_per4['observaciones'];
                        $tipo_permiso_4_str = $observaciones;
                        break;
                }
                $estatus_4 = $fila_per4['estatus'];
                switch ($estatus_4) {
                    case '0':
                        $estatus_per4_str = "Solicitud en espera";
                        break;
                    
                    case '1':
                        $estatus_per4_str = "Solicitud aprobada";
                        break;

                    case '2':
                        $estatus_per4_str = "Solicitud rechazada";
                        break;

                    default:
                        $estatus_per4_str = "Unknow";
                        break;
                }
                
                if ($f_ini_4 == $f_fin_4) {
                    if ($f_ini_4 == $f_recorre) {
                        $str_permisos = 'Solicitud: <b>'.$tipo_ausencia_4.'.</b> - Estatus: <b>'.$estatus_per4_str.'</b><br><b>Detalles: </b>'.$tipo_permiso_4_str;
                        break;
                    } else {
                        continue;
                    }
                }else{
                    $comienzo = new DateTime($f_ini_4);
                    $final = new DateTime($f_fin_4);
                    // Necesitamos modificar la fecha final en 1 día para que aparezca en el bucle
                    $final = $final->modify('+1 day');

                    $intervalo = DateInterval::createFromDateString('1 day');
                    $periodo = new DatePeriod($comienzo, $intervalo, $final);

                    foreach ($periodo as $dt) {
                        //echo " -> ".$dt->format("Y-m-d");
                        if ($dt->format("Y-m-d") == $f_ini_4) {
                            $str_permisos = 'Solicitud: <b>'.$tipo_ausencia_4.'</b> - Estatus: <b>'.$estatus_per4_str.'</b><br>';
                            break;
                        } else {
                            continue;
                        }
                    }
                }
            }
        } else {
            $query_per5 = "SELECT COUNT(*) AS tot_reg FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_b' AND fecha_array LIKE '%$f_recorre%' AND estatus != '0'";
            $exe_per5 = sqlsrv_query($cnx, $query_per5);
            $fila_per5 = sqlsrv_fetch_array($exe_per5, SQLSRV_FETCH_ASSOC);
            $tot_reg_per5 = $fila_per5['tot_reg'];
            //echo "<br>Dia: ".$f_recorre." |Permiso: Vacaciones |Total: ".$tot_reg_per4;
            if ($tot_reg_per5 > 0) {//***Vacaciones
                $query_per4 = "SELECT * FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_b' AND fecha_array LIKE '%$f_recorre%'";
                $exe_per4 = sqlsrv_query($cnx, $query_per4);
                $fila_per4 = sqlsrv_fetch_array($exe_per4, SQLSRV_FETCH_ASSOC);
                $tipo_ausencia_4 = $fila_per4['tipo_ausencia'];
                $str_permisos = 'Solicitud: <b>'.$tipo_ausencia_4.'</b> - Estatus: <b>Solicitud aprobada</b><br>';
            }
        }
    }
?>
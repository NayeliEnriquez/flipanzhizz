<?php
    if ($es_festivo > 0) {
        echo '<td style="background-color: #CC0066; font-size: 10px;"><strong>FES</strong></td>';
    } else {
        $sql_c = "SELECT COUNT(id) AS son_vacaciones FROM rh_vacaciones WHERE rh_vacaciones.fecha_array LIKE '%$fecha_this%' AND id_empleado = '$CLAVE' AND estatus = '1'";
        $exe_c = sqlsrv_query($cnx, $sql_c);
        $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
        $son_vacaciones = $fila_c['son_vacaciones'];
        if ($son_vacaciones > 0) {
            echo '<td style="background-color: #FFFF00; font-size: 10px;"><strong>V</strong></td>';
        }else{
            $sql_d = "SELECT COUNT(*) AS es_incapacidad FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$CLAVE' AND f_fin >= '$fecha_this' AND f_ini <= '$fecha_this' AND estatus = '1'";
            $exe_d = sqlsrv_query($cnx, $sql_d);
            $fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC);
            $es_incapacidad = $fila_d['es_incapacidad'];
            if ($es_incapacidad > 0) {
                echo '<td style="background-color: #E76D0A; font-size: 10px;"><strong>I</strong></td>';
            }else{
                echo '<td style="font-size: 10px;">'.$dia_dia.'</td>';
            }
        }
    }
?>
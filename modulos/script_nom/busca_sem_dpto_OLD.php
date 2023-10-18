<?php
    ini_set('max_execution_time', '0');
    //date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
    //***
    function saber_dia($nombredia) {
        $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $n_semana = $_POST['n_semana'];
    $n_year = $_POST['n_year'];
    $n_depto = $_POST['n_depto'];
    $td_tabla = "";

    $semanas = getFirstDayWeek($n_semana, $n_year);
    $f_start = strtotime($semanas[start]);
    $f_end = strtotime($semanas[end]);

    $y = 0;
    $sql_1 = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE department_id = '$n_depto' AND enable_att = '1'";
    //$sql_1 = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '6242' AND enable_att = '1'";
    $exe_1 = sqlsrv_query($conn, $sql_1);
    while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
        $y++;
        $td_tabla .= "<tr id='tr_".$y."'>";
        $totales_extra = 0;

        $emp_code_1 = $fila_1['emp_code'];
        $last_name_1 = $fila_1['last_name'];
        $first_name_1 = $fila_1['first_name'];

        //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***
        $id_bd_employee = $fila_1['id'];
        $query_shift_b = "SELECT shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_employee'";
        $exe_shift_b = sqlsrv_query($conn, $query_shift_b);
        $fila_shift_b = sqlsrv_fetch_array($exe_shift_b, SQLSRV_FETCH_ASSOC);
        $shift_id = $fila_shift_b['shift_id'];

        $he_lun = '';   $hs_lun = '';   $tolerancia_lun = '';
        $he_mar = '';   $hs_mar = '';   $tolerancia_mar = '';
        $he_mie = '';   $hs_mie = '';   $tolerancia_mie = '';
        $he_jue = '';   $hs_jue = '';   $tolerancia_jue = '';
        $he_vie = '';   $hs_vie = '';   $tolerancia_vie = '';
        $he_sab = '';   $hs_sab = '';   $tolerancia_sab = '';
        $he_dom = '';   $hs_dom = '';   $tolerancia_dom = '';
        $query_shift_c = "SELECT
                CAST(att_s.in_time AS time)[hora_entrada], 
                att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
            FROM
                [zkbiotime].[dbo].[att_shiftdetail] AS att_s
            INNER JOIN
                [zkbiotime].[dbo].[att_timeinterval] AS att_t
            ON
                att_s.time_interval_id = att_t.id
            WHERE
                att_s.shift_id = '$shift_id'
            ORDER BY
                att_s.day_index ASC";
        $exe_shift_c = sqlsrv_query($conn, $query_shift_c);
        while ($fila_shift_c = sqlsrv_fetch_array($exe_shift_c, SQLSRV_FETCH_ASSOC)) {
            $day_index = $fila_shift_c['day_index'];
            $id_horario = $fila_shift_c['id'];
            $alias_hr = $fila_shift_c['alias'];
            $hora_entrada = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_shift_c['hora_entrada']))));
            $hora_entrada = substr($hora_entrada, 16, 5);
            $hora_salida = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_shift_c['hora_salida']))));
            $hora_salida = substr($hora_salida, 16, 5);
            $tolerancia_min = $fila_shift_c['in_above_margin'];
            switch ($day_index) {
                case '0':
                    if ($id_horario == '2') {
                        $he_dom = 'Descanso';   $hs_dom = 'Descanso';   $tolerancia_dom = 'Descanso';
                    } else {
                        $he_dom = $hora_entrada;   $hs_dom = $hora_salida;   $tolerancia_dom = $tolerancia_min;
                    }
                    break;
                
                case '1':
                    if ($id_horario == '2') {
                        $he_lun = 'Descanso';   $hs_lun = 'Descanso';   $tolerancia_lun = 'Descanso';
                    } else {
                        $he_lun = $hora_entrada;   $hs_lun = $hora_salida;   $tolerancia_lun = $tolerancia_min;
                    }
                    break;

                case '2':
                    if ($id_horario == '2') {
                        $he_mar = 'Descanso';   $hs_mar = 'Descanso';   $tolerancia_mar = 'Descanso';
                    } else {
                        $he_mar = $hora_entrada;   $hs_mar = $hora_salida;   $tolerancia_mar = $tolerancia_min;
                    }
                    break;

                case '3':
                    if ($id_horario == '2') {
                        $he_mie = 'Descanso';   $hs_mie = 'Descanso';   $tolerancia_mie = 'Descanso';
                    } else {
                        $he_mie = $hora_entrada;   $hs_mie = $hora_salida;   $tolerancia_mie = $tolerancia_min;
                    }
                    break;

                case '4':
                    if ($id_horario == '2') {
                        $he_jue = 'Descanso';   $hs_jue = 'Descanso';   $tolerancia_jue = 'Descanso';
                    } else {
                        $he_jue = $hora_entrada;   $hs_jue = $hora_salida;   $tolerancia_jue = $tolerancia_min;
                    }
                    break;

                case '5':
                    if ($id_horario == '2') {
                        $he_vie = 'Descanso';   $hs_vie = 'Descanso';   $tolerancia_vie = 'Descanso';
                    } else {
                        $he_vie = $hora_entrada;   $hs_vie = $hora_salida;   $tolerancia_vie = $tolerancia_min;
                    }
                    break;

                case '6':
                    if ($id_horario == '2') {
                        $he_sab = 'Descanso';   $hs_sab = 'Descanso';   $tolerancia_sab = 'Descanso';
                    } else {
                        $he_sab = $hora_entrada;   $hs_sab = $hora_salida;   $tolerancia_sab = $tolerancia_min;
                    }
                    break;
                
                default:
                    $he_sab = 'ERROR';   $hs_sab = 'ERROR';   $tolerancia_sab = 'ERROR';
                    break;
            }
        }
        //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***

        $sql_a = "SELECT NOMINA FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$emp_code_1'";
        $exe_a = sqlsrv_query($cnx, $sql_a);
        $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
        $NOMINA_a = $fila_a['NOMINA'];
        $tipo_nomina = strpos($NOMINA_a, "SEMANA");
        if ($tipo_nomina !== false) {
            //echo "<br>".$first_name_1." ".$last_name_1;
            $td_tabla .= "
                <th class='align-middle' scope='row'>".$emp_code_1." - ".$last_name_1." ".$first_name_1."</th>
            ";

            $z = 0;
            for ($i=$f_start; $i <= $f_end ; $i+=86400) {
                unset($punch_time_c);
                unset($fecha_c);
                unset($Hora_c);
                unset($terminal_alias_c);
                unset($terminal_alias_ext);
                unset($Hora_extra);
                unset($Hora_normal);
                $punch_time_c = array();
                $fecha_c = array();
                $Hora_c = array();
                $terminal_alias_c = array();
                $terminal_alias_ext = array();
                $terminal_alias_nor = array();
                $Hora_extra = array();
                $Hora_normal = array();
        
                $f_recorre = date("Y-m-d", $i);
                $dia_dia = saber_dia($f_recorre);
                $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code_1' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
                $exe_revisa = sqlsrv_query($conn, $query_revisa);
                $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
                if ($fila_revisa != NULL) {
                    if ((strtotime($he_lun)) > (strtotime($hs_lun))) {//***TURNO NOCTURNO
                        $turno = "noche";
                        $menos_un_dia = date("Y-m-d",strtotime($f_recorre."- 1 days")); 
                        $dia_dia = saber_dia($menos_un_dia);
                        //echo "<br>Menos un dia: ".$menos_un_dia;
                        //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                        $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '".$emp_code_b."' AND punch_time > '".$menos_un_dia." 12:00' AND punch_time < '".$f_recorre." 12:00' ORDER BY fecha ASC";
                        $exe_c = sqlsrv_query($conn, $query_c);
                        while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                            $punch_time_c[] = $fila_c['punch_time'];
                            $fecha_c[] = $fila_c['fecha'];
                            $Hora_c[] = $fila_c['Hora'];
                            $terminal_alias_c[] = $fila_c['terminal_alias'];
                            $val_term = strpos($fila_c['terminal_alias'], "TE ");//Buscamos la palabra TE que se refiere a una terminal de Tiempo Extra
                            if ($val_term !== false) {
                                $terminal_alias_ext[] = $fila_c['terminal_alias'];
                                $Hora_extra[] = $fila_c['Hora'];
                            } else {
                                $terminal_alias_nor[] = $fila_c['terminal_alias'];
                                $Hora_normal[] = $fila_c['Hora'];
                            }
                        }
                    }else{//***TURNO DIA
                        $turno = "dia";
                        //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                        $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code_1' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
                        $exe_c = sqlsrv_query($conn, $query_c);
                        while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                            $punch_time_c[] = $fila_c['punch_time'];
                            $fecha_c[] = $fila_c['fecha'];
                            $Hora_c[] = $fila_c['Hora'];
                            $terminal_alias_c[] = $fila_c['terminal_alias'];
                            $val_term = strpos($fila_c['terminal_alias'], "TE ");//Buscamos la palabra TE que se refiere a una terminal de Tiempo Extra
                            if ($val_term !== false) {
                                $terminal_alias_ext[] = $fila_c['terminal_alias'];
                                $Hora_extra[] = $fila_c['Hora'];
                            } else {
                                $terminal_alias_nor[] = $fila_c['terminal_alias'];
                                $Hora_normal[] = $fila_c['Hora'];
                            }
                        }
                    }
                }
        
                $h_norm_A = new DateTime($Hora_normal[0]);
                $h_norm_B = new DateTime(end($Hora_normal));
                if ($turno == "noche") {
                    $h_norm_A->modify('-1 day');
                }
                $diferencia_norm = $h_norm_A->diff($h_norm_B);
                //echo "<br>".$diferencia_norm->format('NORMAL: %H horas %i minutos %s segundos');
                $h_extra_A = new DateTime($Hora_extra[0]);
                $h_extra_B = new DateTime(end($Hora_extra));
                if ($turno == "noche") {
                    $h_extra_A->modify('-1 day');
                }
                $diferencia_ext = $h_extra_A->diff($h_extra_B);
                foreach ($diferencia_norm as $key => $value) {
                    //echo "<br>".$key." -> ".$value;
                    if ($key == 'h') {
                        $hrs_norm_tot += $value;
                    }
                    if ($key == 'i') {
                        $min_norm_tot += $value;
                    }
                }
                foreach ($diferencia_ext as $key => $value) {
                    if ($key == 'h') {
                        $hrs_ext_tot += $value;
                    }
                    if ($key == 'i') {
                        $min_ext_tot += $value;
                    }
                }
        
                switch ($dia_dia) {
                    case 'Lunes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla = "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionLunes'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseLunes' aria-expanded='false' aria-controls='collapseLunes'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseLunes' class='accordion-collapse collapse' data-bs-parent='#accordionLunes'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_lun." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_lun." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    
                    case 'Martes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionMartes'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseMartes' aria-expanded='false' aria-controls='collapseMartes'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseMartes' class='accordion-collapse collapse' data-bs-parent='#accordionMartes'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_mar." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_mar." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    case 'Miercoles':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionMiercoles'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseMiercoles' aria-expanded='false' aria-controls='collapseMiercoles'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseMiercoles' class='accordion-collapse collapse' data-bs-parent='#accordionMiercoles'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_mie." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_mie." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    case 'Jueves':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionJueves'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseJueves' aria-expanded='false' aria-controls='collapseJueves'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseJueves' class='accordion-collapse collapse' data-bs-parent='#accordionJueves'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_jue." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_jue." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    case 'Viernes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionViernes'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseViernes' aria-expanded='false' aria-controls='collapseViernes'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseViernes' class='accordion-collapse collapse' data-bs-parent='#accordionViernes'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_vie." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_vie." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    case 'Sabado':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionSabado'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseSabado' aria-expanded='false' aria-controls='collapseSabado'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseSabado' class='accordion-collapse collapse' data-bs-parent='#accordionSabado'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_sab." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_sab." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                    case 'Domingo':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        $emp_code_b = $emp_code_1;
                        include ('../script_super/permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
        
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$n_year' AND emp_code = '$emp_code_1' AND f_recorre = '$f_recorre' AND aut_sup = '1' AND estatus = '2'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $txt_textra = $fila_master['txt_textra'];
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
        
                        $th_tabla .= "
                            <th class='align-middle' scope='col'>".$dia_dia."<br>".$f_recorre."</th>
                        ";
                        if($tot_hrs_e_new> 0){
                            $v_min = 0;
                            if (empty($Hora_normal[0])) {
                                $min_a = $Hora_extra[0];
                                $min_b = end($Hora_extra);
                                $v_min = 1;
                            }
                            if (empty(end($Hora_extra))) {
                                $min_a = $Hora_normal[0];
                                $min_b = end($Hora_normal);
                                $v_min = 2;
                            }
        
                            if ($v_min == 1) {
                                $min_desc = "<p style='font-size:10px;'>".$min_a." - ".$min_b."</p>";
                            }else{
                                $min_desc = "<p style='font-size:10px;'>".$Hora_normal[0]." - ".end($Hora_extra)."</p>";
                            }
        
                            $td_tabla .= "
                                <td class='align-middle'>
                                    ".$min_desc."<p style='font-size:14px;'>Horas extra: <br><strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>
                                    <br>
                                    <div class='accordion' id='accordionDomingo'>
                                        <div class='accordion-item'>
                                            <h2 class='accordion-header'>
                                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseDomingo' aria-expanded='false' aria-controls='collapseDomingo'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg>
                                                </button>
                                            </h2>
                                            <div id='collapseDomingo' class='accordion-collapse collapse' data-bs-parent='#accordionDomingo'>
                                                <div class='accordion-body'>
                                                    <center>
                                                        <table>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de entrada:</td>
                                                                <td><strong>".$he_dom." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Horario de salida:</td>
                                                                <td><strong>".$hs_dom." hrs</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_normal[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_normal)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_n_new > 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas: <strong>".$tot_hrs_n_new." Hrs ".$tot_min_n_new." Min</strong></p></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                            <tr>
                                                                <td colspan='2'><strong><i>Registros tiempo extra entrada/salida</i></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Entrada:</td>
                                                                <td><strong>".$Hora_extra[0]."</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Salida:</td>
                                                                <td><strong>".end($Hora_extra)."</strong></td>
                                                            </tr>";
                                                            if($tot_hrs_e_new> 0){
                                                                $td_tabla .= "
                                                                    <tr>
                                                                        <td colspan='2'><p style='font-size:18px;'>Total de horas aprobadas Extra: <strong>".$tot_hrs_e_new." Hrs ".$tot_min_e_new." Min</strong></p>Informaci&oacute;n de tiempo extra: <i>".$txt_textra."</i></td>
                                                                    </tr> ";
                                                            }else{
                                                                $td_tabla .= "
                                                                <tr>
                                                                    <td colspan='2'><i>Sin aprobaci&oacute;n de horas extra</i></td>
                                                                </tr> ";
                                                            }
                                                        $td_tabla .= "
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            ";
                            $totales_extra += $tot_hrs_e_new;
                        }else{
                            $td_tabla .= "
                                <td>
                                    <i>Sin horas extra</i>
                                </td>";
                        }
                        break;
                }
                $z++;
                if ($z == 7) {
                    $td_tabla .= "
                        <td id='totales_".$y."' class='align-middle'><strong>".$totales_extra." horas</strong></td>
                    ";
                }
            }
        }else{
            //echo "<br>".$sql_a;
            continue;
        }
    }
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div style="width:auto; height:auto; overflow:auto;">
            <table class="table" id="table_semana">
                <thead>
                    <tr class="table-info">
                        <th class='align-middle' scope='col'>Nombre</th>
                        <?php echo($th_tabla); ?>
                        <th class='align-middle' scope='col'>Total horas extra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo($td_tabla); ?>
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th class='align-middle' scope='col'>Nombre</th>
                        <?php echo($th_tabla); ?>
                        <th class='align-middle' scope='col'>Total horas extra</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*$(document).ready(function() {
        $('#table_semana').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });*/
</script>
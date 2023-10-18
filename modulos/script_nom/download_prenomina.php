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
        $dias = array('', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $n_semana = $_GET['slc_semana'];
    $n_year = $_GET['slc_year'];
    $n_depto = $_GET['slc_deptos'];
    $td_tabla = "";

    $semanas = getFirstDayWeek($n_semana, $n_year);
    $f_start = strtotime($semanas[start]);
    $f_end = strtotime($semanas[end]);
    

    header("Content-type: text/html; charset=utf8");
    header("Content-Type:application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment; filename=PrenominaSEMANA'.$n_semana.'-'.$n_year.'.xls');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    
    echo '
        <style>
            .col_azul {
                background-color: #92CDDC; 
            }
            .col_verde {
                background-color: #CCFFCC;
            }
            .col_rosa {
                background-color: #FFCCFF;
            }
        </style>
        <table style="text-align:center; border-style:solid;">
            <tr></tr>
            <tr>
                <td colspan="13" style="border: 1px solid;"><center><b>NOVAG INFANCIA, S.A. DE C.V.</b></center></td>
            </tr>
            <tr>
                <td style="width: 12px;"></td>
                <td class="col_azul"></td>
                <td><b>PER. '.$n_semana.'/'.$n_year.' '.date('d', $f_start).' AL '.date('d', $f_end).' '.date('M', $f_start).'. '.date('Y', $f_start).'</b></td>
                <td class="col_verde">DIAS</td>
                <td class="col_verde">Vacaciones</td>
                <td>GRATIF.</td>
                <td>PRIMA</td>
                <td>TIEMPO</td>
                <td>COMP.</td>
                <td class="col_rosa">AUSENTISMO</td>
                <td class="col_rosa">INCAPACIDAD</td>
                <td>OTRAS</td>
                <td>CAJA</td>                
            </tr>
            <tr>
                <td style="width: 12px;"></td>
                <td class="col_azul">CLAVE</td>
                <td>NOMBRE</td>
                <td class="col_verde">TRAB.</td>
                <td class="col_verde"></td>
                <td>ESP.</td>
                <td>DOM.</td>
                <td>EXTRA</td>
                <td></td>
                <td class="col_rosa"></td>
                <td class="col_rosa"></td>
                <td></td>
                <td>AHORRO</td>
            </tr>
        </table>
    ';

    $sql_1 = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE department_id = '$n_depto' AND enable_att = '1' ORDER BY last_name ASC";
    $exe_1 = sqlsrv_query($conn, $sql_1);
    while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
        $emp_code_1 = $fila_1['emp_code'];
        $last_name_1 = $fila_1['last_name'];
        $first_name_1 = $fila_1['first_name'];

        $sql_valida_datos = "SELECT COUNT(id) AS filas_t FROM dbo.rh_master_emptime WHERE rh_master_emptime.year = '$n_year' AND rh_master_emptime.n_semana = '$n_semana' AND rh_master_emptime.emp_code = '$emp_code_1' AND rh_master_emptime.estatus = '2'";
        $exe_valida_datos = sqlsrv_query($cnx, $sql_valida_datos);
        $fila_valida_datos = sqlsrv_fetch_array($exe_valida_datos, SQLSRV_FETCH_ASSOC);
        $filas_t = $fila_valida_datos['filas_t'];
        if ($filas_t == 0) {
            continue;
        }

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

        $dias_trabajados = 0;
        $totales_extra = 0;
        $ausen_lun = ''; $ausen_mar = ''; $ausen_mie = ''; $ausen_jue = ''; $ausen_vie = ''; $ausen_sab = ''; $ausen_dom = '';
        $ausen_txt = '';
        $tot_ausencias = 0;
        $v_lun = ''; $v_mar = ''; $v_mie = ''; $v_jue = ''; $v_vie = ''; $v_sab = ''; $v_dom = ''; 
        $v_txt = '';
        $tot_vacaciones = 0;
        $inc_lun = ''; $inc_mar = ''; $inc_mie = ''; $inc_jue = ''; $inc_vie = ''; $inc_sab = ''; $inc_dom = '';
        $inc_txt = '';
        $tot_incapacidades = 0;
        
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
                    $mas_un_dia = date("Y-m-d",strtotime($f_recorre."+ 1 days")); 
                    //$dia_dia = saber_dia($menos_un_dia);
                    //echo "<br>Menos un dia: ".$menos_un_dia;
                    //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                    //echo "--->".$query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '".$emp_code_1."' AND punch_time > '".$menos_un_dia." 12:00' AND punch_time < '".$f_recorre." 12:00' ORDER BY fecha ASC";
                    $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '".$emp_code_1."' AND punch_time > '".$f_recorre." 12:00' AND punch_time < '".$mas_un_dia." 12:00' ORDER BY fecha ASC";
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
                            $val_term = strpos($fila_c['terminal_alias'], "TIEMPO EXTRA");
                            if ($val_term !== false) {
                                $terminal_alias_ext[] = $fila_c['terminal_alias'];
                                $Hora_extra[] = $fila_c['Hora'];
                            } else {
                                $terminal_alias_nor[] = $fila_c['terminal_alias'];
                                $Hora_normal[] = $fila_c['Hora'];
                            }
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
                            $val_term = strpos($fila_c['terminal_alias'], "TIEMPO EXTRA");
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
    
                    /*$diferencia_norm->format('%H');
                    $diferencia_norm->format('%i');*/

                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_lun != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_lun != 'Descanso') {
                                $ausencia_lun = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_lun != 'Descanso') {
                            $ausencia_lun = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    //***Vacaciones
                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_lun = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_lun = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_mar != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_mar != 'Descanso') {
                                $ausencia_mar = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_mar != 'Descanso') {
                            $ausencia_mar = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_mar = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_mar = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_mie != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_mie != 'Descanso') {
                                $ausencia_mie = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_mie != 'Descanso') {
                            $ausencia_mie = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_mie = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_mie = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_jue != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_jue != 'Descanso') {
                                $ausencia_jue = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_jue != 'Descanso') {
                            $ausencia_lun = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_jue = date("j F", $i);
                    }
                    
                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_jue = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_vie != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_vie != 'Descanso') {
                                $ausencia_vie = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_vie != 'Descanso') {
                            $ausencia_lun = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_vie = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_vie = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_sab != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_sab != 'Descanso') {
                                $ausencia_sab = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_sab != 'Descanso') {
                            $ausencia_sab = date("j F", $i);
                            $tot_ausencias++;
                        }
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_sab = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_sab = date("j F", $i);
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
    
                    if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                        if ($tot_hrs_n_new > 0) {
                            if ($he_dom != 'Descanso') {
                                $dias_trabajados += 1.4;
                            }
                        }else{
                            if ($he_dom != 'Descanso') {
                                $ausencia_dom = date("j F", $i);
                                $tot_ausencias++;
                            }
                        }
                        if ($tot_hrs_e_new > 0) {
                            $totales_extra += $tot_hrs_e_new;
                        }
                        $prim_dom = 1;
                    }else{
                        //$he_lun//***Hora de entrada
                        //$hs_lun//***Hora de salida
                        if ($he_dom != 'Descanso') {
                            $ausencia_dom = date("j F", $i);
                            $tot_ausencias++;
                        }
                        $prim_dom = '';
                    }

                    $query_vacaciones = "SELECT COUNT(id) AS days_v_taken  FROM [rh_novag_system].[dbo].[rh_vacaciones] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND fecha_array LIKE '%$f_recorre%'";
                    $exe_vacaciones = sqlsrv_query($cnx, $query_vacaciones);
                    $fila_vacaciones = sqlsrv_fetch_array($exe_vacaciones, SQLSRV_FETCH_ASSOC);
                    if ($fila_vacaciones['days_v_taken'] > 0) {
                        $tot_vacaciones++;
                        $v_dom = date("j F", $i);
                    }

                    //***Incapacidades
                    $query_incapacidades = "SELECT COUNT(id) AS days_inc_taken FROM [rh_novag_system].[dbo].[rh_solicitudes] WHERE id_empleado = '$emp_code_1' AND estatus = '1' AND tipo_permiso = '3' AND f_ini <= '$f_recorre' AND f_fin >= '$f_recorre'";
                    $exe_incapacidades = sqlsrv_query($cnx, $query_incapacidades);
                    $fila_incapacidades = sqlsrv_fetch_array($exe_incapacidades, SQLSRV_FETCH_ASSOC);
                    if ($fila_incapacidades['days_inc_taken'] > 0) {
                        $tot_incapacidades++;
                        $inc_dom = date("j F", $i);
                    }
                    break;
            }

        }

        $v_txt = $v_lun.' '.$v_mar.' '.$v_mie.' '.$v_jue.' '.$v_vie.' '.$v_sab.' '.$v_dom;
        $digito_vaca = 1.4 * $tot_vacaciones;
        $vaca_all = ($tot_vacaciones > 0) ? $tot_vacaciones.' D.<br>'.$v_txt.' '.$digito_vaca : '' ;
        
        $ausen_txt = $ausencia_lun.' '.$ausencia_mar.' '.$ausencia_mie.' '.$ausencia_jue.' '.$ausencia_vie.' '.$ausencia_sab.' '.$ausencia_dom;
        $digito_ausen = 1.4 * $tot_ausencias;
        $ausencia_all = ($tot_ausencias > 0) ? $tot_ausencias.' D.<br>'.$ausen_txt.' '.$digito_ausen  : '' ;

        $inc_txt = $inc_lun.' '.$inc_mar.' '.$inc_mie.' '.$inc_jue.' '.$inc_vie.' '.$inc_sab.' '.$inc_dom;
        $tot_incapacidades = ($tot_incapacidades > 0) ? $tot_incapacidades : '' ;

        $td_tabla .= '
            <table style="text-align:center; border-style:solid;">
                <tr>
                    <td style="width: 12px;"></td>
                    <td class="col_azul">'.$emp_code_1.'</td>
                    <td>'.$last_name_1.' '.$first_name_1.'</td>
                    <td class="col_verde">'.$dias_trabajados.'</td>
                    <td class="col_verde">'.$vaca_all.'</td>
                    <td></td>
                    <td>'.$prim_dom.'</td>
                    <td>'.$totales_extra.'</td>
                    <td></td>
                    <td class="col_rosa">'.$ausencia_all.'</td>
                    <td class="col_rosa">'.$tot_incapacidades.'</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        ';
    }

    echo($td_tabla);
?>
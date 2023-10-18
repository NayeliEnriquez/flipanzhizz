<?php
    ini_set('max_execution_time', 0);
    session_start();
    date_default_timezone_set("America/Mazatlan");
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."<br>";
	}*/
    $num_empleado_session = $_SESSION['num_empleado_a'];
    $name_empleado_session = $_SESSION['name_a'];
    $fecha_now = date('Y-m-d H:i:s');

    $txt_historico = "Supervisor ".$num_empleado_session." ".$name_empleado_session." aprueba los horarios, fecha(".$fecha_now.")";

    include ('../../php/conn.php');

    //***
    function saber_dia($nombredia) {
        $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $emp_code = $_POST['emp_code'];
    $f_start = $_POST['f_start'];
    $f_end = $_POST['f_end'];
    $slc_semana = $_POST['slc_semana'];
    $slc_year = $_POST['slc_year'];
    
    $responseee = $emp_code."||";

    for ($i=$f_start; $i <= $f_end ; $i+=86400) { 
        $z++;
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

        //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***
        $query_shift_a = "SELECT id FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code'";
        $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
        $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
        $id_bd_employee = $fila_shift_a['id'];
        $query_shift_b = "SELECT shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_employee'";
        $exe_shift_b = sqlsrv_query($conn, $query_shift_b);
        $fila_shift_b = sqlsrv_fetch_array($exe_shift_b, SQLSRV_FETCH_ASSOC);
        $shift_id = $fila_shift_b['shift_id'];
        /*La funcion CAST solo obtiene el tipo de dato que necesites y se declara indicando TIME(HORAS), DATEADD se utiliza para agregar minutos horas o dias segun la funcion(MINUTE, DAY, HOUR)
        Ejemplos
        -- Restar 30 minutos a la fecha y hora actual 
        SELECT DATEADD(MINUTE, -30, GETDATE()) AS '30 minutos antes'
        GO
        -- Sumar 1 hora a la fecha y hora actual
        SELECT DATEADD(HOUR, 1, GETDATE()) AS '1 hora después'
        GO
        -- Restamos 1 día a la fecha actual
        SELECT DATEADD(DAY, -1, GETDATE()) AS '1 día antes'
        GO
        */

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

        $f_recorre = date("Y-m-d", $i);
        $dia_dia = saber_dia($f_recorre);
        $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
        $exe_revisa = sqlsrv_query($conn, $query_revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        if ($fila_revisa != NULL) {
            $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
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

            $h_norm_A = new DateTime($Hora_normal[0]);
            $h_norm_B = new DateTime(end($Hora_normal));
            $diferencia_norm = $h_norm_A->diff($h_norm_B);
            $h_extra_A = new DateTime($Hora_extra[0]);
            $h_extra_B = new DateTime(end($Hora_extra));
            $diferencia_ext = $h_extra_A->diff($h_extra_B);
            foreach ($diferencia_norm as $key => $value) {
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
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= $dia_dia.",1";
                    }else{
                        if ($he_lun == 'Descanso') {
                            $he_lun = '00:00:00';
                            $hs_lun = '00:00:00';
                        }
                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_lun','$hs_lun');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal_ini', '$Hora_normal_end',
                            '$Hora_extra_ini', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= $dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></td>";
                    }
                    break;

                case 'Martes':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_mar == 'Descanso') {
                            $he_mar = '00:00:00';
                            $hs_mar = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_mar'
                                    ,'$hs_mar');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></td>";
                    }
                    break;

                case 'Miercoles':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_mie == 'Descanso') {
                            $he_mie = '00:00:00';
                            $hs_mie = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_mie'
                                    ,'$hs_mie');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></td>";
                    }
                    break;

                case 'Jueves':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_jue == 'Descanso') {
                            $he_jue = '00:00:00';
                            $hs_jue = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_jue'
                                    ,'$hs_jue');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></td>";
                    }
                    break;

                case 'Viernes':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_vie == 'Descanso') {
                            $he_vie = '00:00:00';
                            $hs_vie = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_vie'
                                    ,'$hs_vie');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></td>";
                    }
                    break;

                case 'Sabado':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_sab == 'Descanso') {
                            $he_sab = '00:00:00';
                            $hs_sab = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_sab'
                                    ,'$hs_sab');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico','$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></ td>";
                    }
                    break;

                case 'Domingo':
                    $Hora_normal_ini = $Hora_normal[0];
                    $Hora_normal_end = end($Hora_normal);
                    $Hora_extra_ini = $Hora_extra[0];
                    $Hora_extra_end = end($Hora_extra);
                    $hrs_nml = $diferencia_norm->format('%H');
                    $inphin_ = $hrs_nml;
                    $min_nml = $diferencia_norm->format('%i');
                    $inphout_ = $min_nml;
                    $hrs_ext = $diferencia_ext->format('%H');
                    $inphinext_ = $hrs_ext;
                    $min_ext = $diferencia_ext->format('%i');
                    $inphoutext_ = $min_ext;

                    $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND f_recorre = '$f_recorre' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                    $exe_master = sqlsrv_query($cnx, $query_master);
                    $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                    $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                    $tot_min_n_new = $fila_master['tot_min_n_new'];
                    $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                    $tot_min_e_new = $fila_master['tot_min_e_new'];

                    if($tot_hrs_n_new > 0){
                        $responseee .= ",".$dia_dia.",1";
                    }else{
                        if ($he_dom == 'Descanso') {
                            $he_dom = '00:00:00';
                            $hs_dom = '00:00:00';
                        }

                        $query_horario = "INSERT INTO [dbo].[rh_horarios_hst]
                                    ([horario_in]
                                    ,[horario_out])
                            VALUES
                                    ('$he_dom'
                                    ,'$hs_dom');
                            SELECT SCOPE_IDENTITY()
                        ";
                        $exe_horario = sqlsrv_query($cnx, $query_horario);
                        sqlsrv_next_result($exe_horario); 
                        sqlsrv_fetch($exe_horario); 
                        $id_horario_turno = sqlsrv_get_field($exe_horario, 0);

                        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                            ([year], [n_semana], [emp_code],
                            [f_recorre], [hora_nin], [hora_nout],
                            [hora_ein], [hora_eout], [tot_hrs_n],
                            [tot_min_n], [tot_hrs_e], [tot_min_e],
                            [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                            [tot_min_e_new], [aut_sup], [aut_rh],
                            [estatus], [historico], [id_horario_turno])
                        VALUES
                            ('$slc_year', '$slc_semana', '$emp_code',
                            '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                            '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                            '$min_nml', '$hrs_ext', '$min_ext',
                            '$inphin_', '$inphout_', '$inphinext_',
                            '$inphoutext_', '1', '0',
                            '1', '$txt_historico', '$id_horario_turno')";

                        $responseee .= ",".$dia_dia.",<td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'><center><div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!</div></center></ td>";
                    }
                    break;
                    
                
            }
        }else{
            switch ($dia_dia) {
                case 'Lunes':
                    $v_lun = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Martes':
                    $v_mar = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Miercoles':
                    $v_mie = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Jueves':
                    $v_jue = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Viernes':
                    $v_vie = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Sabado':
                    $v_sab = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;

                case 'Domingo':
                    $v_dom = "
                        <div id='div_empty_".$emp_code."_".$dia_dia."'>
                            <center>
                                Sin registros<br>
                                <button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code."_".$dia_dia."' title='Agregar horario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                </button>
                            </center>
                        </div>";
                    break;
            }
        }
    }
    echo ($responseee);
?>
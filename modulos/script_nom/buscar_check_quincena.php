<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $num_empl = $_POST['num_empl'];
    $slc_quincena = $_POST['slc_quincena'];
    $slc_year = $_POST['slc_year'];
    $inp_dia_unico = $_POST['inp_dia_unico'];

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

    $query_name = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code = '$num_empl'";
    $exe_name = sqlsrv_query($conn, $query_name);
    $fila_name = sqlsrv_fetch_array($exe_name, SQLSRV_FETCH_ASSOC);
    $first_name = $fila_name['first_name'];
    $last_name = $fila_name['last_name'];

    //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***
    $query_shift_a = "SELECT id FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$num_empl'";
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

    if (!empty($inp_dia_unico)) {
        $dia_dia = saber_dia($inp_dia_unico);
        $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$num_empl' AND fecha LIKE '$inp_dia_unico' ORDER BY fecha DESC";
        $exe_revisa = sqlsrv_query($conn, $query_revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        if ($fila_revisa != NULL) {
            //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
            $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$num_empl' AND fecha LIKE '$inp_dia_unico' ORDER BY fecha DESC";
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
        }else{
            $Hora_normal = array('Sin registro', 'Sin registro');
        }

        //***Codigo para obtener los permisos del dia corriente***
        $str_permisos = '';
        $emp_code_b = $num_empl;
        $f_recorre = $inp_dia_unico;
        include ('../script_super/permisos_dia.php');
        //***Codigo para obtener los permisos del dia corriente***

        if (strlen($Hora_normal[0]) != '12') {
            $h_norm_A = new DateTime($Hora_normal[0]);
            $h_norm_B = new DateTime(end($Hora_normal));
            $diferencia_norm = $h_norm_A->diff($h_norm_B);
        } else {
            $h_norm_A = new DateTime("00:00:00");
            $h_norm_B = new DateTime("00:00:00");
            $diferencia_norm = $h_norm_A->diff($h_norm_B);
        }

        switch ($dia_dia) {
            case 'Lunes':
                $he_days = $he_lun;
                $hs_days = $hs_lun;
                break;
            
            case 'Martes':
                $he_days = $he_mar;
                $hs_days = $hs_mar;
                break;

            case 'Miercoles':
                $he_days = $he_mie;
                $hs_days = $hs_mie;
                break;

            case 'Jueves':
                $he_days = $he_jue;
                $hs_days = $hs_jue;
                break;

            case 'Viernes':
                $he_days = $he_vie;
                $hs_days = $hs_vie;
                break;

            case 'Sabado':
                $he_days = $he_sab;
                $hs_days = $hs_sab;
                break;

            case 'Domingo':
                $he_days = $he_dom;
                $hs_days = $hs_dom;
                break;
        }

        ?>
        <br>
        <div class="row">
            <div class="col-md-10 col-sm-10">
            </div>
            <div class="col-md-2 col-sm-2">
                <button type="button" class="btn btn-dark" onclick="descarga_pdf_quincena(<?php echo($num_empl); ?>, <?php echo($slc_quincena); ?>, <?php echo($slc_year); ?>)">Descargar PDF <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <?php echo($div_acordion); ?>
                </div>
            </div>
        </div>
        <?php
    }else{
        //echo "<br>".$slc_quincena." <> ".$slc_year." <> ".$num_empl."<br>";
        switch ($slc_quincena) {
            case '1'://***<option value="1">Del 1ro al 15 de Enero</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 1;
                break;
            
            case '2'://***<option value="2">Del 16 al 31 de Enero</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 1;
                break;

            case '3'://***<option value="3">Del 1ro al 15 de Febrero</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 2;
                break;

            case '4'://***<option value="4">Del 16 al 28 de Febrero</option>
                $d_ini = 16;
                $d_fin = 28;
                $v_mes = 2;
                break;

            case '5'://***<option value="5">Del 1ro al 15 de Marzo</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 3;
                break;

            case '6'://***<option value="6">Del 16 al 31 de Marzo</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 3;
                break;

            case '7'://***<option value="7">Del 1ro al 15 de Abril</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 4;
                break;

            case '8'://***<option value="8">Del 16 al 30 de Abril</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 4;
                break;

            case '9'://***<option value="9">Del 1ro al 15 de Mayo</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 5;
                break;

            case '10'://***<option value="10">Del 16 al 31 de Mayo</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 5;
                break;

            case '11'://***<option value="11">Del 1ro al 15 de Junio</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 6;
                break;

            case '12'://***<option value="12">Del 16 al 30 de Junio</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 6;
                break;

            case '13'://***<option value="13">Del 1ro al 15 de Julio</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 7;
                break;

            case '14'://***<option value="14">Del 16 al 31 de Julio</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 7;
                break;

            case '15'://***<option value="15">Del 1ro al 15 de Agosto</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 8;
                break;

            case '16'://***<option value="16">Del 16 al 31 de Agosto</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 8;
                break;

            case '17'://***<option value="17">Del 1ro al 15 de Septiembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 9;
                break;

            case '18'://***<option value="18">Del 16 al 30 de Septiembre</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 9;
                break;

            case '19'://***<option value="19">Del 1ro al 15 de Octubre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 10;
                break;

            case '20'://***<option value="20">Del 16 al 31 de Octubre</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 10;
                break;

            case '21'://***<option value="21">Del 1ro al 15 de Noviembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 11;
                break;

            case '22'://***<option value="22">Del 16 al 30 de Noviembre</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 11;
                break;

            case '23'://***<option value="23">Del 1ro al 15 de Diciembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 12;
                break;

            case '24'://***<option value="24">Del 16 al 31 de Diciembre</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 12;
                break;
        }

        $v_mes = ($v_mes < 10) ? '0'.$v_mes : $v_mes;

        $th_table = '';
        
        $td_tabla = "
            <th class='align-middle' scope='row'>".$num_empl." - ".$last_name." ".$first_name."</th>
        ";

        $int_color = 0;
        for ($i=$d_ini; $i <= $d_fin ; $i++) {
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

            $i = ($i < 10) ? '0'.$i : $i;
            $v_fecha = $slc_year.'-'.$v_mes.'-'.$i;
            $dia_dia = saber_dia($v_fecha);
            $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$num_empl' AND fecha LIKE '$v_fecha' ORDER BY fecha DESC";
            $exe_revisa = sqlsrv_query($conn, $query_revisa);
            $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
            if ($fila_revisa != NULL) {
                //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$num_empl' AND fecha LIKE '$v_fecha' ORDER BY fecha DESC";
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
            }else{
                $Hora_normal = array('Sin registro', 'Sin registro');
            }

            //***Codigo para obtener los permisos del dia corriente***
            $str_permisos = '';
            $emp_code_b = $num_empl;
            $f_recorre = $v_fecha;
            include ('../script_super/permisos_dia.php');
            //***Codigo para obtener los permisos del dia corriente***

            if (strlen($Hora_normal[0]) != '12') {
                $h_norm_A = new DateTime($Hora_normal[0]);
                $h_norm_B = new DateTime(end($Hora_normal));
                $diferencia_norm = $h_norm_A->diff($h_norm_B);
            } else {
                $h_norm_A = new DateTime("00:00:00");
                $h_norm_B = new DateTime("00:00:00");
                $diferencia_norm = $h_norm_A->diff($h_norm_B);
            }

            switch ($dia_dia) {
                case 'Lunes':
                    $he_days = $he_lun;
                    $hs_days = $hs_lun;
                    break;
                
                case 'Martes':
                    $he_days = $he_mar;
                    $hs_days = $hs_mar;
                    break;
    
                case 'Miercoles':
                    $he_days = $he_mie;
                    $hs_days = $hs_mie;
                    break;
    
                case 'Jueves':
                    $he_days = $he_jue;
                    $hs_days = $hs_jue;
                    break;
    
                case 'Viernes':
                    $he_days = $he_vie;
                    $hs_days = $hs_vie;
                    break;
    
                case 'Sabado':
                    $he_days = $he_sab;
                    $hs_days = $hs_sab;
                    break;
    
                case 'Domingo':
                    $he_days = $he_dom;
                    $hs_days = $hs_dom;
                    break;
            }

            $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$num_empl' AND f_recorre = '$f_recorre' AND aut_sup = '1'";
            $exe_master = sqlsrv_query($cnx, $query_master);
            $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
            $txt_textra = $fila_master['txt_textra'];
            $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
            $tot_min_n_new = $fila_master['tot_min_n_new'];
            $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
            $tot_min_e_new = $fila_master['tot_min_e_new'];

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

            if ($int_color == 0) {
                $color_td = "A4A4A4";
                $int_color = 1;
            } else {
                $color_td = "FFFFFF";
                $int_color = 0;
            }
            
            $td_tabla .= "
                <td class='align-middle' style='background-color: #".$color_td.";'>
                    <table>
                        <tr>
                            <td colspan='2'><strong><i>Horario laboral</i></strong></td>
                        </tr>
                        <tr>
                            <td colspan='2'><p style='font-size: 12px;'><strong>De ".$he_days." hrs a ".$hs_days." hrs</strong></p></td>
                        </tr>
                        ";
                    if (!empty($str_permisos)) {
                        $td_tabla .= "
                        <tr>
                            <td colspan='2'><small><i>".$str_permisos."</i></small></td>
                        </tr>";
                    }
            $td_tabla .= "
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
                                <td colspan='2'></td>
                            </tr> ";
                        }
                    if (!empty($Hora_extra[0])) {
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
                    }
                    $td_tabla .= "
                    </table>
                </td>
            ";
            $totales_extra += $tot_hrs_e_new;
        }

        $f_start = $slc_year."-".$v_mes."-".$d_ini;
        $f_end = $slc_year."-".$v_mes."-".$d_fin;
        for ($k=$d_ini; $k <= $d_fin ; $k++) {
            $k = ($k < 10) ? '0'.$k : $k ;
            $fechita = date($slc_year."-".$v_mes."-".$k);
            $dia_diita = saber_dia($fechita);
            $th_table .= '<th class="align-middle" scope="col"><center>'.$dia_diita.'<br>'.$fechita.'</center></th>';
        }
        ?>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div style="width:auto; height:auto; overflow:auto;">
                    <table class="table" id="table_quincena">
                        <thead>
                            <tr class="table-info">
                                <th class='align-middle' scope='col'>Nombre</th>
                                <?php echo($th_table); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo($td_tabla); ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th class='align-middle' scope='col'>Nombre</th>
                                <?php echo($th_table); ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <button type="button" class="btn btn-outline-primary" onclick="download_formatos_ext(2)">Descargar formatos</button>
            </div>
        </div>
        <?php
    }
?>
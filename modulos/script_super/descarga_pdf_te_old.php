<?php
    //error_reporting(E_ALL);
    session_start();
    $num_empleado_session = $_SESSION['num_empleado_a'];

    ini_set('max_execution_time', 0);
    date_default_timezone_set("America/Mazatlan");
    $ahora = date("Y-m-d_H_i_s");

    require_once '../../html2pdf_v4.03/html2pdf.class.php';
    include ('../../php/conn.php');

    $slc_year = $_POST['slc_year'];
    $slc_semana = $_POST['slc_semana'];
    $slc_quincena = $_POST['slc_quincena'];
    $slc_areas_p = $_POST['slc_areas_p'];
    $str_emps = $_POST['str_emps'];

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

    $obtener_depto_a = "SELECT department_id FROM personnel_employee WHERE emp_code = '$num_empleado_session'";
    $exe_depto_a = sqlsrv_query($cnx, $obtener_depto_a);
    $fila_depto_a = sqlsrv_fetch_array($exe_depto_a, SQLSRV_FETCH_ASSOC);
    $department_id = $fila_depto_a['department_id'];

    $obtener_depto_b = "SELECT dept_name FROM personnel_department WHERE id = '$department_id'";
    $exe_depto_b = sqlsrv_query($cnx, $obtener_depto_b);
    $fila_depto_b = sqlsrv_fetch_array($exe_depto_b, SQLSRV_FETCH_ASSOC);
    $dept_name = $fila_depto_b['dept_name'];

    $slc_areas_p = ($slc_areas_p == 'NA') ? $dept_name : $slc_areas_p ;

    if ($num_empleado_session == '2776') {
        $slc_areas_p = 'ALMACEN DE TRANSITO';
    }

    $txt_tipo_r = '';
    if (empty($slc_semana)) {
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

        //echo "d_ini: ".$d_ini." d_fin:".$d_fin;
        $f_start = strtotime($slc_year."-".$v_mes."-".$d_ini);
        $f_end = strtotime($slc_year."-".$v_mes."-".$d_fin);
        $txt_tipo_r = 'QUINCENA';
    }else{
        $semanas = getFirstDayWeek($slc_semana, $slc_year);
        $f_start = strtotime($semanas[start]);
        $f_end = strtotime($semanas[end]);
        $txt_tipo_r = 'SEMANA #'.$slc_semana;
    }

    
    $strHTML = '
        <page>
            <page_header>
            </page_header>
            <table style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th style="border: 3px solid; text-align: center;" colspan="18"><strong>TIEMPO EXTRA ('.$txt_tipo_r.' DEL '.date("Y-m-d", $f_start).' AL '.date("Y-m-d", $f_end).') Área: '.$slc_areas_p.'</strong></th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid; text-align: center;">Empleado</th>';
    if (!empty($slc_semana)) {
        for ($i=$f_start; $i <= $f_end ; $i+=86400) {
            $strHTML .= '
                <th style="border: 1px solid; text-align: center; font-size: 10px;">'.substr(saber_dia(date("Y-m-d", $i)), 0, 3).'<br>'.date("Y-m-d", $i).'</th>
            ';
        }
    }
    $strHTML .= '
                        <th style="border: 1px solid;">Totales</th>
                    </tr>
                </thead>
    ';

    if (empty($slc_semana)) {
        /*$file = fopen("Result.txt", "w");
        fwrite($file, print_r($str_emps, true) . PHP_EOL);
        fclose($file);*/
        $str_emps = str_replace("'", "", $str_emps);
        $str_emps_arr = explode(",", $str_emps);
        $strHTML .= '
                <tbody style="font-size: 12px;">';
        
        foreach ($str_emps_arr as $key => $value) {
            $tot_extras = 0;
            $query_shift_a = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$value'";
            $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
            $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
            $strHTML .= '
                    <tr>
                        <td style="border-bottom:1px; border: 1px solid; width: 180px; word-wrap: break-word;">
                            '.utf8_encode($fila_shift_a['last_name']).' '.utf8_encode($fila_shift_a['first_name']).'
                        </td>
            ';
            /*for ($i=$f_start; $i <= $f_end ; $i+=86400) {
                $f_recorre = date("Y-m-d", $i);
                $dia_semana = saber_dia($f_recorre);
                $sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '$value' AND rh_master_emptime.f_recorre = '$f_recorre'";
                $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
                $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
                if ($fila_date_emp == null) {
                    $tot_hrs_e_new_a = 0;
                } else {
                    $id_a = $fila_date_emp['id'];
                    $emp_code_a = $fila_date_emp['emp_code'];
                    $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
                    $f_recorre_a = substr($fila_date_emp, 5, 10);
                    $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
                    $txt_textra_a = $fila_date_emp['txt_textra'];
                    $estatus_a = $fila_date_emp['estatus'];
                }
                $strHTML .= '
                        <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                            <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 8px;">'.$txt_textra_a.'</p>
                        </td>';
                    $tot_extras += $tot_hrs_e_new_a;
            }*/
            $strHTML .= '
                        <td>
                            <table>
                                <!--<tr>
                                    <td>Lun</td><td>Mar</td><td>Mie</td><td>Jue</td><td>Vie</td><td>Sab</td><td>Dom</td>
                                </tr>-->';
                                $z = 0;
                                
                                $strHTML .= '<tr><td>';
                                for ($i=$f_start; $i <= $f_end ; $i+=86400) {
                                    $z++;
                                    $dia_del_semana = saber_dia(date("Y-m-d", $i));

                                    $f_recorre[$z] = date("Y-m-d", $i);
                                    /*$sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '$value' AND rh_master_emptime.f_recorre = '$f_recorre'";
                                    $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
                                    $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
                                    if ($fila_date_emp == null) {
                                        $tot_hrs_e_new_a = 0;
                                    } else {
                                        $id_a = $fila_date_emp['id'];
                                        $emp_code_a = $fila_date_emp['emp_code'];
                                        $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
                                        $f_recorre_a = substr($fila_date_emp, 5, 10);
                                        $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
                                        $txt_textra_a = $fila_date_emp['txt_textra'];
                                        $estatus_a = $fila_date_emp['estatus'];
                                    }*/


                                    if ($z == 1) {
                                        if ($dia_del_semana != 'Lunes') {
                                            switch ($dia_del_semana) {
                                                case 'Martes':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                    ';
                                                    break;
                                                
                                                case 'Miercoles':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Martes 00-00-0000</span>
                                                    ';
                                                    break;

                                                case 'Jueves':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Martes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Miercoles 00-00-0000</span>
                                                    ';
                                                    break;

                                                case 'Viernes':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Martes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Miercoles 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Jueves 00-00-0000</span>
                                                    ';
                                                    break;

                                                case 'Sabado':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Martes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Miercoles 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Jueves 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Viernes 00-00-0000</span>
                                                    ';
                                                    break;

                                                case 'Domingo':
                                                    $strHTML .= '
                                                        <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Martes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Miercoles 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Jueves 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Viernes 00-00-0000</span>
                                                        <span style="color: white; font-size: 10px;">Sabado 00-00-0000</span>
                                                    ';
                                                    break;
                                            }
                                        }
                                    }
                                    $strHTML .= '
                                        <!--<span>'.$dia_del_semana.' '.date("d-m-Y", $i).' '.$tot_hrs_e_new_a.' '.$txt_textra_a.'</span>-->
                                        <span style="font-size: 10px;">'.$dia_del_semana.'</span>
                                    ';
                                    if ($dia_del_semana == 'Domingo') {
                                        $strHTML .= '
                                            <span style="font-size: 10px;">Total</span><br>
                                        ';
                                        $tam_arr = 7 - count($f_recorre);
                                        for ($k=0; $k < $tam_arr; $k++) { 
                                            $strHTML .= '
                                                <span style="color: white; font-size: 10px;">Lunes 00-00-0000</span>
                                            ';
                                        }
                                        foreach ($f_recorre as $key_b => $value_b) {
                                            $sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '$value' AND rh_master_emptime.f_recorre = '$value_b'";
                                            $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
                                            $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
                                            if ($fila_date_emp == null) {
                                                $tot_hrs_e_new_a = 0;
                                            } else {
                                                $id_a = $fila_date_emp['id'];
                                                $emp_code_a = $fila_date_emp['emp_code'];
                                                $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
                                                $f_recorre_a = substr($fila_date_emp, 5, 10);
                                                $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
                                                $txt_textra_a = $fila_date_emp['txt_textra'];
                                                $estatus_a = $fila_date_emp['estatus'];
                                            }
                                            $strHTML .= '
                                                <span>'.$tot_hrs_e_new_a.' '.$txt_textra_a.'</span>
                                            ';
                                        }
                                        $strHTML .= '
                                                <span>15 horas</span>
                                            ';
                                        $strHTML .= '
                                            <br>
                                        ';
                                        $z = 0;
                                    }

                                    /*if ($z == 1) {
                                        if ($dia_del_semana != 'Lunes') {
                                            switch ($dia_del_semana) {
                                                case 'Martes':
                                                    $strHTML .= '
                                                        <td></td>
                                                    ';
                                                    break;
                                                
                                                case 'Miercoles':
                                                    $strHTML .= '
                                                        <td></td>
                                                        <td></td>
                                                    ';
                                                    break;

                                                case 'Jueves':
                                                    $strHTML .= '
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    ';
                                                    break;

                                                case 'Viernes':
                                                    $strHTML .= '
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    ';
                                                    break;

                                                case 'Sabado':
                                                    $strHTML .= '
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    ';
                                                    break;

                                                case 'Domingo':
                                                    $strHTML .= '
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    ';
                                                    break;
                                            }
                                        }
                                    }
                                    $strHTML .= '
                                        <td>'.$dia_del_semana.'</td>
                                    ';
                                    if ($dia_del_semana == 'Domingo') {
                                        $strHTML .= '
                                            <td>Total</td>
                                        ';
                                    }*/
                                }
                                $strHTML .= '</td></tr>';
                        $strHTML .= '
                            </table>
                        </td>
                        <!--<td style="text-align: center; border-bottom:1px; border: 1px solid;"><strong>'.$tot_extras.' horas</strong></td>-->
                    </tr>';
        }
        $strHTML .= '</tbody>';
    }else{
        $sql_emps = "SELECT DISTINCT emp_code FROM dbo.rh_master_emptime WHERE rh_master_emptime.year = '$slc_year' AND rh_master_emptime.n_semana = '$slc_semana' AND rh_master_emptime.emp_code IN ($str_emps)";
        $exe_emps = sqlsrv_query($cnx, $sql_emps);
        $i_candado = 0;
        while ($fila_emps = sqlsrv_fetch_array($exe_emps, SQLSRV_FETCH_ASSOC)) {
            $i_candado++;
            $emp_code_emps = $fila_emps['emp_code'];
            $query_shift_a = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_emps'";
            $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
            $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
            $tot_extras = 0;
            
            for ($i=$f_start; $i <= $f_end ; $i+=86400) {
                $f_recorre = date("Y-m-d", $i);
                $dia_semana = saber_dia($f_recorre);
                $sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '$emp_code_emps' AND rh_master_emptime.f_recorre = '$f_recorre'";
                $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
                $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
                if ($fila_date_emp == null) {
                    $tot_hrs_e_new_a = 0;
                } else {
                    $id_a = $fila_date_emp['id'];
                    $emp_code_a = $fila_date_emp['emp_code'];
                    $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
                    $f_recorre_a = substr($fila_date_emp, 5, 10);
                    $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
                    $txt_textra_a = $fila_date_emp['txt_textra'];
                    $estatus_a = $fila_date_emp['estatus'];
                }
                switch ($dia_semana) {
                    case 'Lunes':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_lun = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_lun = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
                    
                    case 'Martes':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_mar = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_mar = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
                    
                    case 'Miercoles':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_mie = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_mie = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Jueves':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_jue = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_jue = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Viernes':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_vie = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_vie = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Sabado':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_sab = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_sab = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Domingo':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_dom = '
                            <td style="text-align: center; border-bottom:1px;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_dom = '
                            <td style="text-align: center; border-bottom:1px;">
                                
                            </td>';
                        }
                        break;
                }
            }
            $strHTML .= '
                <tbody style="font-size: 12px;">
                    <tr>
                        <td style="border-bottom:1px; width: 180px; word-wrap: break-word;">'.$emp_code_emps.' - '.$fila_shift_a['last_name'].' '.$fila_shift_a['first_name'].'</td>
            ';
            $strHTML .= $t_lun.$t_mar.$t_mie.$t_jue.$t_vie.$t_sab.$t_dom.'
                        <td style="text-align: center; border-bottom:1px;"><strong>'.$tot_extras.' horas</strong></td>
                    </tr>
                </tbody>';
        }
    }
    $strHTML .= '            
            </table>
            <page_footer>
                <table style="width: auto; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="font-size: 15px; text-align: center;" width="180"><strong>__________________</strong></td>
                            <td style="font-size: 15px; text-align: center;" width="180"><strong>__________________</strong></td>
                            <td style="font-size: 15px; text-align: center;" width="180"><strong>__________________</strong></td>
                            <td style="font-size: 15px; text-align: center;" width="180"><strong>__________________</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size: 9px; text-align: center;" width="180">CREADO POR: <br>AUX.ADMINISTRATIVO</td>
                            <td style="font-size: 9px; text-align: center;" width="180">AUTORIZO: <br>DIRECTOR DE OPERACIONES</td>
                            <td style="font-size: 9px; text-align: center;" width="180">ENTERADO: LIC.SANTIAGO BOJALIL R.<br>Vo. Bo.  DIRECCION GENERAL</td>
                            <td style="font-size: 9px; text-align: center;" width="180">RECIBIDO: RECURSO HUMANOS</td>
                        </tr>
                    </tbody>
                </table>
            </page_footer>
        </page>
    ';

    $fichero_pdf = 'file/'.$slc_year.'_semana'.$slc_semana.'_'.$ahora.'.pdf';
    $html2pdf = new HTML2PDF('L', 'Letter', 'es', 'true', 'UTF-8');
    //***L -> Horizontal | P -> Vertical || A5 -> Media carta | Letter -> Carta
    $html2pdf->writeHTML($strHTML);
    $html2pdf->Output($fichero_pdf,'F');
    echo($fichero_pdf);
?>
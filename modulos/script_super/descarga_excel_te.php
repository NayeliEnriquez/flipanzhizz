<?php
    //error_reporting(E_ALL);
    session_start();
    $num_empleado_session = $_SESSION['num_empleado_a'];

    ini_set('max_execution_time', 0);
    date_default_timezone_set("America/Mazatlan");
    $ahora = date("Y-m-d_H_i_s");

    include ('../../php/conn.php');

    $slc_year = $_GET['slc_year'];
    $slc_semana = $_GET['slc_semana'];
    $slc_quincena = $_GET['slc_quincena'];
    $slc_areas_p = $_GET['slc_areas_p'];
    $str_emps = $_GET['str_emps'];

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
    }else{
        $semanas = getFirstDayWeek($slc_semana, $slc_year);
        $f_start = strtotime($semanas[start]);
        $f_end = strtotime($semanas[end]);
    }

    header("Content-type: text/html; charset=utf8");
    header("Content-Type:application/vnd.ms-excel; charset=UTF-8");
    header('Content-Disposition: attachment; filename=TiempoExtra_T_'.$ahora.'.xls');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    echo '
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="border: 3px solid; text-align: center;" colspan="9"><strong>TIEMPO EXTRA (SEMANA #'.$slc_semana.' DEL '.date("Y-m-d", $f_start).' AL '.date("Y-m-d", $f_end).') &Aacute;rea: '.$slc_areas_p.'</strong></th>
                </tr>
                <tr>
                    <th style="border: 1px solid; text-align: center;">Empleado</th>';
        for ($i=$f_start; $i <= $f_end ; $i+=86400) {
            echo '
                    <th style="border: 1px solid; text-align: center;">'.saber_dia(date("Y-m-d", $i)).'<br>'.date("Y-m-d", $i).'</th>
            ';
        }
        echo '
                    <th style="border: 1px solid;">Totales</th>
                </tr>
            </thead>
        ';
    if (empty($slc_semana)) {
        $str_emps = str_replace("'", "", $str_emps);
        $str_emps_arr = explode(",", $str_emps);
        foreach ($str_emps_arr as $key => $value) {
            $tot_extras = 0;
            $query_shift_a = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$value'";
            $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
            $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
            echo '
                <tbody style="font-size: 12px;">
                    <tr>
                        <td style="border-bottom:1px; border: 1px solid; width: 180px; word-wrap: break-word;">'.utf8_encode($fila_shift_a['last_name']).' '.utf8_encode($fila_shift_a['first_name']).'</td>
            ';
            for ($i=$f_start; $i <= $f_end ; $i+=86400) {
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
                echo '
                    <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                        <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 8px;">'.$txt_textra_a.'</p>
                    </td>';
                    $tot_extras += $tot_hrs_e_new_a;
            }
            echo '
                        <td style="text-align: center; border-bottom:1px; border: 1px solid;"><strong>'.$tot_extras.' horas</strong></td>
                    </tr>
                </tbody>';
        }
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
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_lun = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
                    
                    case 'Martes':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_mar = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_mar = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
                    
                    case 'Miercoles':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_mie = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_mie = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Jueves':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_jue = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_jue = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Viernes':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_vie = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_vie = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Sabado':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_sab = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_sab = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
        
                    case 'Domingo':
                        if ($tot_hrs_e_new_a > 0) {
                            $t_dom = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                <strong>'.$tot_hrs_e_new_a.'</strong><br><p style="font-size: 9px;">'.$txt_textra_a.'</p>
                            </td>';
                            $tot_extras += $tot_hrs_e_new_a;
                        } else {
                            $t_dom = '
                            <td style="text-align: center; border-bottom:1px; border: 1px solid;">
                                
                            </td>';
                        }
                        break;
                }
            }
            echo '
                <tbody style="font-size: 12px;">
                    <tr>
                        <td style="border-bottom:1px; border: 1px solid; width: 180px; word-wrap: break-word;">'.$fila_shift_a['last_name'].' '.$fila_shift_a['first_name'].'</td>
            ';
            echo $t_lun.$t_mar.$t_mie.$t_jue.$t_vie.$t_sab.$t_dom.'
                        <td style="text-align: center; border-bottom:1px; border: 1px solid;"><strong>'.$tot_extras.' horas</strong></td>
                    </tr>
                </tbody>';
        }
    }
        echo '
        </table>';

    
?>
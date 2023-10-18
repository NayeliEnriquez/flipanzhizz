<?php
    session_start();
    date_default_timezone_set("America/Mazatlan");
    ini_set('max_execution_time', 0);
    include ('../../php/conn.php');

    $num_empleado_session = $_SESSION['num_empleado_a'];
    $name_empleado_session = $_SESSION['name_a'];
    $fecha_now = date('Y-m-d H:i:s');
    $response_td = array();

    $slc_year = $_POST['slc_year'];
    $slc_quincena = $_POST['slc_semana'];
    $emp_code = $_POST['emp_code'];

    $totales_horas_semana = 0;
    $totales_minutos_semana = 0;
    $totales_horas_extra_semana = 0;
    $totales_minutos_extra_semana = 0;

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

    $f_start = strtotime($slc_year."-".$v_mes."-".$d_ini);
    $f_end = strtotime($slc_year."-".$v_mes."-".$d_fin);

    $d_ini = 1;
    $d_fin = 15;

    for ($i=$d_ini; $i <= $d_fin; $i++) { 
        $query_htotales = "SELECT * FROM [dbo].[rh_master_emptime] WHERE emp_code = '$emp_code_b' AND f_recorre = '".$slc_year."-".$v_mes."-".$i."'";
        $exe_htotales = sqlsrv_query($cnx, $query_htotales);
        $fila_htotales = sqlsrv_fetch_array($exe_htotales, SQLSRV_FETCH_ASSOC);
        $totales_horas_semana += $fila_htotales['tot_hrs_n_new'];
        $totales_minutos_semana += $fila_htotales['tot_min_n_new'];
        $totales_horas_extra_semana += $fila_htotales['tot_hrs_e_new'];
        $totales_minutos_extra_semana += $fila_htotales['tot_min_e_new'];
    }

    /*for ($k=$f_start; $k <= $f_end ; $k+=86400) {
        $fechita = date("Y-m-d", $k);
        echo "***".$query_htotales = "SELECT * FROM [dbo].[rh_master_emptime] WHERE emp_code = '$emp_code_b' AND f_recorre = '$fechita'";
        $exe_htotales = sqlsrv_query($cnx, $query_htotales);
        $fila_htotales = sqlsrv_fetch_array($exe_htotales, SQLSRV_FETCH_ASSOC);
        $totales_horas_semana += $fila_htotales['tot_hrs_n_new'];
        $totales_minutos_semana += $fila_htotales['tot_min_n_new'];
        $totales_horas_extra_semana += $fila_htotales['tot_hrs_e_new'];
        $totales_minutos_extra_semana += $fila_htotales['tot_min_e_new'];
    }*/

    $response_td['msg_success'] = "
        <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #1700ff; color: white;' value='$totales_horas_semana' readonly></td>
        <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #1700ff; color: white;' value='$totales_minutos_semana' readonly></td>
        <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #41009f; color: white;' value='$totales_horas_extra_semana' readonly></td>
        <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #41009f; color: white;' value='$totales_minutos_extra_semana' readonly></td>
    ";

    echo json_encode($response_td);
?>
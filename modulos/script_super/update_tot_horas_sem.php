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
    $slc_semana = $_POST['slc_semana'];
    $emp_code = $_POST['emp_code'];

    $totales_horas_semana = 0;
    $totales_minutos_semana = 0;
    $totales_horas_extra_semana = 0;
    $totales_minutos_extra_semana = 0;
    $query_htotales = "SELECT SUM(tot_hrs_n_new) AS td_a, SUM(tot_min_n_new) AS td_b, SUM(tot_hrs_e_new) AS td_c, SUM(tot_min_e_new) AS td_d FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code' AND n_semana = '$slc_semana'";
    $exe_htotales = sqlsrv_query($cnx, $query_htotales);
    $fila_htotales = sqlsrv_fetch_array($exe_htotales, SQLSRV_FETCH_ASSOC);
    $totales_horas_semana = $fila_htotales['td_a'];
    $totales_minutos_semana = $fila_htotales['td_b'];
    $totales_horas_extra_semana = $fila_htotales['td_c'];
    $totales_minutos_extra_semana = $fila_htotales['td_d'];

    $response_td['msg_success'] = "
        <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #1700ff; color: white;' value='$totales_horas_semana' readonly></td>
        <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #1700ff; color: white;' value='$totales_minutos_semana' readonly></td>
        <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #41009f; color: white;' value='$totales_horas_extra_semana' readonly></td>
        <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #41009f; color: white;' value='$totales_minutos_extra_semana' readonly></td>
    ";

    echo json_encode($response_td);
?>
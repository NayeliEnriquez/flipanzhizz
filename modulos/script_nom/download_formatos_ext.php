<?php
    include ('../../php/conn.php');
    $tipo = $_POST['tipo'];
    $slc_year = $_POST['slc_year'];
    $inp_num_empl = '';

    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
    //***
    
    if ($tipo == 1) {
        $slc_deptos = $_POST['slc_deptos'];
        $slc_semana = $_POST['slc_semana'];
        
        $semanas = getFirstDayWeek($slc_semana, $slc_year);
        $f_start = $semanas[start];
        $f_end = $semanas[end];

        $busca_depto = "SELECT dept_name FROM personnel_department WHERE id = '$slc_deptos'";
        $exe_depto = sqlsrv_query($conn, $busca_depto);
        $fila_depto = sqlsrv_fetch_array($exe_depto, SQLSRV_FETCH_ASSOC);
        $dept_name = $fila_depto['dept_name'];
    } else {
        $inp_num_empl = $_POST['inp_num_empl'];
        $slc_quincena = $_POST['slc_quincena'];
        $inp_dia_unico = $_POST['inp_dia_unico'];

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
        $d_ini = ($d_ini < 10) ? '0'.$d_ini : $d_ini;

        $busca_depto_a = "SELECT department_id FROM personnel_employee WHERE emp_code = '$inp_num_empl'";
        $exe_depto_a = sqlsrv_query($conn, $busca_depto_a);
        $fila_depto_a = sqlsrv_fetch_array($exe_depto_a, SQLSRV_FETCH_ASSOC);
        $department_id = $fila_depto_a['department_id'];

        $busca_depto_b = "SELECT dept_name FROM personnel_department WHERE id = '$department_id'";
        $exe_depto_b = sqlsrv_query($conn, $busca_depto_b);
        $fila_depto_b = sqlsrv_fetch_array($exe_depto_b, SQLSRV_FETCH_ASSOC);
        $dept_name = $fila_depto_b['dept_name'];

        if (empty($inp_dia_unico)) {
            $f_start = $slc_year.'-'.$v_mes.'-'.$d_ini;
            $f_end = $slc_year.'-'.$v_mes.'-'.$d_fin;
        } else {
            $f_start = $inp_dia_unico;
            $f_end = $inp_dia_unico;
        }
        
    }

    echo $f_start."|".$f_end."|".$dept_name."|".$inp_num_empl;
?>
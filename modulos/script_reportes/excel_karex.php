<?php
    ini_set('max_execution_time', 0);
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $slc_kardex = $_GET['slc_kardex'];
    $slc_year = $_GET['slc_year'];
    $list_num_empl_k = $_GET['list_num_empl_k'];

    function saber_dia($nombredia) {
        //$dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $dias = array('','','','','','','S','D');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    header("Content-type: text/html; charset=utf8");
    header("Content-Type:application/vnd.ms-excel; charset=UTF-8");
    if (!empty($list_num_empl_k)) {
        $sql_a = "SELECT * FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$list_num_empl_k'";
        header('Content-Disposition: attachment; filename=Kardex_'.$list_num_empl_k.'.xls');
    } else {
        $sql_a = "SELECT * FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE kardex_type = '$slc_kardex' ORDER BY CLAVE ASC";
        header('Content-Disposition: attachment; filename=Kardex_0'.$slc_kardex.'.xls');
    }
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    $exe_a = sqlsrv_query($cnx, $sql_a);
    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
        $kardex_type = $fila_a['kardex_type'];
        $CLAVE = $fila_a['CLAVE'];

        $query1 = "
            SELECT
                personnel_employee.id,
                personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
                personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name,
                dbo.personnel_employee.hire_date
            FROM
                personnel_employee
            INNER JOIN
                personnel_department
            ON
                personnel_employee.department_id = personnel_department.id
            INNER JOIN
                personnel_position
            ON
                personnel_employee.position_id = personnel_position.id
            WHERE
                personnel_employee.emp_code = '$CLAVE'
        ";
        $exe_1 = sqlsrv_query($conn, $query1);
        $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
        $first_name_1 = $fila_1['first_name'];
        $last_name_1 = $fila_1['last_name'];
        $department_id_1 = $fila_1['department_id'];
        $dept_name_1 = $fila_1['dept_name'];
        $position_id_1 = $fila_1['position_id'];
        $position_name_1 = $fila_1['position_name'];
        $hire_date_1 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_1['hire_date'])))), 5, 10);

        echo '
            <table style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="border: 3px solid; text-align: center;">
                        <th>'.$CLAVE.'</th>
                        <th colspan="15">'.$last_name_1.' '.$first_name_1.'</th>
                        <th colspan="10">'.$position_name_1.'</th>
                        <th colspan="5">'.$hire_date_1.'</th>
                        <th>Alta</th>
                    </tr>
                    <tr>
                        <th></th><th></th><th></th><th></th><th></th>
                        <th></th><th></th><th></th><th></th><th></th>
                        <th></th><th></th><th></th><th></th><th></th>
                        <th></th><th></th><th></th><th></th><th></th>
                        <th></th><th></th><th></th><th></th><th></th>
                        <th></th><th></th><th></th><th></th><th></th>
                    </tr>
                </thead>
            </table>
        ';

        echo '
            <!--<table class="table table-bordered border-success" style="display: block; white-space: nowrap; overflow-x: auto; width:auto;">-->
            <table style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="border: 1px solid; text-align: center; background-color: blue;">
                        <td style="font-size: 12px;">'.$slc_year.'</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            echo '<td style="font-size: 12px;">'.$i.'</td>';

                        }
        echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Enero</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-01-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            
                            include('action_search.php');
                        }
        echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Febrero</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-02-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            if (($i == '31') || ($i == '30') || ($i == '29')) {
                                echo '<td style="background-color:black;"></td>';
                            }else{
                                include('action_search.php');
                            }
                        }
        echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Marzo</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-03-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Abril</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-04-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            if (($i == '31')) {
                                echo '<td style="background-color:black;"></td>';
                            }else{
                                include('action_search.php');
                            }
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Mayo</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-05-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Junio</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-06-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            if (($i == '31')) {
                                echo '<td style="background-color:black;"></td>';
                            }else{
                                include('action_search.php');
                            }
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Julio</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-07-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Agosto</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-08-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Septiembre</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-09-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            if (($i == '31')) {
                                echo '<td style="background-color:black;"></td>';
                            }else{
                                include('action_search.php');
                            }
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Octubre</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-10-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Noviembre</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-11-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            if (($i == '31')) {
                                echo '<td style="background-color:black;"></td>';
                            }else{
                                include('action_search.php');
                            }
                        }
            echo '
                    </tr>
                    <tr style="border: 1px solid; text-align: center;">
                        <td style="font-size: 12px;">Diciembre</td>
                        ';
                        for ($i=1; $i <= 31; $i++) { 
                            if ($i < 10) {
                                $i = '0'.$i;
                            }
                            $fecha_this = $slc_year."-12-".$i;
                            $dia_dia = saber_dia($fecha_this);

                            $sql_b = "SELECT COUNT(id) AS es_festivo FROM [rh_novag_system].[dbo].[rh_festivos] WHERE dia_festivo = '$fecha_this'";
                            $exe_b = sqlsrv_query($cnx, $sql_b);
                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                            $es_festivo = $fila_b['es_festivo'];
                            include('action_search.php');
                        }
            echo '
                    </tr>
                </thead>
            </table>
            <br><br>
        ';
    }
?>
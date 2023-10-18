<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $ahora = date("Y-m-d H:i:s");
    
    $action = $_GET['action'];
    $year = $_GET['year'];
    $week = $_GET['week'];
    $emps = $_GET['emps'];
    $correo = $_GET['correo'];

    switch ($action) {
        case '1'://***APROVAR
            $sql_emps = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.year = '$year' AND rh_master_emptime.n_semana = '$week' AND rh_master_emptime.emp_code IN ($emps) AND estatus = '1'";
            $exe_emps = sqlsrv_query($cnx, $sql_emps);
            while ($fila_emps = sqlsrv_fetch_array($exe_emps, SQLSRV_FETCH_ASSOC)) {
                if ($fila_emps['estatus'] == '1') {
                    $sql_emps_u = "UPDATE dbo.rh_master_emptime SET estatus = '2', historico = CONCAT(historico, '|Aprobado a travez del correo ".$correo." con fecha ".$ahora."')  WHERE id = '".$fila_emps[id]."'";
                    sqlsrv_query($cnx, $sql_emps_u);
                }
            }
            ?>
            <script languaje='javascript' type='text/javascript'>
                if (alert('Horario aprobado')) {
                    window.close()
                }
            </script>
            <?php
            break;
        
        case '2':
            # code...
            break;
    }
?>
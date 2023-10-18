<?php
    ini_set('max_execution_time', 0);
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $slc_year = $_POST['slc_year'];
    $slc_semana = $_POST['slc_semana'];
    $slc_pla = $_POST['slc_pla'];


    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
    //***
    function saber_dia($nombredia) {
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $semanas = getFirstDayWeek($slc_semana, $slc_year);
    //$semanas[start]." al ".$semanas[end];
    ?>
    <table class="table table-dark table-hover align-middle" id="tb_semana">
        <thead>
            <tr>
                <th># Empleado</th>
                <th>Nombre</th>
                <th>D&iacute;a</th>
                <th>Fecha</th>
                <th>Acciones</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $recorrido = 0;

    //$query_a = "SELECT TOP (5) CLAVE FROM rh_employee_gen WHERE NOMINA LIKE '$slc_pla' ORDER BY [NOMBRE COMPLETO] ASC";
    $query_a = "SELECT CLAVE FROM rh_employee_gen WHERE CLAVE = '4017'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
        $cve_emp = $fila_a['CLAVE'];
        $f_start = strtotime($semanas[start]);
        $f_end = strtotime($semanas[end]);
        for ($i=$f_start; $i <= $f_end ; $i+=86400) { 
            $f_recorre = date("Y-m-d", $i);
            $query_b = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$cve_emp' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
            $exe_b = sqlsrv_query($conn, $query_b);
            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
            if ($fila_b != NULL) {
                $recorrido++;
                $query_c = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code = '$cve_emp'";
                $exe_c = sqlsrv_query($cnx, $query_c);
                $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
                $first_name_b = $fila_c['first_name'];
                $last_name_b = $fila_c['last_name'];

                $punch_time_b = $fila_b['punch_time'];
                $fecha_b = $fila_b['fecha'];
                $Hora_b = $fila_b['Hora'];
                $terminal_alias_b = $fila_b['terminal_alias'];

                echo "
                <tr style='font-size: 12px;'>
                    <td scope='row'>XXX".$cve_emp."</td>
                    <td>".$last_name_b."&nbsp;".$first_name_b."</td>
                    <td>".saber_dia($fecha_b)."</td>
                    <td>".$fecha_b."</td>
                    <td><button type='button' class='btn btn-outline-success btn-sm' data-bs-toggle='modal' data-bs-target='#mdl_dtl_sem' onclick='dtl_sem($recorrido, $cve_emp, $fecha_b)'>Detalles</button></td>
                    <td><div id='sts_".$recorrido."'><svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-x-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='9' y1='9' x2='15' y2='15'></line><line x1='15' y1='9' x2='9' y2='15'></line></svg></div></td>
                </tr>
                ";
            }
        }
    }

    /*$query_a = "SELECT * FROM [zkbiotime].[dbo].[Push] WHERE emp_code = '3053' AND fecha LIKE '2023-01-12' ORDER BY fecha DESC";
    $exe_a = sqlsrv_query($conn, $query_a);
	while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
		echo "<br>".var_dump($fila_a['punch_time']);
	}*/
?>
        </tbody>
        <tfoot>
            <tr>
                <th># Empleado</th>
                <th>Nombre</th>
                <th>D&iacute;a</th>
                <th>Fecha</th>
                <th>Acciones</th>
                <th>Estatus</th>
            </tr>
        </tfoot>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="mdl_dtl_sem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdl_dtl_semLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="mdl_dtl_semLabel">Detalles</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="response_dtl_sem"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_semana').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
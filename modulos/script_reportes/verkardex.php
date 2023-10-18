<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $slc_kardex_v = $_POST['slc_kardex_v'];

    switch ($slc_kardex_v) {
        case '1':
            $name_file = "KARDEX 01 MANTENIMIENTO OFICINAS";
            break;
        
        case '2':
            $name_file = "";
            break;

        case '3':
            $name_file = "KARDEX 03 QUINCENA TLALPAN";
            break;

        case '4':
            $name_file = "KARDEX 04 ALMACEN TLALPAN";
            break;

        case '5':
            $name_file = "KARDEX 05 PRODUCCION TLALPAN";
            break;

        case '6':
            $name_file = "KARDEX 06 MANTENIMIENTO GENERAL";
            break;

        case '7':
            $name_file = "KARDEX 07 ALMACEN IZTAPALAPA";
            break;

        case '8':
            $name_file = "";
            break;
        
        case '9':
            $name_file = "KARDEX 09 LABORATORIO ANALISIS";
            break;
    }
?>
<div class="row">
    <div class="col-md-4">
        <br>
        <button type="button" class="btn btn-primary btn-sm" onclick="activa_div_kardex()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        </button>
        <div id="div_empleados_kardex" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <label for="inp_num_empl_k" class="form-label">Numero de empleado(Enter)</label>
                    <input class="form-control" list="datalistOptions" id="inp_num_empl_k" placeholder="Busqueda..." onkeyup="busca_emp_k(event)">
                    <datalist id="datalistOptions">
                        <?php
                            $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                            $exe_1 = sqlsrv_query($cnx, $query1);
                            while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                $emp_code_1 = $fila_1['emp_code'];
                                $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                echo '
                                    <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                ';
                            }
                        ?>
                    </datalist>
                </div>
            </div>
            <div id="response_busca_emp_k">
                <input type="hidden" class="form-control" id="inp_fname_sup" value="">
            </div>
            <br>
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-success me-md-2 btn-sm" type="button" onclick="guarda_emp_kardex()">Guardar</button>
                    <button class="btn btn-warning btn-sm" type="button" onclick="limpia_emp_kardex()">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered border-info table-sm" id="tb_emps_kardexs">
            <thead>
                <tr>
                    <th scope="col">Num. Empleado</th>
                    <th scope="col">Nombre completo</th>
                </tr>
            </thead>
            <tbody>
        <?php
            $sql_a = "SELECT * FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE kardex_type = '$slc_kardex_v' ORDER BY [NOMBRE COMPLETO] ASC";
            $exe_a = sqlsrv_query($cnx, $sql_a);
            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                $clve = $fila_a['CLAVE'];
                $name_full = $fila_a['NOMBRE COMPLETO'];
                echo '
                <tr class="align-middle">
                    <th scope="row">'.$clve.'</th>
                    <td>'.$name_full.'</td>
                </tr>
                ';
            }
        ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_emps_kardexs').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: '<?php echo($name_file); ?>',
                text:'Descargar Excel'
            }]
        });
    });
</script>
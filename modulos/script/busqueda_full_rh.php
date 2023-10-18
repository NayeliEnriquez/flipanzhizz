<?php
   $slc_formato = $_POST['slc_formato'];
?>
<table class="table table-dark table-hover align-middle" id="tb_busq_rh">
    <thead>
        <tr>
            <th scope="col"><center>Folio</center></th>
            <th scope="col">Empleado</th>
            <th scope="col">Solicitud</th>
            <th scope="col"><?php echo $encabe_dates = ($slc_formato == 'rh_vacaciones') ? 'Fechas solicitadas' : 'Fecha de solicitud' ; ?></th>
            <th scope="col">Estatus</th>
            <th scope="col">Detalles</th>
        </tr>
    </thead>
    <tbody>
<?php
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    include ('../../php/conn.php');

    $inp_f_busqueda = $_POST['inp_f_busqueda'];
    $inp_num_empl_rh = $_POST['inp_num_empl_rh'];
    $query_complemento = '';
    $query_full = '';

    if ((!empty($inp_f_busqueda) && (empty($inp_num_empl_rh)))) {//***Busqueda por fecha
        $query_complemento = "tab.f_solicitud >= '$inp_f_busqueda 00:00:00.000' AND tab.f_solicitud <= '$inp_f_busqueda 23:59:59.000'";
    }elseif ((empty($inp_f_busqueda) && (!empty($inp_num_empl_rh)))) {//***Busqueda por numero de empleado
        $query_complemento = "tab.id_empleado = '$inp_num_empl_rh'";
    }elseif ((!empty($inp_f_busqueda) && (!empty($inp_num_empl_rh)))) {//***Busqueda por fecha y numero de empleado
        $query_complemento = "tab.f_solicitud >= '$inp_f_busqueda 00:00:00.000' AND tab.f_solicitud <= '$inp_f_busqueda 23:59:59.000' AND tab.id_empleado = '$inp_num_empl_rh'";
    }else{
        echo "
            <tr>
                <th scope='row'></th>
                <td><center>Busqueda por</center></td>
                <td><center>Fecha</center></td>
                <td><center>y/o</center></td>
                <td><center>Empleado</center></td>
                <td></td>
            </tr>
        ";
        exit();
    }

    switch ($slc_formato) {
        case 'ALL':
            $query_full = $slc_formato;
            break;
        
        default:

            if ($slc_formato == 'rh_vacaciones') {
               $comp_v = ", tab.fecha_array";
            }else{
               $comp_v = "";
            }

            $query_full = "SELECT tab.id, tab.tipo_ausencia, tab.f_solicitud, tab.id_empleado, pe.first_name, pe.last_name, tab.estatus".$comp_v." FROM  $slc_formato tab INNER JOIN personnel_employee pe ON pe.emp_code = tab.id_empleado WHERE ".$query_complemento;
            $exe_full = sqlsrv_query($cnx, $query_full);
            while ($fila_full = sqlsrv_fetch_array($exe_full, SQLSRV_FETCH_ASSOC)) {
                $contador++;
                $v_accion = 'ver';
                $id_empleado_c = $fila_full['id_empleado'];
                $first_name_c = utf8_encode($fila_full['first_name']);
                $last_name_c = utf8_encode($fila_full['last_name']);
                $id_c = $fila_full['id'];
                $tipo_ausencia_c = $fila_full['tipo_ausencia'];
                $f_solicitud_c = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['f_solicitud'])))), 5, 16)." hrs";
                $estatus_c = $fila_full['estatus'];
                switch ($estatus_c) {
                     case '0':
                        $estatus_c_desc = 'En espera';
                        break;

                     case '1':
                        $estatus_c_desc = 'Aprobado';
                        break;
                     
                     case '2':
                        $estatus_c_desc = 'Rechazado';
                        break;

                     case '3':
                        $estatus_c_desc = 'Aprobado';
                        break;
                }
                
                if (($estatus_c == '1' || $estatus_c == '3') && ($slc_formato == 'rh_vacaciones')) {
                    $v_accion = 'aprob';
                }

                if ($slc_formato == 'rh_vacaciones') {
                  $f_solicitud_c = $fila_full['fecha_array'];
                }

                echo "
                    <tr>
                        <th scope='row'><center>".$id_c."</center></th>
                        <td>".$id_empleado_c." - ".$last_name_c." ".$first_name_c."</td>
                        <td>".$tipo_ausencia_c."</td>
                        <td>".$f_solicitud_c."</td>
                        <td>".$estatus_c_desc."</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud_RH' onclick='ver_solicitud_rh(".$id_c.", `".$slc_formato."`, `".$v_accion."`)'>
                                Detalles
                            </button>
                        </td>
                    </tr>
                ";
            }
            break;
    }    
?>
    </tbody>
</table>

<div class="modal fade" id="Mdl_ver_solicitud_RH" tabindex="-1" aria-labelledby="Mdl_ver_solicitud_RHLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Mdl_ver_solicitud_RHLabel">Ver solicitud</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response_ver_solicitud_rh"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <div id="response_ver_solicitud_rh_buttons"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_busq_rh').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            "order": [3, 'asc']
        });
    });
</script>
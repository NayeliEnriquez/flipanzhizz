<?php
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('../../php/conn.php');

    $slc_formato = $_POST['slc_formato'];
    $inp_folio = $_POST['inp_folio'];
    $inp_num_empl_h = $_POST['inp_num_empl_h'];

    $query_b = "
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
            personnel_employee.emp_code = '$inp_num_empl_h'
    ";
    $exe_b = sqlsrv_query($cnx, $query_b);
    $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
    $first_name_b = $fila_b['first_name'];
    $last_name_b = $fila_b['last_name'];
    $department_id_b = $fila_b['department_id'];
    $dept_name_b = $fila_b['dept_name'];
    $position_id_b = $fila_b['position_id'];
    $position_name_b = $fila_b['position_name'];
    $hire_date_b = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_b['hire_date']))));
    $hire_date_b = substr($hire_date_b, 5, 10);

?>
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <label for="inp_fname" class="form-label">Nombre(s)</label>
            <input type="text" class="form-control" id="inp_fname" value="<?php echo($first_name_b); ?>" readonly>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="inp_lname" class="form-label">Apellido(s)</label>
            <input type="text" class="form-control" id="inp_lname" value="<?php echo($last_name_b); ?>" readonly>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="inp_depto" class="form-label">Departamento</label>
            <input type="text" class="form-control" id="inp_depto" value="<?php echo($dept_name_b); ?>" readonly>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="inp_pos" class="form-label">Posici&oacute;n</label>
            <input type="text" class="form-control" id="inp_pos" value="<?php echo($position_name_b); ?>" readonly>
        </div>
    </div>
<?php
    if (empty($inp_folio)) {
        $query_a = "SELECT * FROM $slc_formato WHERE id_empleado = '$inp_num_empl_h' ORDER BY f_solicitud DESC";
        $exe_a = sqlsrv_query($cnx, $query_a);
        switch ($slc_formato) {
            case 'rh_solicitudes':
                echo '
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Folio</th>
                                <th scope="col">Fecha solicitud</th>
                                <th scope="col">Tipo ausencia</th>
                                <th scope="col">Sueldo</th>
                                <th scope="col">Observaciones</th>
                                <th scope="col">Estatus</th>
                                <th scope="col">Imprimir</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                        $folio_a = $fila_a['id'];
                        $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
                        $f_solicitud_a = substr($f_solicitud_a, 5, 10);
                        $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                        $tipo_goce_a = $fila_a['tipo_goce'];
                        $observaciones_a = $fila_a['observaciones'];
                        $estatus_a = $fila_a['estatus'];
                        //$estatus_adesc = ($estatus_a == '1') ? 'Solicitud aprobada' : 'Solicitud rechazada' ;
                        switch ($estatus_a) {
                            case '1':
                                $estatus_adesc = '<td style="background-color: #63C14E;">Solicitud aprobada</td>';
                                break;
                        
                            case '2':
                                $estatus_adesc = '<td style="background-color: #DB5C4C;">Solicitud rechazada</td>';
                                break;

                            default:
                                $estatus_adesc = '<td style="background-color: #D6EEEE;">Solicitud en espera</td>';
                                break;
                        }
                        echo '
                            <tr>
                                <td>'.$folio_a.'</td>
                                <td>'.$f_solicitud_a.'</td>
                                <td>'.$tipo_ausencia_a.'</td>
                                <td>'.$tipo_goce_a.'</td>
                                <td>'.$observaciones_a.'</td>
                                '.$estatus_adesc.'
                                <td>
                                    <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$folio_a.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                                </td>
                            </tr>
                        ';
                    }
                echo '                            
                        </tbody>
                    </table>
                ';
                break;
            
            case 'rh_salida':
                echo '
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Folio</th>
                                <th scope="col">Fecha solicitud</th>
                                <th scope="col">Tipo de permiso</th>
                                <th scope="col">Fecha y hora de permiso</th>
                                <th scope="col">Reposici&oacute;n</th>
                                <th scope="col">Observaciones</th>
                                <th scope="col">Estatus</th>
                                <th scope="col">Imprimir</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                        $folio_a = $fila_a['id'];
                        $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
                        $f_solicitud_a = substr($f_solicitud_a, 5, 10);
                        $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                        $f_permiso_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_permiso'])))), 5, 10);
                        $h_permiso_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_permiso'])))), 16, 5);
                        $fh_permiso = $f_permiso_a." ".$h_permiso_a;
                        $reposicion_a = $fila_a['reposicion'];
                        $repo_desc = ($reposicion_a == '0') ? 'N/A' : $fila_a['hrs_repo']." hr(s) el ".substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_h_repo'])))), 5, 16);
                        $observaciones_a = $fila_a['observaciones'];
                        $estatus_a = $fila_a['estatus'];
                        //$estatus_adesc = ($estatus_a == '1') ? 'Solicitud aprobada' : 'Solicitud rechazada' ;
                        switch ($estatus_a) {
                            case '1':
                            case '3':
                                $estatus_adesc = '<td style="background-color: #63C14E;">Solicitud aprobada</td>';
                                break;
                        
                            case '2':
                                $estatus_adesc = '<td style="background-color: #DB5C4C;">Solicitud rechazada</td>';
                                break;

                            default:
                                $estatus_adesc = '<td style="background-color: #D6EEEE;">Solicitud en espera</td>';
                                break;
                        }
                        echo '
                            <tr>
                                <td>'.$folio_a.'</td>
                                <td>'.$f_solicitud_a.'</td>
                                <td>'.$tipo_ausencia_a.'</td>
                                <td>'.$fh_permiso.'</td>
                                <td>'.$repo_desc.'</td>
                                <td>'.$observaciones_a.'</td>
                                '.$estatus_adesc.'
                                <td>
                                    <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$folio_a.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                                </td>
                            </tr>
                        ';
                    }
                echo '
                        </tbody>
                    </table>
                ';
                break;

            case 'rh_vacaciones':
                echo '
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Folio</th>
                                <th scope="col">Fecha solicitud</th>
                                <th scope="col">Dias de vacaciones</th>
                                <th scope="col">Fechas</th>
                                <th scope="col">Observaciones</th>
                                <th scope="col">Estatus</th>
                                <th scope="col">Imprimir</th>
                            </tr>
                        </thead>
                        <tbody>';
                        while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                            $folio_a = $fila_a['id'];
                            $f_solicitud_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud'])))), 5, 10);
                            $dias_tot = $fila_a['dias_vac'];
                            $fecha_array_v = substr(str_replace("|", "■", $fila_a['fecha_array']), 0, -3);
                            $observaciones_a = $fila_a['observaciones'];
                            $estatus_a = $fila_a['estatus'];
                            //$estatus_adesc = ($estatus_a == '1') ? 'Solicitud aprobada' : 'Solicitud rechazada' ;
                            switch ($estatus_a) {
                                case '1':
                                    $estatus_adesc = '<td style="background-color: #63C14E;">Solicitud aprobada</td>';
                                    break;
                            
                                case '2':
                                    $estatus_adesc = '<td style="background-color: #DB5C4C;">Solicitud rechazada</td>';
                                    break;
    
                                default:
                                    $estatus_adesc = '<td style="background-color: #D6EEEE;">Solicitud en espera</td>';
                                    break;
                            }
                            echo '
                                <tr>
                                    <td>'.$folio_a.'</td>
                                    <td>'.$f_solicitud_a.'</td>
                                    <td>'.$dias_tot.'</td>
                                    <td>'.$fecha_array_v.'</td>
                                    <td>'.$observaciones_a.'</td>
                                    '.$estatus_adesc.'
                                    <td>
                                        <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$folio_a.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                                    </td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                ';
                break;
        }
    }else{
        $query_a = "SELECT * FROM $slc_formato WHERE id = '$inp_folio' AND id_empleado = '$inp_num_empl_h'";
        $exe_a = sqlsrv_query($cnx, $query_a);
        $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
        if ($fila_a === false) {
    ?>
        <hr class="my-4">
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>&nbsp;&nbsp;&nbsp;&nbsp;No se encontraron coincidencias, favor de verificar su informaci&oacute;n.
        </div>
    <?php
        }else{
            switch ($slc_formato) {
                case 'rh_solicitudes':
                    $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                    $tipo_ausencia_sel_1 = "disabled";
                    $tipo_ausencia_sel_2 = "disabled";
                    $tipo_ausencia_sel_3 = "disabled";
                    $tipo_ausencia_sel_4 = "disabled";
                    switch ($tipo_ausencia_a) {
                        case 'Permiso':
                            $tipo_ausencia_sel_1 = "checked";
                            break;
                        
                        case 'Comision':
                            $tipo_ausencia_sel_2 = "checked";
                            break;
        
                        case 'Suspension':
                            $tipo_ausencia_sel_3 = "checked";
                            break;
        
                        case 'Amonestacion':
                            $tipo_ausencia_sel_4 = "checked";
                            break;
                    }
                    $f_ini_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini']))));
                    $f_ini_a = substr($f_ini_a, 5, 10);
                    $f_fin_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin']))));
                    $f_fin_a = substr($f_fin_a, 5, 10);
                    $tipo_goce_a = $fila_a['tipo_goce'];
                    $tipo_goce_sel_1 = "disabled";
                    $tipo_goce_sel_2 = "disabled";
                    switch ($tipo_goce_a) {
                        case 'Con goce de sueldo':
                            $tipo_goce_sel_1 = "checked";
                            break;
                        
                        case 'Sin goce de sueldo':
                            $tipo_goce_sel_2 = "checked";
                            break;
                    }
                    $observaciones_a = $fila_a['observaciones'];
                    $f_solicitud_a = $fila_a['f_solicitud'];
                    $id_solicitante_a = $fila_a['id_solicitante'];
                    $id_depto_a = $fila_a['id_depto'];
                    $estatus_a = $fila_a['estatus'];
                    ?>
                    <div class="row centered">
                        <h4 class="mb-3">Tipo de ausencia</h4>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input id="chk_permiso" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_1); ?> value="1">
                                <label class="form-check-label" for="chk_permiso">Permiso</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input id="chk_comision" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_2); ?> value="2">
                                <label class="form-check-label" for="chk_comision">Comisi&oacute;n</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input id="chk_susp" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_3); ?> value="3">
                                <label class="form-check-label" for="chk_susp">Suspensi&oacute;n</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input id="chk_amon" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_4); ?> value="4">
                                <label class="form-check-label" for="chk_amon">Amonestaci&oacute;n</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="inp_finicial" class="form-label">Fecha inicial</label>
                            <input id="inp_finicial" class="form-control" type="date" value="<?php echo($f_ini_a); ?>" readonly>
                            <div class="invalid-feedback">
                                Colocar fecha inicial.
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="inp_ffinal" class="form-label">Fecha final</label>
                            <input id="inp_ffinal" class="form-control" type="date" value="<?php echo($f_fin_a); ?>" readonly>
                            <div class="invalid-feedback">
                            Colocar fecha final.
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <br><br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_goce" id="chk_c_goce" <?php echo($tipo_goce_sel_1); ?> value="1">
                                <label class="form-check-label" for="chk_c_goce">
                                    Con goce de sueldo
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <br><br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_goce" id="chk_s_goce" <?php echo($tipo_goce_sel_2); ?> value="2">
                                <label class="form-check-label" for="chk_s_goce">
                                    Sin goce de sueldo
                                </label>
                            </div>
                        </div>
                    </div>
                    <p class="lead">
                        NOTA: Cualquier permiso con goce de sueldo deberá ser autorizado por el Director General y/o Director Administrativo.
                    </p>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs" readonly><?php echo($observaciones_a); ?></textarea>
                                <label for="txt_obs">Observaciones</label>
                                <div class="invalid-feedback">
                                    Colocar algun observacion o causa de la ausencia.
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;
                
                case 'rh_salida':
                    $tipo_ausencia_a = $fila_a['tipo_ausencia'];
                    $in_out_a = $fila_a['in_out'];
                    $chk_in = "disabled";
                    $chk_out = "disabled";
                    switch ($in_out_a) {
                        case '1'://***entrada
                            $chk_in = "checked";
                            break;
                        
                        case '2'://***salida
                            $chk_out = "checked";
                            break;
                    }
                    $asunto_a = $fila_a['asunto'];
                    $chk_job = "disabled";
                    $chk_per = "disabled";
                    switch ($asunto_a) {
                        case '1':
                            $chk_job = "checked";
                            break;
                        
                        case '2':
                            $chk_per = "checked";
                            break;
                    }
                    $sueldo_a = $fila_a['sueldo'];
                    $chk_c_goce = "disabled";
                    $chk_s_goce = "disabled";
                    switch ($sueldo_a) {
                        case '1':
                            $chk_c_goce = "checked";
                            break;
                        
                        case '2':
                            $chk_s_goce = "checked";
                            break;
                    }
                    $f_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_permiso']))));
                    $f_permiso_a = substr($f_permiso_a, 5, 10);
                    $h_permiso_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_permiso']))));
                    $h_permiso_a = substr($h_permiso_a, 16, 8);
                    $observaciones_a = $fila_a['observaciones'];
                    $reposicion_a = $fila_a['reposicion'];
                    $chk_reponer_a = "disabled";
                    if ($reposicion_a == '1') {
                        $chk_reponer_a = "checked disabled";
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_in" value="1" <?php echo($chk_in); ?>>
                                <label class="form-check-label" for="rdo_in">
                                    Permitir entrada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_out" value="2" <?php echo($chk_out); ?>>
                                <label class="form-check-label" for="rdo_out">
                                    Permitir salida
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_job" value="1" <?php echo($chk_job); ?>>
                                <label class="form-check-label" for="rdo_job">
                                    Asunto de trabajo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_per" value="2" <?php echo($chk_per); ?>>
                                <label class="form-check-label" for="rdo_per">
                                    Asunto personal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_goce" id="rdo_c_goce" value="1" <?php echo($chk_c_goce); ?>>
                                <label class="form-check-label" for="rdo_c_goce">
                                    Con goce de sueldo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdo_goce" id="rdo_s_goce" value="2" <?php echo($chk_s_goce); ?>>
                                <label class="form-check-label" for="rdo_s_goce">
                                    Sin goce de sueldo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <br>
                            <label for="inp_fecha_p" class="form-label">Fecha del permiso</label>
                            <input id="inp_fecha_p" class="form-control" type="date" value="<?php echo($f_permiso_a); ?>" min="<?php echo($fecha_now); ?>" readonly>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <br>
                            <label for="inp_hora_p" class="form-label">Hora del permiso</label>
                            <input id="inp_hora_p" class="form-control" type="time" value="<?php echo($h_permiso_a); ?>" readonly>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <br><br>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Destino y motivo" id="txt_obs" readonly><?php echo($observaciones_a); ?></textarea>
                                <label for="txt_obs">Destino y motivo</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" value="Reponer" id="chk_reponer" <?php echo($chk_reponer_a); ?>>
                                    <label class="form-check-label" for="chk_reponer">Repondra tiempo</label>
                                </li>
                            </ul>
                        </div>            
                    </div>
                    <?php
                    break;
        
                case 'rh_vacaciones':
                    $f_ini_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini']))));
                    $f_ini_v = substr($f_ini_v, 5, 10);
                    $f_fin_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin']))));
                    $f_fin_v = substr($f_fin_v, 5, 10);
                    $h_in_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_in']))));
                    $h_in_v = substr($h_in_v, 16, 8);
                    $h_out_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_out']))));
                    $h_out_v = substr($h_out_v, 16, 8);
                    $hire_date_1 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ingreso']))));
                    $hire_date_1 = substr($hire_date_1, 5, 10);
                    $observaciones_a = $fila_a['observaciones'];
                    $fecha_regreso_v = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['fecha_regreso'])))), 5, 10);
                    $fecha_array_v = explode("|", substr($fila_a['fecha_array'], 0, -1));
                    ?>
                    <div class="row">
                        <!--<div class="col-md-3 col-sm-12">
                            <label for="inp_finicial" class="form-label">Fecha inicial</label>
                            <input id="inp_finicial" class="form-control" type="date" value="<?php echo($f_ini_v); ?>" readonly>
                            <div class="invalid-feedback">
                                Colocar fecha inicial.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="inp_ffinal" class="form-label">Fecha final</label>
                            <input id="inp_ffinal" class="form-control" type="date" value="<?php echo($f_fin_v); ?>" readonly>
                            <div class="invalid-feedback">
                                Colocar fecha final.
                            </div>
                        </div>-->
                        <div class="col-md-3 col-sm-12">
                            <label for="inp_hora_i" class="form-label">Horario de entrada</label>
                            <input id="inp_hora_i" class="form-control" type="time" value="<?php echo($h_in_v); ?>" readonly>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="inp_hora_s" class="form-label">Horario de salida</label>
                            <input id="inp_hora_s" class="form-control" type="time" value="<?php echo($h_out_v); ?>" readonly>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="inp_f_hire" class="form-label">Fecha de ingreso</label>
                            <input id="inp_f_hire" class="form-control" type="date" value="<?php echo($hire_date_1); ?>" readonly>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="inp_f_regreso" class="form-label">Reincorporandose el dia</label>
                            <input id="inp_f_regreso" class="form-control" type="date" value="<?php echo($fecha_regreso_v); ?>" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <table border="1" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><center>#</center></th>
                                        <th><center>Fecha</center></th>
                                    </tr>
                                    <?php
                                        $num_z = 0;
                                        foreach ($fecha_array_v as $key => $value) {
                                            $num_z++;
                                            echo '
                                            <tr>
                                                <td>
                                                    <center>'.$num_z.'</center>
                                                </td>
                                                <td>
                                                    <center><input class="form-control" type="date" name="inp_arrdates[]" value="'.$value.'" readonly></center>
                                                </td>
                                            </tr>
                                            ';
                                        }
                                    ?>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-3 col-sm-12">
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs" readonly><?php echo($observaciones_a); ?></textarea>
                                <label for="txt_obs">Observaciones</label>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;
            }
            $estatus_sol = $fila_a['estatus'];
            switch ($estatus_sol) {
                case '1':
                case '3':
                    $color_sts = 'success';
                    $txt_estatus = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>&nbsp;&nbsp;&nbsp;&nbsp;Solicitud aprobada.';
                    $col_n = '10';
                    $div_md = '
                        <div class="col-md-2 col-sm-12">
                            <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$inp_folio.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                        </div>
                    ';
                    break;
                
                case '2':
                    $color_sts = 'danger';
                    $txt_estatus = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>&nbsp;&nbsp;&nbsp;&nbsp;Solicitud rechazada';
                    $col_n = '10';
                    $div_md = '';
                    //$col_n = '12';
                    //$div_md = '';//***MOSTRAR LA OPCION DE DESCARGAR PDF HASTA NUEVO AVISO
                    $div_md = '
                        <div class="col-md-2 col-sm-12">
                            <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$inp_folio.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                        </div>
                    ';
                    break;
                
                default:
                    $color_sts = 'warning';
                    $txt_estatus = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-watch"><circle cx="12" cy="12" r="7"></circle><polyline points="12 9 12 12 13.5 13.5"></polyline><path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83m.01-10.7l.35-3.83A2 2 0 0 1 9.83 1h4.35a2 2 0 0 1 2 1.82l.35 3.83"></path></svg>&nbsp;&nbsp;&nbsp;&nbsp;Solicitud en espera de revisi&oacute;n';
                    //$col_n = '12';
                    //$div_md = '';//***MOSTRAR LA OPCION DE DESCARGAR PDF HASTA NUEVO AVISO
                    $col_n = '10';
                    $div_md = '
                        <div class="col-md-2 col-sm-12">
                            <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`'.$slc_formato.'`, '.$inp_folio.', '.$inp_num_empl_h.')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></button>
                        </div>
                    ';
                    break;
            }
    ?>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-<?php echo($col_n); ?> col-sm-12">
                <div class="alert alert-<?php echo($color_sts); ?> d-flex align-items-center" role="alert">
                    <?php echo($txt_estatus); ?>
                </div>
            </div>
            <?php echo($div_md); ?>
        </div>
    <?php
        }
    }
    ?>
<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $y_today = date('Y');
    session_start();
    /*while($post = each($_SESSION)){
        echo $post[0]." = ".$post[1]."<br>";
    }*/
    $num_empleado_session = $_SESSION['num_empleado_a'];

    $id_table = $_POST['id_table'];
    $table = $_POST['table'];
    $accion = $_POST['accion'];
    $query_a = "SELECT * FROM $table WHERE id = '$id_table'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $id_empleado_a = $fila_a['id_empleado'];
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
            personnel_employee.emp_code = '$id_empleado_a'
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
<input type="hidden" name="inp_id_table" id="inp_id_table" value="<?php echo($id_table); ?>">
<input type="hidden" name="inp_table" id="inp_table" value="<?php echo($table); ?>">
<div class="row">
    <div class="col-md-3 col-sm-12">
        <label for="inp_fname" class="form-label">Nombre(s)</label>
        <input type="text" class="form-control" id="inp_fname" value="<?php echo(utf8_encode($first_name_b)); ?>" readonly>
    </div>
    <div class="col-md-3 col-sm-12">
        <label for="inp_lname" class="form-label">Apellido(s)</label>
        <input type="text" class="form-control" id="inp_lname" value="<?php echo(utf8_encode($last_name_b)); ?>" readonly>
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
<br>
<?php
    switch ($table) {
        case 'rh_solicitudes':
            $tipo_ausencia_a = $fila_a['tipo_ausencia'];
            $tipo_ausencia_sel_1 = "";
            $tipo_ausencia_sel_2 = "";
            $tipo_ausencia_sel_3 = "";
            $tipo_ausencia_sel_4 = "";
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

                case 'Otros':
                    $tipo_ausencia_sel_4 = "checked";
                    break;
            }
            $f_ini_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini']))));
            $f_ini_a = substr($f_ini_a, 5, 10);
            $f_fin_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin']))));
            $f_fin_a = substr($f_fin_a, 5, 10);
            $tipo_goce_a = $fila_a['tipo_goce'];
            $tipo_goce_sel_1 = "";
            $tipo_goce_sel_2 = "";
            switch ($tipo_goce_a) {
                case 'Con goce de sueldo':
                    $tipo_goce_sel_1 = "checked";
                    break;
                
                case 'Sin goce de sueldo':
                    $tipo_goce_sel_2 = "checked";
                    break;
            }
            $historico_a = $fila_a['historico'];
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
                        <input id="chk_permiso" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_1); ?> disabled value="1">
                        <label class="form-check-label" for="chk_permiso">Permiso</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-check">
                        <input id="chk_comision" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_2); ?> disabled value="2">
                        <label class="form-check-label" for="chk_comision">Comisi&oacute;n</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-check">
                        <input id="chk_susp" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_3); ?> disabled value="3">
                        <label class="form-check-label" for="chk_susp">Suspensi&oacute;n</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-check">
                        <input id="chk_amon" name="chk_ausencia" type="radio" class="form-check-input" <?php echo($tipo_ausencia_sel_4); ?> disabled value="4">
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
                        <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs"><?php echo($observaciones_a); ?></textarea>
                        <label for="txt_obs">Observaciones</label>
                        <div class="invalid-feedback">
                            Colocar algun observacion o causa de la ausencia.
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-floating">
                        <textarea class="form-control" id="txt_his" readonly><?php echo($historico_a); ?></textarea>
                        <label for="txt_obs">Historico</label>
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
            $chk_in = "";
            $chk_out = "";
            switch ($in_out_a) {
                case '1'://***entrada
                    $chk_in = "checked";
                    break;
                
                case '2'://***salida
                    $chk_out = "checked";
                    break;
            }
            $asunto_a = $fila_a['asunto'];
            $chk_job = "";
            $chk_per = "";
            switch ($asunto_a) {
                case '1':
                    $chk_job = "checked";
                    break;
                
                case '2':
                    $chk_per = "checked";
                    break;
            }
            $sueldo_a = $fila_a['sueldo'];
            $chk_c_goce = "";
            $chk_s_goce = "";
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
            $historico_a = $fila_a['historico'];
            $observaciones_a = $fila_a['observaciones'];
            $reposicion_a = $fila_a['reposicion'];
            $chk_reponer_a = "";
            if ($reposicion_a == '1') {
                $chk_reponer_a = "checked";
            }
            ?>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_in" value="1" <?php echo($chk_in); ?> disabled>
                        <label class="form-check-label" for="rdo_in">
                            Permitir entrada
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_out" value="2" <?php echo($chk_out); ?> disabled>
                        <label class="form-check-label" for="rdo_out">
                            Permitir salida
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_job" value="1" <?php echo($chk_job); ?> disabled>
                        <label class="form-check-label" for="rdo_job">
                            Asunto de trabajo
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_per" value="2" <?php echo($chk_per); ?> disabled>
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
                    <input id="inp_fecha_p" class="form-control" type="date" value="<?php echo($f_permiso_a); ?>" min="<?php echo($fecha_now); ?>" required>
                </div>
                <div class="col-md-3 col-sm-12">
                    <br>
                    <label for="inp_hora_p" class="form-label">Hora del permiso</label>
                    <input id="inp_hora_p" class="form-control" type="time" value="<?php echo($h_permiso_a); ?>" required>
                </div>
                <div class="col-md-6 col-sm-12">
                    <br><br>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Destino y motivo" id="txt_obs"><?php echo($observaciones_a); ?></textarea>
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
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-floating">
                        <textarea class="form-control" id="txt_his" readonly><?php echo($historico_a); ?></textarea>
                        <label for="txt_obs">Historico</label>
                        <div class="invalid-feedback">
                            Colocar algun observacion o causa de la ausencia.
                        </div>
                    </div>
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
            $historico_a = $fila_a['historico'];
            $fecha_array_v = explode("|", substr($fila_a['fecha_array'], 0, -1));
            $estatus_v = $fila_a['estatus'];
            $dia_lun = $fila_a['dia_lun'];
            $lun_chk = ($dia_lun == '1') ? 'checked' : '' ;
            $dia_mar = $fila_a['dia_mar'];
            $mar_chk = ($dia_mar == '1') ? 'checked' : '' ;
            $dia_mie = $fila_a['dia_mie'];
            $mie_chk = ($dia_mie == '1') ? 'checked' : '' ;
            $dia_jue = $fila_a['dia_jue'];
            $jue_chk = ($dia_jue == '1') ? 'checked' : '' ;
            $dia_vie = $fila_a['dia_vie'];
            $vie_chk = ($dia_vie == '1') ? 'checked' : '' ;
            $dia_sab = $fila_a['dia_sab'];
            $sab_chk = ($dia_sab == '1') ? 'checked' : '' ;
            $dia_dom = $fila_a['dia_dom'];
            $dom_chk = ($dia_dom == '1') ? 'checked' : '' ;

            //************************
            function daysWeek($inicio, $fin){
                $start = new DateTime($inicio);
                $end = new DateTime($fin);

                //de lo contrario, se excluye la fecha de finalización (¿error?)
                $end->modify('+1 day');
                $interval = $end->diff($start);

                // total dias
                $days = $interval->days;

                // crea un período de fecha iterable (P1D equivale a 1 día)
                $period = new DatePeriod($start, new DateInterval('P1D'), $end);

                // almacenado como matriz, por lo que puede agregar más de una fecha feriada
                //$holidays = array('2012-09-07', '2022-01-03', '2022-02-01', '2022-03-21', '2022-04-14', '2022-04-15', '2022-09-16', '2022-11-21', '2022-12-21');
                $holidays = array('2023-01-01', '2023-02-06', '2023-03-20', '2023-05-01', '2023-09-16', '2023-11-20', '2023-12-25');

                foreach($period as $dt) {
                    $curr = $dt->format('D');

                    // obtiene si es Sábado o Domingo
                    if($curr == 'Sat' || $curr == 'Sun') {
                        $days--;
                    }elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                        $days--;
                    }
                }

                //echo $days;
                return($days);
            }
            //************************
            //$total_dias_sol = daysWeek($f_ini_v, $f_fin_v);
            $inp_f_hire = date($hire_date_1);
            //***sumar 1 año
            $v_hire_one = date("Y-m-d",strtotime($inp_f_hire."+ 1 year"));
            
            /*$query_b = "SELECT vacation_rule FROM personnel_employee WHERE emp_code = '$id_empleado_a'";//***Numero de vacaciones restantes en el ciclo
            $exe_b = sqlsrv_query($cnx, $query_b);
            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
            $vacation_rule = $fila_b['vacation_rule'];*/

            $v_mensaje = 'Se solicitan <strong>'.count($fecha_array_v).'</strong> dia(s) de vacaciones.';
            
            ?>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <table border="1" class="table table-striped" id="tb_fechas_v">
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
                                            <center><input class="form-control" type="date" name="inp_arrdates[]" value="'.$value.'"></center>
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
                    <label for="inp_f_hire" class="form-label">Fecha de ingreso</label>
                    <input id="inp_f_hire" class="form-control" type="date" value="<?php echo($hire_date_1); ?>" readonly>
                </div>
                <div class="col-md-6 col-sm-12">
                    <br>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs"><?php echo($observaciones_a); ?></textarea>
                        <label for="txt_obs">Observaciones</label>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <center><label>Jornada laboral actual</label></center>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_lun" value="Lunes" <?php echo($lun_chk); ?> disabled>
                        <label class="form-check-label" for="chk_lun">Lunes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_mar" value="Martes" <?php echo($mar_chk); ?> disabled>
                        <label class="form-check-label" for="chk_mar">Martes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_mie" value="Miercoles" <?php echo($mie_chk); ?> disabled>
                        <label class="form-check-label" for="chk_mie">Miercoles</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_jue" value="Jueves" <?php echo($jue_chk); ?> disabled>
                        <label class="form-check-label" for="chk_jue">Jueves</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_vie" value="Viernes" <?php echo($vie_chk); ?> disabled>
                        <label class="form-check-label" for="chk_vie">Viernes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_sab" value="Sabado" <?php echo($sab_chk); ?> disabled>
                        <label class="form-check-label" for="chk_sab">Sabado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="chk_dom" value="Domingo" <?php echo($dom_chk); ?> disabled>
                        <label class="form-check-label" for="chk_dom">Domingo</label>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label for="inp_hora_i" class="form-label">Horario de entrada</label>
                    <input id="inp_hora_i" class="form-control" type="time" value="<?php echo($h_in_v); ?>" readonly>
                </div>
                <div class="col-md-2 col-sm-12">
                    <label for="inp_hora_s" class="form-label">Horario de salida</label>
                    <input id="inp_hora_s" class="form-control" type="time" value="<?php echo($h_out_v); ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                </div>
                <div class="col-md-6 col-sm-12">
                    <p><?php echo($v_mensaje); ?></p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
               <?php
                  $query_a = "SELECT hire_date FROM personnel_employee WHERE emp_code = '$id_empleado_a'";
                  $exe_a = sqlsrv_query($conn, $query_a);
                  $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                  $hire_date = date(substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10));
                  $v_hire_one = date("Y-m-d",strtotime($hire_date."+ 1 year"));
               
                  $v_vence_dias = date("Y",strtotime($fecha_hora_now."- 18 month"));
               
                  if ($fecha_now >= $v_hire_one) {
                     $color_card = "info";
               
                     $hire_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 10, 5);
               
                     for ($i=$v_vence_dias; $i <= $y_today; $i++) { 
                        $v_caduca = date("Y-m-d",strtotime($i."-".$hire_date_2."+ 18 month"));//SE SUMAN 30 MESES POR QUE SON 18 MESES DE LA CADUCIDAD Y 12 MESES POR EL AÑO TRANSCURRIDO DE TRABAJO, EJEMPLO UNA PERSONA ENTRA EL 1/06/22, CUMPLE UN AÑO EL 1/06/23 ENTONCES A PARTIR DE ESTA FECHA PUEDE HACER USO DE VACACIONES Y SE CUENTAN 18 MESES DE CADUCIDAD, EN ESTE EJEMPLO LAS VACACIONES CADUCAN EL 1/12/24
                        if ($fecha_now > $v_caduca) {
                              continue;
                        }
                        $query_a = "SELECT y_$i FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$id_empleado_a'";
                        $exe_a = sqlsrv_query($cnx, $query_a);
                        $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                        $y_dias_v = $fila_a['y_'.$i];
                        if ($y_dias_v > 0) {
                              $vacation_rule .= "Cuenta con <strong>".$y_dias_v."</strong> dias disponibles del periodo <strong>".$i."</strong><br><small>(Fecha de caducidad <b>".$v_caduca.")</b></small> <br>";
                        }
                     }
                  }else{
                     $color_card = "danger";
                     $vacation_rule = "Aun no cumple el año laboral, bajo aprobaci&oacute;n del siguiente periodo.";
                  }
               ?>
               <center>
                  <div class="card text-bg-<?php echo($color_card); ?> mb-3no" style="max-width: 24rem;">
                     <div class="card-header">Vacaciones restantes</div>
                        <div class="card-body">
                              <p class="card-text"><?php echo($vacation_rule); ?></p>
                        </div>
                     </div>
                  </div>
               </center>
            </div>
            <hr class="my-4">
            <div class="row">
               <div class="col-md-12 col-sm-12">
                  <div class="form-floating">
                     <textarea class="form-control" id="txt_his" readonly><?php echo($historico_a); ?></textarea>
                     <label for="txt_obs">Historico</label>
                     <div class="invalid-feedback">
                           Colocar algun observacion o causa de la ausencia.
                     </div>
                  </div>
               </div>
            </div>
            ||
            <?php
                if ($accion == 'aprob') {
            ?>
                <button type="button" class="btn btn-danger btn-sm" id="btn_act2" onclick="actions_solicitud_rh(2)">Revisado y rechazado</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_act1" onclick="actions_solicitud_rh(1)">Revisado y aprobado</button>
            <?php
                }
            ?>
            <?php
            break;
    }
?>
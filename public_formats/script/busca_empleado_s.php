<script>
    $(document).ready(function(){
        $("#rdo_c_goce").click(function(){
            let selected_rdo_inout = $("input[type='radio'][name='rdo_inout']:checked");
            selected_rdo_inout_Val = selected_rdo_inout.val();
            if (selected_rdo_inout_Val == '1') {
                $("#div_in").show();
                $("#div_out").hide();
            }else{
                $("#div_in").hide();
                $("#div_out").show();
            }
        });
        $("#rdo_s_goce").click(function(){
            $("#div_in").hide();
            $("#div_out").hide();
        });
        $("#rdo_in").click(function(){
            let selected_rdo_goce = $("input[type='radio'][name='rdo_goce']:checked");
            selected_rdo_goce_Val = selected_rdo_goce.val();
            if (selected_rdo_goce_Val == '1') {
                $("#div_in").show();
                $("#div_out").hide();
            } else {
                $("#div_in").hide();
                $("#div_out").hide();
            }
        });
        $("#rdo_out").click(function(){
            let selected_rdo_goce = $("input[type='radio'][name='rdo_goce']:checked");
            selected_rdo_goce_Val = selected_rdo_goce.val();
            if (selected_rdo_goce_Val == '1') {
                $("#div_in").hide();
                $("#div_out").show();
            } else {
                $("#div_in").hide();
                $("#div_out").hide();
            }
        });
    
        $("#chk_reponer").click(function(){
            confirma = $('#chk_reponer').is(':checked'); 
            
            if (confirma == true) {
                $("#div_reposicion").show();
                document.getElementById("inp_fhrepo").focus();
            } else {
                $("#div_reposicion").hide();
            }
        })

        $("#rdo_job").click(function(){
            $("#div_goce").show();
        });
        $("#rdo_per").click(function(){
            $("#div_goce").hide();
        });
    });
</script>
<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $inp_num_empl = $_POST['inp_num_empl_s'];

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
            personnel_employee.emp_code = '$inp_num_empl'
    ";
    $exe_1 = sqlsrv_query($conn, $query1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    if ($fila_1 == NULL) {
        //echo "5";
        echo '
        <br>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <div>
                <h4>Sin coincidencias, favor de intentarlo nuevamente o contactar a su administrador de sistema.</h4>
            </div>
        </div>
        ';
        exit();
    }
    $id_bd_1 = $fila_1['id'];
    $first_name_1 = $fila_1['first_name'];
    $last_name_1 = $fila_1['last_name'];
    $department_id_1 = $fila_1['department_id'];
    $dept_name_1 = $fila_1['dept_name'];
    $position_id_1 = $fila_1['position_id'];
    $position_name_1 = $fila_1['position_name'];
    //$hire_date_1 = $fila_1['hire_date'];
    $hire_date_1 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_1['hire_date']))));
    $hire_date_1 = substr($hire_date_1, 5, 10);
    echo '
        <input type="hidden" id="inp_id_empleado" value="'.$inp_num_empl.'">
        <input type="hidden" id="inp_id_depto" value="'.$department_id_1.'">
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="inp_fname" class="form-label">Nombre(s)</label>
                <input type="text" class="form-control" id="inp_fname" value="'.utf8_encode($first_name_1).'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_lname" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="inp_lname" value="'.utf8_encode($last_name_1).'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_depto" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto" value="'.$dept_name_1.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_pos" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos" value="'.$position_name_1.'" readonly>
            </div>
        </div>
    ';
?>
        <br>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_in" value="1">
                    <label class="form-check-label" for="rdo_in">
                        Permitir entrada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_inout" id="rdo_out" value="2" checked>
                    <label class="form-check-label" for="rdo_out">
                        Permitir salida
                    </label>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_job" value="1">
                    <label class="form-check-label" for="rdo_job">
                        Asunto de trabajo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_asunto" id="rdo_per" value="2" checked>
                    <label class="form-check-label" for="rdo_per">
                        Asunto personal
                    </label>
                </div>
            </div>
            <div class="col-md-4 col-sm-12" style="display:none;" id="div_goce">
                <br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_goce" id="rdo_c_goce" value="1">
                    <label class="form-check-label" for="rdo_c_goce">
                        Con goce de sueldo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rdo_goce" id="rdo_s_goce" value="2" checked>
                    <label class="form-check-label" for="rdo_s_goce">
                        Sin goce de sueldo
                    </label>
                </div>
            </div>
        </div>
        <br>
        <div class="row" id="div_in" style="display:none;">
            <div class="col-md-4 col-sm-12">
                <label for="inp_hr_in" class="form-label">Hora laboral de entrada</label>
                <input id="inp_hr_in" class="form-control" type="time">
                <div class="invalid-feedback">
                    Colocar hora entrada.
                </div>
            </div>
        </div>
        <div class="row" id="div_out" style="display:none;">
            <div class="col-md-4 col-sm-12">
                <label for="inp_hr_out" class="form-label">Hora laboral de salida</label>
                <input id="inp_hr_out" class="form-control" type="time">
                <div class="invalid-feedback">
                    Colocar hora salida.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <br>
                <label for="inp_fecha_p" class="form-label">Fecha del permiso</label>
                <input id="inp_fecha_p" class="form-control" type="date" value="<?php echo($fecha_now); ?>" required>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <label for="inp_hora_p" class="form-label">Hora del permiso</label>
                <input id="inp_hora_p" class="form-control" type="time" required>
            </div>
            <div class="col-md-6 col-sm-12">
                <br><br>
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Destino y motivo" id="txt_obs"></textarea>
                    <label for="txt_obs">Destino y motivo</label>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <br>
                <ul class="list-group">
                    <li class="list-group-item">
                        <input class="form-check-input me-1" type="checkbox" value="Reponer" id="chk_reponer">
                        <label class="form-check-label" for="chk_reponer">Repondra tiempo</label>
                    </li>
                </ul>
            </div>
            <div class="col-md-5 col-sm-12">
                <!--<p style="font-size: 12px;"><strong>Si va a reponer tiempo coloque la fecha y el horario de reposici&oacute;n en la casilla "Destino y motivo".</strong></p>-->
                <div id="div_reposicion" style="display:none;">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="inp_fhrepo" class="form-label">Fecha de reposici&oacute;n</label>
                            <input type="datetime-local" class="form-control" id="inp_fhrepo" name="inp_fhrepo">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="inp_hrrepo" class="form-label">Total de horas a reponer</label>
                            <input type="number" min="1" class="form-control" id="inp_hrrepo" name="inp_hrrepo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <div id="response_empleado_s">
                    <button class="w-100 btn btn-primary btn-lg" id="btn_send" onclick="frm_ausencias_s()">Enviar solicitud</button>
                </div>
            </div>
        </div>
<script>
    $(document).ready(function(){
        $("#chk_c_goce").click(function(){
            $("#div_in_out").show();
        });
        $("#chk_s_goce").click(function(){
            $("#div_in_out").hide();
        });
    });
    //***Permisos
    $(document).ready(function(){
        $("#chk_permiso").click(function(){
            $("#div_ppersonal").show();
            $("#div_pagos").show();
            $("#div_mensaje_a").hide();
            $("#div_mensaje_b").hide();
        });
        $("#chk_comision").click(function(){
            $("#div_ppersonal").hide();
            $("#div_pagos").show();
            $("#div_file").hide();
            $("#div_mensaje_a").show();
            $("#div_mensaje_b").hide();
        });
        $("#chk_susp").click(function(){
            $("#div_ppersonal").hide();
            $("#div_pagos").hide();
            $("#div_in_out").hide();
            $("#div_file").hide();
            $("#div_mensaje_a").hide();
            $("#div_mensaje_b").show();
        });
        $("#chk_amon").click(function(){
            $("#div_ppersonal").hide();
            $("#div_pagos").hide();
            $("#div_in_out").hide();
            $("#div_file").hide();
            $("#div_mensaje_a").hide();
            $("#div_mensaje_b").show();
        });
    });
    //***
    $(document).ready(function() {
        $("input[type='radio'][name='chk_permisos']").click(function(){
            let selected_rdo_permisos = $("input[type='radio'][name='chk_permisos']:checked");
            if (selected_rdo_permisos.length > 0) {
                rdo_tipo_Val_permisos = selected_rdo_permisos.val();
            }

            if ((rdo_tipo_Val_permisos == 3) || (rdo_tipo_Val_permisos == 4)) {
                $("#div_pagos").hide();
                $("#div_file").hide();
            }else{
                $("#div_pagos").show();
                $("#div_file").show();
            }
        });
    })
</script>
<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $inp_num_empl = $_POST['inp_num_empl'];

    $query1 = "
        SELECT
            personnel_employee.id,
            personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
            personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name
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
    $exe_1 = sqlsrv_query($cnx, $query1);
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
            <div class="col-md-6 col-sm-12">
                <h4 class="mb-3">Tipo de ausencia</h4>
                <div class="my-3">
                    <div class="form-check form-check-inline">
                        <input id="chk_permiso" name="chk_ausencia" type="radio" class="form-check-input" checked required value="1">
                        <label class="form-check-label" for="chk_permiso">Permiso</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="chk_comision" name="chk_ausencia" type="radio" class="form-check-input" required value="2">
                        <label class="form-check-label" for="chk_comision">Comisi&oacute;n</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="chk_susp" name="chk_ausencia" type="radio" class="form-check-input" required value="3">
                        <label class="form-check-label" for="chk_susp">Suspensi&oacute;n</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="chk_amon" name="chk_ausencia" type="radio" class="form-check-input" required value="4">
                        <label class="form-check-label" for="chk_amon">Otros</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div id="div_ppersonal">
                    <h4 class="mb-3">Tipo de permiso</h4>
                    <div class="my-3">
                        <div class="form-check form-check-inline">
                            <input id="chk_pater" name="chk_permisos" type="radio" class="form-check-input" checked required value="1">
                            <label class="form-check-label" for="chk_pater">Paternidad</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="chk_defun" name="chk_permisos" type="radio" class="form-check-input" required value="2">
                            <label class="form-check-label" for="chk_defun">Defunsi&oacute;n</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="chk_medico" name="chk_permisos" type="radio" class="form-check-input" required value="3">
                            <label class="form-check-label" for="chk_medico">Incapacidad interna(Uso exclusivo medico Novag)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="chk_otros" name="chk_permisos" type="radio" class="form-check-input" required value="4">
                            <label class="form-check-label" for="chk_otros">Otros(Especificar en observaciones)</label>
                        </div>
                    </div>
                </div>
                <div id="div_mensaje_a" style="display:none;">
                    <div class="alert alert-info" role="alert">
                        Ingresar datos de la comisión, lugar y horario en el campo de observaciones.
                    </div>
                </div>
                <div id="div_mensaje_b" style="display:none;">
                    <div class="alert alert-info" role="alert">
                    Ingresar el motivo en el campo de observaciones.
                    </div>
                </div>
            </div>
        </div>
        <div id="div_file">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="inp_file">
                        <label class="input-group-text" for="inp_file" accept=".pdf,.jpg,.png">Archivo justificante (opcional)</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <label for="inp_finicial" class="form-label">Fecha inicial</label>
                <input id="inp_finicial" class="form-control" type="date" value="<?php echo($fecha_now); ?>" required>
                <div class="invalid-feedback">
                    Colocar fecha inicial.
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="inp_ffinal" class="form-label">Fecha final</label>
                <input id="inp_ffinal" class="form-control" type="date" value="<?php echo($fecha_now); ?>" required>
                <div class="invalid-feedback">
                Colocar fecha final.
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <br>
                <div id="div_pagos">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_goce" id="chk_c_goce" value="1">
                        <label class="form-check-label" for="chk_c_goce">
                            Con goce de sueldo
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rdo_goce" id="chk_s_goce" value="2" checked>
                        <label class="form-check-label" for="chk_s_goce">
                            Sin goce de sueldo
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="div_in_out" style="display:none;">
            <div class="col-md-4 col-sm-12">
                <label for="inp_hr_in" class="form-label">Hora entrada</label>
                <input id="inp_hr_in" class="form-control" type="time" >
                <div class="invalid-feedback">
                    Colocar hora entrada.
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="inp_hr_out" class="form-label">Hora salida</label>
                <input id="inp_hr_out" class="form-control" type="time" >
                <div class="invalid-feedback">
                    Colocar hora salida.
                </div>
            </div>
        </div>
        <p class="lead">
            NOTA: Cualquier permiso con goce de sueldo deberá ser autorizado por el Director General y/o Director Administrativo.
        </p>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Observaciones y/o causas" id="txt_obs" required></textarea>
                    <label for="txt_obs">Observaciones</label>
                    <div class="invalid-feedback">
                        Colocar algun observacion o causa de la ausencia.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div id="response_empleado_aus">
                    <button class="w-100 btn btn-primary btn-lg" id="btn_send" onclick="frm_ausencias()">Enviar solicitud</button>
                </div>
            </div>
        </div>
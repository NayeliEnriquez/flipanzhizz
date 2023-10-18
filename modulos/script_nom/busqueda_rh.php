<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $inp_num_empl = $_POST['inp_num_empl'];
    $slc_deptos = $_POST['slc_deptos'];
    $year_current = date('Y');

    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }

    if (($inp_num_empl != '') && ($slc_deptos == 0)) {//***BUSQUEDA POR NUMERO DE EMPLEADO
        $query1 = "SELECT
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
        $exe_1 = sqlsrv_query($cnx, $query1);
        $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
        $id_bd_1 = $fila_1['id'];
        $first_name_1 = $fila_1['first_name'];
        $last_name_1 = $fila_1['last_name'];
        $department_id_1 = $fila_1['department_id'];
        $dept_name_1 = $fila_1['dept_name'];
        $position_id_1 = $fila_1['position_id'];
        $position_name_1 = $fila_1['position_name'];
        $hire_date_1 = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_1['hire_date']))));
        $hire_date_1 = substr($hire_date_1, 5, 10);

        $sql_a = "SELECT NOMINA FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$inp_num_empl'";
        $exe_a = sqlsrv_query($cnx, $sql_a);
        $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
        $NOMINA_a = $fila_a['NOMINA'];
        $tipo_nomina = strpos($NOMINA_a, "SEMANA");

        if ($tipo_nomina !== false) {
            $tipo_nomina_str = "SEMANAL";
        } else {
            $tipo_nomina_str = "QUINCENAL";
        }

        ?>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <input type="hidden" value="<?php echo($NOMINA_a); ?>">
            </div>
            <div class="col-md-8 col-sm-12">
                <figure>
                    <blockquote class="blockquote">
                        <p><?php echo $last_name_1."&nbsp;".$first_name_1; ?></p>
                    </blockquote>
                    <figcaption class="blockquote-footer">
                        <?php echo "Departamento: ".$dept_name_1.", Puesto: ".$position_name_1.", Fecha de contrataci&oacute;n: ".$hire_date_1.", Nomina: ".$tipo_nomina_str; ?>
                    </figcaption>
                </figure>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <br>
                <button type="button" class="btn btn-primary position-relative btn-sm" onclick="e_s_rh(<?php echo $inp_num_empl.',`'.$tipo_nomina_str.'`'; ?>)">
                    Registros entrada/salida
                </button>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <!--<button type="button" class="btn btn-secondary position-relative btn-sm" data-bs-toggle="modal" data-bs-target="#Modal_vacaciones" onclick="vacaciones_rh(<?php echo $inp_num_empl.',`'.$tipo_nomina_str.'`'; ?>)">-->
                <button type="button" class="btn btn-success position-relative btn-sm" data-bs-toggle="modal" data-bs-target="#Modal_vacaciones" onclick="accion_vacaciones(2, <?php echo $inp_num_empl; ?>, 'rh_vacaciones')">
                    Vacaciones
                </button>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <button type="button" class="btn btn-info position-relative btn-sm" onclick="solicitudes_rh(<?php echo $inp_num_empl.',`'.$tipo_nomina_str.'`'; ?>)">
                    Historial de solicitudes
                </button>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <button type="button" class="btn btn-info position-relative btn-sm" disabled>
                    Documentos digitales
                </button>
            </div>
        </div>
        <?php
    }elseif (($inp_num_empl == '') && ($slc_deptos != 0)) {//***BUSQUEDA POR DEPARTAMENTO
        ?>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-check">
                    <label class="form-check-label" for="slc_year">A&ntilde;o:</label>
                    <select class="form-select" id="slc_year" onchange="fun_year_semana(this.value)">
                        <option value="<?php echo($year_current); ?>" selected><?php echo($year_current); ?></option>
                        <?php
                            for ($i = $year_current-1; $i > 2019 ; $i--) { 
                                echo '<option value='.$i.'>'.$i.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-check">
                    <div id="response_year_semana">
                        <label class="form-check-label" for="slc_semana">Semanas:</label>
                        <select class="form-select" id="slc_semana" onchange="busca_sem_dpto(this.value, document.getElementById('slc_year').value, <?php echo($slc_deptos); ?>)">
                            <option selected disabled>Seleccionar una semana</option>
                            <?php
                                for ($i=1; $i < 54; $i++) { 
                                    $semanas = getFirstDayWeek($i, $year_current);
                                    echo '<option value='.$i.'>Semana: '.$i.': Del '.$semanas[start].' al '.$semanas[end].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
?>
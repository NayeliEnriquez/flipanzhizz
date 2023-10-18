<table class="table table-dark table-hover align-middle" id="tb_empleados">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Departamento</th>
            <th scope="col"># Empleado</th>
            <th scope="col">Nombre completo</th>
            <th scope="col">Posici&oacute;n</th>
            <th scope="col">Fecha de contrataci&oacute;n</th>
            <th scope="col">Tipo de nomina</th>
            <th scope="col">Jefe inmediato</th>
            <th scope="col">Editar jefe</th>
        </tr>
    </thead>
    <tbody>
        <?php
            include ('../../php/conn.php');
            $contador = 0;
            $query_a = "SELECT
                    pe.first_name, pe.last_name, pe.emp_code,
                    pe.department_id, pe.position_id, pe.hire_date,
                    pd.dept_name, ps.position_name, pd.emp_code_charge, pd.parent_dept_id
                FROM
                    dbo.personnel_employee pe
                INNER JOIN
                    dbo.personnel_department pd
                ON
                    pe.department_id = pd.id
                INNER JOIN
                    dbo.personnel_position ps
                ON
                    pe.position_id = ps.id
                WHERE
                    pe.enable_att = '1' ORDER BY pe.emp_code ASC";
            $exe_a = sqlsrv_query($cnx, $query_a);
            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                $first_name_a = utf8_encode($fila_a['first_name']);
                $last_name_a = utf8_encode($fila_a['last_name']);
                $emp_code_a = $fila_a['emp_code'];
                $hire_date_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date']))));
                $hire_date_a = substr($hire_date_a, 5, 10);
                $dept_name_a = $fila_a['dept_name'];
                $position_name_a = $fila_a['position_name'];
                /*$pago_a = $fila_a['pago'];
                $pago_a = (empty($pago_a)) ? '<button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_asg_nomina" onclick="tipos_nominas(`'.$emp_code_a.'`, 1)">Asignar nomina</button>' : $pago_a ;
                $puesto_novag_a = $fila_a['puesto_novag'];*/

                $query_gen = "SELECT NOMINA AS pago, PUESTO AS puesto_novag FROM dbo.rh_employee_gen WHERE CLAVE = '$emp_code_a'";
                $exe_gen = sqlsrv_query($cnx, $query_gen);
                $fila_gen = sqlsrv_fetch_array($exe_gen, SQLSRV_FETCH_ASSOC);
                if ($fila_gen == null) {
                    $pago_a = 'No info';
                    $puesto_novag_a = 'No info';
                    $full_name = $last_name_a." ".$first_name_a;
                    $sql_inserta = "INSERT INTO [dbo].[rh_employee_gen] ([CLAVE], [NOMBRE COMPLETO], [PUESTO], [DEPARTAMENTO]) VALUES ('$emp_code_a', '$full_name', '$position_name_a', '$dept_name_a')";
                    sqlsrv_query($cnx, $sql_inserta);
                } else {
                    $pago_a = $fila_gen['pago'];
                    if ((empty($pago_a)) || ($pago_a == null)) {
                        $pago_a = 'No info';
                    }
                    $puesto_novag_a = $fila_gen['puesto_novag'];
                    if ((empty($puesto_novag_a)) || ($puesto_novag_a == null)) {
                        $puesto_novag_a = 'No info';
                    }
                }

                $emp_code_charge_a = $fila_a['emp_code_charge'];
                $parent_dept_id_a = $fila_a['parent_dept_id'];

                $sql_A = "SELECT * FROM rh_jefe_directo WHERE num_emp = '$emp_code_a'";
                $exe_A = sqlsrv_query($cnx, $sql_A);
                $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
                $num_emp = $fila_A['num_emp'];
                $num_emp_boss = $fila_A['num_emp_boss'];

                if ($num_emp == null) {
                    if (empty($emp_code_charge_a)) {
                        if (!empty($parent_dept_id_a)) {
                            $query_c = "
                                SELECT 
                                    pd.emp_code_charge, pe.first_name, pe.last_name
                                FROM 
                                    personnel_department pd
                                INNER JOIN
                                    dbo.personnel_employee pe
                                ON
                                    pd.emp_code_charge = pe.emp_code
                                WHERE 
                                    pd.id = '$parent_dept_id_a'";
                            $exe_c = sqlsrv_query($cnx, $query_c);
                            $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
                            $first_name_jefe = utf8_encode($fila_c['first_name']);
                            $last_name_jefe = utf8_encode($fila_c['last_name']);
                            $emp_code_charge_jefe = $fila_c['emp_code_charge'];
                            if (!empty($last_name_jefe)) {
                                $desc_jefe = $emp_code_charge_jefe." - ".$last_name_jefe." ".$first_name_jefe;
                            } else {
                                $desc_jefe = "No info";
                            }
                        }else{
                            $desc_jefe = "No info";
                        }
                    } else {
                        $query_b = "SELECT first_name, last_name FROM personnel_employee WHERE emp_code LIKE '$emp_code_charge_a'";
                        $exe_b = sqlsrv_query($cnx, $query_b);
                        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                        $first_name_jefe = utf8_encode($fila_b['first_name']);
                        $last_name_jefe = utf8_encode($fila_b['last_name']);
                        if (!empty($last_name_jefe)) {
                            $desc_jefe = $emp_code_charge_a." - ".$last_name_jefe." ".$first_name_jefe;
                        } else {
                            $desc_jefe = "No info";
                        }
                    }
                }else{
                    $query_B = "SELECT
                            pe.first_name, pe.last_name, pe.emp_code
                        FROM
                            dbo.personnel_employee pe
                        WHERE
                            pe.emp_code = '$num_emp_boss'";
                    $exe_B = sqlsrv_query($cnx, $query_B);
                    $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
                    $first_name_boss = utf8_encode($fila_B['first_name']);
                    $last_name_boss = utf8_encode($fila_B['last_name']);

                    if ($first_name_boss == null) {
                        $query_C = "SELECT * FROM rh_directivos WHERE CLAVE = '$num_emp_boss'";
                        $exe_C = sqlsrv_query($cnx, $query_C);
                        $fila_C = sqlsrv_fetch_array($exe_C, SQLSRV_FETCH_ASSOC);
                        $first_name_boss = utf8_encode($fila_C['NOMBRE COMPLETO']);

                        $desc_jefe = $num_emp_boss." - ".$first_name_boss;
                    }else{
                        $desc_jefe = $num_emp_boss." - ".$last_name_boss." ".$first_name_boss;
                    }
                }
                
                $contador++;
                echo '
                    <tr>
                        <th scope="row">'.$contador.'</th>
                        <td><center>'.$dept_name_a.'</center></td>
                        <td><center>'.$emp_code_a.'</center></td>
                        <td><center>'.$last_name_a.' '.$first_name_a.'</center></td>
                        <td><center>'.$position_name_a.'</center></td>
                        <td><center>'.$hire_date_a.'</center></td>
                        <td><center>'.$pago_a.'</center></td>
                        <td><center>'.$desc_jefe.'</center></td>
                        <td>
                            <center>
                                <button type="button" class="btn btn-sm btn-outline-info" title="Cambiar de jefe directo" data-bs-toggle="modal" data-bs-target="#mdl_change_boss" onclick="ver_boss('.$emp_code_a.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" title="Restablecer PIN" onclick="restablece_pin('.$emp_code_a.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                </button>
                            </center>
                        </td>
                    </tr>
                ';
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Departamento</th>
            <th scope="col"># Empleado</th>
            <th scope="col">Nombre completo</th>
            <th scope="col">Posici&oacute;n</th>
            <th scope="col">Fecha de contrataci&oacute;n</th>
            <th scope="col">Tipo de nomina</th>
            <th scope="col">Jefe inmediato</th>
            <th scope="col">Editar jefe</th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_empleados').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
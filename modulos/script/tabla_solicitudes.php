<table class="table table-dark table-hover align-middle" id="tb_solicitudes">
    <thead>
        <tr>
            <th scope="col"><center>Folio</center></th>
            <th scope="col">Empleado</th>
            <th scope="col">Solicitud</th>
            <th scope="col">Fecha de solicitud</th>
            <th scope="col">Detalles</th>
        </tr>
    </thead>
    <tbody>
        <?php
            include ('../../php/conn.php');
            session_start();
            /*while($post = each($_SESSION)){
                echo $post[0]." = ".$post[1]."<br>";
            }*/
            $num_empleado_session = $_SESSION['num_empleado_a'];

            $cadena_dg = "7050, 2382, 7046, 2729, 7048, 7051, 7035, 7042, 7049, 2983, 2822";//***Direccion general***
            $val_dg = strpos($cadena_dg, $num_empleado_session);

            $contador = 0;

            if ($val_dg !== false) {
                $v_dg = 1;
                $query_c = "SELECT 
                                rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                rs.id_empleado, pe.first_name, pe.last_name
                            FROM 
                                rh_solicitudes rs
                            INNER JOIN
                                personnel_employee pe
                            ON 
                                pe.emp_code = rs.id_empleado
                            WHERE 
                                rs.estatus = '3' 
                            ORDER BY rs.f_solicitud ASC";
                $exe_c = sqlsrv_query($cnx, $query_c);
                while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                    $contador++;
                    $id_empleado_c = $fila_c['id_empleado'];
                    $first_name_c = utf8_encode($fila_c['first_name']);
                    $last_name_c = utf8_encode($fila_c['last_name']);
                    $id_c = $fila_c['id'];
                    $tipo_ausencia_c = $fila_c['tipo_ausencia'];
                    $f_solicitud_c = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_c['f_solicitud']))));
                    $f_solicitud_c = substr($f_solicitud_c, 5, 20);
                    echo "
                        <tr>
                            <th scope='row'><center>".$id_c."</center></th>
                            <td>".$id_empleado_c." - ".$last_name_c." ".$first_name_c."</td>
                            <td>".$tipo_ausencia_c."</td>
                            <td>".$f_solicitud_c."</td>
                            <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_c.", `rh_solicitudes`)'>Detalles</button></td>
                        </tr>
                    ";
                }

                $query_d = "SELECT 
                                rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                rs.id_empleado, pe.first_name, pe.last_name
                            FROM 
                                rh_salida rs
                            INNER JOIN
                                personnel_employee pe
                            ON 
                                pe.emp_code = rs.id_empleado
                            WHERE 
                                rs.estatus = '3' 
                            ORDER BY rs.f_solicitud ASC";
                $exe_d = sqlsrv_query($cnx, $query_d);
                while ($fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC)) {
                    $contador++;
                    $id_empleado_d = $fila_d['id_empleado'];
                    $first_name_d = utf8_encode($fila_d['first_name']);
                    $last_name_d = utf8_encode($fila_d['last_name']);
                    $id_d = $fila_d['id'];
                    $tipo_ausencia_d = $fila_d['tipo_ausencia'];
                    $f_solicitud_d = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_d['f_solicitud']))));
                    $f_solicitud_d = substr($f_solicitud_d, 5, 20);
                    echo "
                        <tr>
                            <th scope='row'><center>".$id_d."</center></th>
                            <td>".$id_empleado_d." - ".$last_name_d." ".$first_name_d."</td>
                            <td>".$tipo_ausencia_d."</td>
                            <td>".$f_solicitud_d."</td>
                            <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_d.", `rh_salida`)'>Detalles</button></td>
                        </tr>
                    ";
                }

                $query_e = "SELECT 
                                rv.id, rv.tipo_ausencia, rv.f_solicitud,
                                rv.id_empleado, pe.first_name, pe.last_name
                            FROM 
                                rh_vacaciones rv
                            INNER JOIN
                                personnel_employee pe
                            ON 
                                pe.emp_code = rv.id_empleado
                            WHERE 
                                rv.estatus = '3' 
                            ORDER BY rv.f_solicitud ASC";
                $exe_e = sqlsrv_query($cnx, $query_e);
                while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                    $contador++;
                    $id_empleado_e = $fila_e['id_empleado'];
                    $first_name_e = utf8_encode($fila_e['first_name']);
                    $last_name_e = utf8_encode($fila_e['last_name']);
                    $id_e = $fila_e['id'];
                    $tipo_ausencia_e = $fila_e['tipo_ausencia'];
                    $f_solicitud_e = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_e['f_solicitud']))));
                    $f_solicitud_e = substr($f_solicitud_e, 5, 20);
                    echo "
                        <tr>
                            <th scope='row'><center>".$id_e."</center></th>
                            <td>".$id_empleado_e." - ".$last_name_e." ".$first_name_e."</td>
                            <td>".$tipo_ausencia_e."</td>
                            <td>".$f_solicitud_e."</td>
                            <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_e.", `rh_vacaciones`)'>Detalles</button></td>
                        </tr>
                    ";
                }
            }else{
                $v_dg = 0;

                $sql_super = "SELECT COUNT(id) AS super_conf FROM rh_supervisores WHERE num_emp = '$num_empleado_session'";
                $exe_super = sqlsrv_query($cnx, $sql_super);
                $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
                $super_conf = $fila_super['super_conf'];
                if ($super_conf > 0) {
                    $in_emps = '';
                    $sql_super_emp = "SELECT [id], [id_super], [num_emp_asign] FROM [rh_novag_system].[dbo].[rh_supers_emps] WHERE id_super = '$num_empleado_session'";
                    $exe_super_emp = sqlsrv_query($cnx, $sql_super_emp);
                    while ($fila_super_emp = sqlsrv_fetch_array($exe_super_emp, SQLSRV_FETCH_ASSOC)) {
                        $in_emps .= "'".$fila_super_emp['num_emp_asign']."', ";
                    }

                    $in_emps = substr($in_emps, 0, -2);

                    $query_c = "SELECT 
                                    rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                    rs.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_solicitudes rs
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rs.id_empleado
                                WHERE 
                                    rs.id_empleado IN (".$in_emps.") AND rs.estatus = '0' 
                                ORDER BY rs.f_solicitud ASC";
                    $exe_c = sqlsrv_query($cnx, $query_c);
                    while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_c = $fila_c['id_empleado'];
                        $first_name_c = utf8_encode($fila_c['first_name']);
                        $last_name_c = utf8_encode($fila_c['last_name']);
                        $id_c = $fila_c['id'];
                        $tipo_ausencia_c = $fila_c['tipo_ausencia'];
                        $f_solicitud_c = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_c['f_solicitud']))));
                        $f_solicitud_c = substr($f_solicitud_c, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_c."</center></th>
                                <td>".$id_empleado_c." - ".$last_name_c." ".$first_name_c."</td>
                                <td>".$tipo_ausencia_c."</td>
                                <td>".$f_solicitud_c."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_c.", `rh_solicitudes`)'>Detalles</button></td>
                            </tr>
                        ";
                    }

                    $query_d = "SELECT 
                                    rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                    rs.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_salida rs
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rs.id_empleado
                                WHERE 
                                    rs.id_empleado IN (".$in_emps.") AND rs.estatus = '0' 
                                ORDER BY rs.f_solicitud ASC";
                    $exe_d = sqlsrv_query($cnx, $query_d);
                    while ($fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_d = $fila_d['id_empleado'];
                        $first_name_d = utf8_encode($fila_d['first_name']);
                        $last_name_d = utf8_encode($fila_d['last_name']);
                        $id_d = $fila_d['id'];
                        $tipo_ausencia_d = $fila_d['tipo_ausencia'];
                        $f_solicitud_d = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_d['f_solicitud']))));
                        $f_solicitud_d = substr($f_solicitud_d, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_d."</center></th>
                                <td>".$id_empleado_d." - ".$last_name_d." ".$first_name_d."</td>
                                <td>".$tipo_ausencia_d."</td>
                                <td>".$f_solicitud_d."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_d.", `rh_salida`)'>Detalles</button></td>
                            </tr>
                        ";
                    }

                    $query_e = "SELECT 
                                    rv.id, rv.tipo_ausencia, rv.f_solicitud,
                                    rv.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_vacaciones rv
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rv.id_empleado
                                WHERE 
                                    rv.id_empleado IN (".$in_emps.") AND rv.estatus = '0' 
                                ORDER BY rv.f_solicitud ASC";
                    $exe_e = sqlsrv_query($cnx, $query_e);
                    while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_e = $fila_e['id_empleado'];
                        $first_name_e = utf8_encode($fila_e['first_name']);
                        $last_name_e = utf8_encode($fila_e['last_name']);
                        $id_e = $fila_e['id'];
                        $tipo_ausencia_e = $fila_e['tipo_ausencia'];
                        $f_solicitud_e = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_e['f_solicitud']))));
                        $f_solicitud_e = substr($f_solicitud_e, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_e."</center></th>
                                <td>".$id_empleado_e." - ".$last_name_e." ".$first_name_e."</td>
                                <td>".$tipo_ausencia_e."</td>
                                <td>".$f_solicitud_e."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_e.", `rh_vacaciones`)'>Detalles</button></td>
                            </tr>
                        ";
                    }

                }else{
                    $query_a = "SELECT * FROM personnel_department WHERE emp_code_charge = '$num_empleado_session' OR sub_emp_code_charge = '$num_empleado_session'";
                    $exe_a = sqlsrv_query($cnx, $query_a);
                    $in_a = '';
                    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                        $id_bd_a = $fila_a['id'];
                        if (!empty($id_bd_a)) {
                            $in_a .= "'".$id_bd_a."', ";
                        }
                    }
                    $in_a = substr($in_a, 0,-2);

                    /*$in_b = '';
                    //$query_b = "SELECT * FROM personnel_department WHERE parent_dept_id IN (".$in_a.")";
                    $query_b = "SELECT * FROM personnel_department WHERE dept_code IN (".$in_a.")";
                    $exe_b = sqlsrv_query($cnx, $query_b);
                    $cont_z = 0;
                    while ($fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
                        $id_bd_b = $fila_b['id'];
                        if (!empty($id_bd_b)) {
                            $in_b .= "'".$id_bd_b."', ";
                        }
                    }
                    
                    $in_b = substr($in_b, 0,-2);*/

                    $query_c = "SELECT 
                                    rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                    rs.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_solicitudes rs
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rs.id_empleado
                                WHERE 
                                    rs.id_depto IN (".$in_a.") AND rs.estatus = '0' 
                                ORDER BY rs.f_solicitud ASC";
                    $exe_c = sqlsrv_query($cnx, $query_c);
                    while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_c = $fila_c['id_empleado'];
                        $first_name_c = utf8_encode($fila_c['first_name']);
                        $last_name_c = utf8_encode($fila_c['last_name']);
                        $id_c = $fila_c['id'];
                        $tipo_ausencia_c = $fila_c['tipo_ausencia'];
                        $f_solicitud_c = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_c['f_solicitud']))));
                        $f_solicitud_c = substr($f_solicitud_c, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_c."</center></th>
                                <td>".$id_empleado_c." - ".$last_name_c." ".$first_name_c."</td>
                                <td>".$tipo_ausencia_c."</td>
                                <td>".$f_solicitud_c."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_c.", `rh_solicitudes`)'>Detalles</button></td>
                            </tr>
                        ";
                    }

                    $query_d = "SELECT 
                                    rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                    rs.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_salida rs
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rs.id_empleado
                                WHERE 
                                    rs.id_depto IN (".$in_a.") AND rs.estatus = '0' 
                                ORDER BY rs.f_solicitud ASC";
                    $exe_d = sqlsrv_query($cnx, $query_d);
                    while ($fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_d = $fila_d['id_empleado'];
                        $first_name_d = utf8_encode($fila_d['first_name']);
                        $last_name_d = utf8_encode($fila_d['last_name']);
                        $id_d = $fila_d['id'];
                        $tipo_ausencia_d = $fila_d['tipo_ausencia'];
                        $f_solicitud_d = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_d['f_solicitud']))));
                        $f_solicitud_d = substr($f_solicitud_d, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_d."</center></th>
                                <td>".$id_empleado_d." - ".$last_name_d." ".$first_name_d."</td>
                                <td>".$tipo_ausencia_d."</td>
                                <td>".$f_solicitud_d."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_d.", `rh_salida`)'>Detalles</button></td>
                            </tr>
                        ";
                    }

                    $query_e = "SELECT 
                                    rv.id, rv.tipo_ausencia, rv.f_solicitud,
                                    rv.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_vacaciones rv
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rv.id_empleado
                                WHERE 
                                    rv.id_depto IN (".$in_a.") AND rv.estatus = '0' 
                                ORDER BY rv.f_solicitud ASC";
                    $exe_e = sqlsrv_query($cnx, $query_e);
                    while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                        $contador++;
                        $id_empleado_e = $fila_e['id_empleado'];
                        $first_name_e = utf8_encode($fila_e['first_name']);
                        $last_name_e = utf8_encode($fila_e['last_name']);
                        $id_e = $fila_e['id'];
                        $tipo_ausencia_e = $fila_e['tipo_ausencia'];
                        $f_solicitud_e = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_e['f_solicitud']))));
                        $f_solicitud_e = substr($f_solicitud_e, 5, 20);
                        echo "
                            <tr>
                                <th scope='row'><center>".$id_e."</center></th>
                                <td>".$id_empleado_e." - ".$last_name_e." ".$first_name_e."</td>
                                <td>".$tipo_ausencia_e."</td>
                                <td>".$f_solicitud_e."</td>
                                <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_e.", `rh_vacaciones`)'>Detalles</button></td>
                            </tr>
                        ";
                    }
                }

                if ($contador == 0) {
                  $in_a = '';
                  $query_search2 = "SELECT * FROM rh_jefe_directo WHERE num_emp_boss = '$num_empleado_session'";
                  $exe_search2 = sqlsrv_query($cnx, $query_search2);
                  while ($fila_search2 = sqlsrv_fetch_array($exe_search2, SQLSRV_FETCH_ASSOC)) {
                     $in_a .= "'".$fila_search2['num_emp']."', ";
                  }

                  $in_a = substr($in_a, 0, -2);

                  $query_c = "SELECT 
                                    rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                    rs.id_empleado, pe.first_name, pe.last_name
                                FROM 
                                    rh_solicitudes rs
                                INNER JOIN
                                    personnel_employee pe
                                ON 
                                    pe.emp_code = rs.id_empleado
                                WHERE 
                                    rs.id_empleado IN (".$in_a.") AND rs.estatus = '0' 
                                ORDER BY rs.f_solicitud ASC";
                  $exe_c = sqlsrv_query($cnx, $query_c);
                  while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                     $contador++;
                     $id_empleado_c = $fila_c['id_empleado'];
                     $first_name_c = utf8_encode($fila_c['first_name']);
                     $last_name_c = utf8_encode($fila_c['last_name']);
                     $id_c = $fila_c['id'];
                     $tipo_ausencia_c = $fila_c['tipo_ausencia'];
                     $f_solicitud_c = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_c['f_solicitud']))));
                     $f_solicitud_c = substr($f_solicitud_c, 5, 20);
                     echo "
                           <tr>
                              <th scope='row'><center>".$id_c."</center></th>
                              <td>".$id_empleado_c." - ".$last_name_c." ".$first_name_c."</td>
                              <td>".$tipo_ausencia_c."</td>
                              <td>".$f_solicitud_c."</td>
                              <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_c.", `rh_solicitudes`)'>Detalles</button></td>
                           </tr>
                     ";
                  }

                  $query_d = "SELECT 
                                 rs.id, rs.tipo_ausencia, rs.f_solicitud,
                                 rs.id_empleado, pe.first_name, pe.last_name
                              FROM 
                                 rh_salida rs
                              INNER JOIN
                                 personnel_employee pe
                              ON 
                                 pe.emp_code = rs.id_empleado
                              WHERE 
                                 rs.id_empleado IN (".$in_a.") AND rs.estatus = '0' 
                              ORDER BY rs.f_solicitud ASC";
                  $exe_d = sqlsrv_query($cnx, $query_d);
                  while ($fila_d = sqlsrv_fetch_array($exe_d, SQLSRV_FETCH_ASSOC)) {
                     $contador++;
                     $id_empleado_d = $fila_d['id_empleado'];
                     $first_name_d = utf8_encode($fila_d['first_name']);
                     $last_name_d = utf8_encode($fila_d['last_name']);
                     $id_d = $fila_d['id'];
                     $tipo_ausencia_d = $fila_d['tipo_ausencia'];
                     $f_solicitud_d = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_d['f_solicitud']))));
                     $f_solicitud_d = substr($f_solicitud_d, 5, 20);
                     echo "
                           <tr>
                              <th scope='row'><center>".$id_d."</center></th>
                              <td>".$id_empleado_d." - ".$last_name_d." ".$first_name_d."</td>
                              <td>".$tipo_ausencia_d."</td>
                              <td>".$f_solicitud_d."</td>
                              <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_d.", `rh_salida`)'>Detalles</button></td>
                           </tr>
                     ";
                  }

                  $query_e = "SELECT 
                                 rv.id, rv.tipo_ausencia, rv.f_solicitud,
                                 rv.id_empleado, pe.first_name, pe.last_name
                              FROM 
                                 rh_vacaciones rv
                              INNER JOIN
                                 personnel_employee pe
                              ON 
                                 pe.emp_code = rv.id_empleado
                              WHERE 
                                 rv.id_empleado IN (".$in_a.") AND rv.estatus = '0' 
                              ORDER BY rv.f_solicitud ASC";
                  $exe_e = sqlsrv_query($cnx, $query_e);
                  while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                     $contador++;
                     $id_empleado_e = $fila_e['id_empleado'];
                     $first_name_e = utf8_encode($fila_e['first_name']);
                     $last_name_e = utf8_encode($fila_e['last_name']);
                     $id_e = $fila_e['id'];
                     $tipo_ausencia_e = $fila_e['tipo_ausencia'];
                     $f_solicitud_e = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_e['f_solicitud']))));
                     $f_solicitud_e = substr($f_solicitud_e, 5, 20);
                     echo "
                           <tr>
                              <th scope='row'><center>".$id_e."</center></th>
                              <td>".$id_empleado_e." - ".$last_name_e." ".$first_name_e."</td>
                              <td>".$tipo_ausencia_e."</td>
                              <td>".$f_solicitud_e."</td>
                              <td><button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#Mdl_ver_solicitud' onclick='ver_solicitud(".$id_e.", `rh_vacaciones`)'>Detalles</button></td>
                           </tr>
                     ";
                  }
               }
            }
            
            
        ?>
    </tbody>
</table>

<div class="modal fade" id="Mdl_ver_solicitud" tabindex="-1" aria-labelledby="Mdl_ver_solicitudLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Mdl_ver_solicitudLabel">Ver solicitud</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response_ver_solicitud"></div>
            </div>
            <div class="modal-footer">
                <div id="response_nuser"></div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm" id="btn_act2" onclick="actions_solicitud(2, <?php echo($v_dg); ?>)">Rechazar</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_act1" onclick="actions_solicitud(1, <?php echo($v_dg); ?>)">Aprobar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_solicitudes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            "order": [3, 'desc']
        });
    });
</script>
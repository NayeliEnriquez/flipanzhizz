<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../php/conn.php');
    session_start();
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."<br>";
	}*/
    $sesion_num_emp = $_SESSION['num_empleado_a'];
    $f_y_h_today = date('Y-m-d');
    $f_y_h = date("Y-m-d",strtotime($f_y_h_today."-16 days")); 
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Index</title>
        <!--<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">-->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
        </style>
    
        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <script type="text/javascript" src="../js/rh.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <body>
        <?php
            include('navs/header_sys.php');
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                    $name_self = basename($_SERVER['PHP_SELF']);
                    include('navs/nav_menu.php');
                ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Personal</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-3">
                            <?php
                                $sql_super = "SELECT COUNT(id) AS super_conf FROM rh_supervisores WHERE num_emp = '$sesion_num_emp'";
                                $exe_super = sqlsrv_query($cnx, $sql_super);
                                $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
                                $super_conf = $fila_super['super_conf'];
                                if ($super_conf > 0) {
                            ?>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_add_emp">
                                    Agregar personal a mi lista
                                </button>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_he_emp">
                                Registros de TE
                            </button>
                        </div>
                    </div><br>
                    <table class="table table-dark table-hover align-middle" id="tb_empleados">
                        <thead>
                            <tr>
                                <th scope="col"># Empleado</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Nombre completo</th>
                                <th scope="col">Posici&oacute;n</th>
                                <th scope="col">Fecha de contrataci&oacute;n</th>
                                <th scope="col">Tipo de nomina</th>
                                <th scope="col">Horarios</th>
                                <th scope="col">Correos</th>
                                <th scope="col">Restablecer PIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include ('../../php/conn.php');
                                $contador = 0;
                                if ((empty($sesion_num_emp)) || ($sesion_num_emp == '0')) {
                                    echo '
                                        <tr>
                                            <td><center></center></td>
                                            <td><center></center></td>
                                            <td><center>SIN</center></td>
                                            <td><center></center></td>
                                            <td><center>INFORMACI&Oacute;N</center></td>
                                            <td><center></center></td>
                                            <td><center></center></td>
                                        </tr>
                                    ';
                                } else {
                                    if ($super_conf > 0) {
                                        $sql_los_del = "SELECT * FROM rh_supers_emps WHERE id_super = '$sesion_num_emp'";
                                        $exe_los_del = sqlsrv_query($cnx, $sql_los_del);
                                        while ($fila_los_del = sqlsrv_fetch_array($exe_los_del, SQLSRV_FETCH_ASSOC)) {
                                            $num_emp_asign = $fila_los_del['num_emp_asign'];
                                            /*$query_a = "SELECT
                                                    pe.first_name, pe.last_name, pe.emp_code,
                                                    pe.department_id, pe.position_id, pe.hire_date,
                                                    pd.dept_name, ps.position_name, eg.NOMINA AS pago,
                                                    eg.PUESTO AS puesto_novag, pd.emp_code_charge, pd.parent_dept_id
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
                                                INNER JOIN
                                                    dbo.rh_employee_gen eg
                                                ON
                                                    pe.emp_code = eg.CLAVE
                                                WHERE
                                                    pe.enable_att = '1' AND pe.emp_code = '$num_emp_asign'";*/

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
                                                    pe.enable_att = '1' AND pe.emp_code = '$num_emp_asign'";

                                            $exe_a = sqlsrv_query($cnx, $query_a);
                                            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                                $first_name_a = utf8_encode($fila_a['first_name']);
                                                $last_name_a = utf8_encode($fila_a['last_name']);
                                                $emp_code_a = $fila_a['emp_code'];
                                                $hire_date_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date']))));
                                                $hire_date_a = substr($hire_date_a, 5, 10);
                                                $dept_name_a = $fila_a['dept_name'];
                                                $position_name_a = $fila_a['position_name'];

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
                                                $contador++;
                                                $query_b = "SELECT [id], [emp_code], [email] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_a'";
                                                $exe_b = sqlsrv_query($conn, $query_b);
                                                $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                                $id_zk = $fila_b['id'];
                                                $email_zk = $fila_b['email'];
                                                $email_zk = (empty($email_zk)) ? "Sin correo" : $email_zk ;
                                                echo '
                                                    <tr>
                                                        <th scope="row"><center>'.$emp_code_a.'</center></td>
                                                        <td><center>'.$dept_name_a.'</center></td>
                                                        <td><center>'.$last_name_a.' '.$first_name_a.'</center></td>
                                                        <td><center>'.$position_name_a.'</center></td>
                                                        <td><center>'.$hire_date_a.'</center></td>
                                                        <td><center>'.$pago_a.'</center></td>
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="employee_actions(`'.$emp_code_a.'`, 1)">Horario</button>
                                                                <button type="button" class="btn btn-warning btn-sm" title="Liberar del equpo de trabajo" onclick="liberar_sup_emp(`'.$emp_code_a.'`, 1)">Liberar</button>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <!--<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="correo_empleado(`'.$emp_code_a.'`, 1)">Email</button>-->
                                                                '.$email_zk.'<br><a href="#" onclick="correo_empleado(`'.$id_zk.'`, `'.$email_zk.'`)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn btn-warning btn-sm" onclick="restablece_pin('.$emp_code_a.')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                                                </button>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        }
                                    }else{
                                        /*$query_a = "SELECT
                                                pe.first_name, pe.last_name, pe.emp_code,
                                                pe.department_id, pe.position_id, pe.hire_date,
                                                pd.dept_name, ps.position_name, eg.NOMINA AS pago,
                                                eg.PUESTO AS puesto_novag, pd.emp_code_charge, pd.parent_dept_id
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
                                            INNER JOIN
                                                dbo.rh_employee_gen eg
                                            ON
                                                pe.emp_code = eg.CLAVE
                                            WHERE
                                                pe.enable_att = '1' AND pd.emp_code_charge = '$sesion_num_emp' ORDER BY pe.last_name ASC";*/
                                        
                                        $query_a = "SELECT
                                                pe.first_name, pe.last_name, pe.emp_code,
                                                pe.department_id, pe.position_id, pe.hire_date,
                                                pd.dept_name, ps.position_name, 
                                                pd.emp_code_charge, pd.parent_dept_id
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
                                                pe.enable_att = '1' AND pd.emp_code_charge = '$sesion_num_emp' OR pd.sub_emp_code_charge = '$sesion_num_emp' AND pe.enable_att = '1' ORDER BY pe.last_name ASC";
                                        $exe_a = sqlsrv_query($cnx, $query_a);
                                        while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                            $first_name_a = utf8_encode($fila_a['first_name']);
                                            $last_name_a = utf8_encode($fila_a['last_name']);
                                            $emp_code_a = $fila_a['emp_code'];
                                            $hire_date_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date']))));
                                            $hire_date_a = substr($hire_date_a, 5, 10);
                                            $dept_name_a = $fila_a['dept_name'];
                                            $position_name_a = $fila_a['position_name'];

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
                                            $contador++;
                                            $query_b = "SELECT [id], [emp_code], [email] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_a'";
                                            $exe_b = sqlsrv_query($conn, $query_b);
                                            $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                            $id_zk = $fila_b['id'];
                                            $email_zk = $fila_b['email'];
                                            $email_zk = (empty($email_zk)) ? "Sin correo" : $email_zk ;
                                            echo '
                                                <tr>
                                                    <th scope="row"><center>'.$emp_code_a.'</center></td>
                                                    <td><center>'.$dept_name_a.'</center></td>
                                                    <td><center>'.$last_name_a.' '.$first_name_a.'</center></td>
                                                    <td><center>'.$position_name_a.'</center></td>
                                                    <td><center>'.$hire_date_a.'</center></td>
                                                    <td><center>'.$pago_a.'</center></td>
                                                    <td>
                                                        <center>
                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="employee_actions(`'.$emp_code_a.'`, 1)">Horario</button>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <!--<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="correo_empleado(`'.$emp_code_a.'`, 1)">Email</button>-->
                                                            '.$email_zk.'<br><a href="#" onclick="correo_empleado(`'.$id_zk.'`, `'.$email_zk.'`)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <button type="button" class="btn btn-warning btn-sm" onclick="restablece_pin('.$emp_code_a.')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                                            </button>
                                                        </center>
                                                    </td>
                                                </tr>
                                            ';
                                        }
                                    }

                                    if ($contador == 0) {
                                       $query_search2 = "SELECT * FROM rh_jefe_directo WHERE num_emp_boss = '$sesion_num_emp'";
                                       $exe_search2 = sqlsrv_query($cnx, $query_search2);
                                       while ($fila_search2 = sqlsrv_fetch_array($exe_search2, SQLSRV_FETCH_ASSOC)) {
                                          $num_emp_a = $fila_search2['num_emp'];
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
                                                            pe.enable_att = '1' AND pe.emp_code = '$num_emp_a'";
                                          $exe_a = sqlsrv_query($cnx, $query_a);
                                          $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);

                                          $first_name_a = utf8_encode($fila_a['first_name']);
                                          $last_name_a = utf8_encode($fila_a['last_name']);
                                          $emp_code_a = $fila_a['emp_code'];
                                          $hire_date_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10);
                                          $dept_name_a = $fila_a['dept_name'];
                                          $position_name_a = $fila_a['position_name'];

                                          $query_gen = "SELECT NOMINA AS pago, PUESTO AS puesto_novag FROM dbo.rh_employee_gen WHERE CLAVE = '$emp_code_a'";
                                          $exe_gen = sqlsrv_query($cnx, $query_gen);
                                          $fila_gen = sqlsrv_fetch_array($exe_gen, SQLSRV_FETCH_ASSOC);
                                          $pago_a = $fila_gen['pago'];
                                          if ((empty($pago_a)) || ($pago_a == null)) {
                                             $pago_a = 'No info';
                                          }
                                          $puesto_novag_a = $fila_gen['puesto_novag'];
                                          if ((empty($puesto_novag_a)) || ($puesto_novag_a == null)) {
                                             $puesto_novag_a = 'No info';
                                          }

                                          $emp_code_charge_a = $fila_a['emp_code_charge'];
                                          $parent_dept_id_a = $fila_a['parent_dept_id'];
                                          $contador++;
                                          $query_b = "SELECT [id], [emp_code], [email] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_a'";
                                          $exe_b = sqlsrv_query($conn, $query_b);
                                          $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                                          $id_zk = $fila_b['id'];
                                          $email_zk = $fila_b['email'];
                                          $email_zk = (empty($email_zk)) ? "Sin correo" : $email_zk ;
                                          echo '
                                                <tr>
                                                   <th scope="row"><center>'.$emp_code_a.'</center></td>
                                                   <td><center>'.$dept_name_a.'</center></td>
                                                   <td><center>'.$last_name_a.' '.$first_name_a.'</center></td>
                                                   <td><center>'.$position_name_a.'</center></td>
                                                   <td><center>'.$hire_date_a.'</center></td>
                                                   <td><center>'.$pago_a.'</center></td>
                                                   <td>
                                                      <center>
                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="employee_actions(`'.$emp_code_a.'`, 1)">Horario</button>
                                                            <button type="button" class="btn btn-warning btn-sm" title="Liberar del equpo de trabajo" onclick="liberar_sup_emp(`'.$emp_code_a.'`, 1)">Liberar</button>
                                                      </center>
                                                   </td>
                                                   <td>
                                                      <center>
                                                            <!--<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_dtl_employee" onclick="correo_empleado(`'.$emp_code_a.'`, 1)">Email</button>-->
                                                            '.$email_zk.'<br><a href="#" onclick="correo_empleado(`'.$id_zk.'`, `'.$email_zk.'`)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                                      </center>
                                                   </td>
                                                   <td>
                                                      <center>
                                                            <button type="button" class="btn btn-warning btn-sm" onclick="restablece_pin('.$emp_code_a.')">
                                                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                                            </button>
                                                      </center>
                                                   </td>
                                                </tr>
                                          ';
                                       }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="mdl_dtl_employee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del horario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="response_employee_actions"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="mdl_add_emp" tabindex="-1" aria-labelledby="mdl_add_empLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdl_add_empLabel">Agregar personal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label for="inp_num_empl_sup" class="form-label">Numero de empleado</label>
                                <input class="form-control" list="datalistOptions" id="inp_num_empl_sup" placeholder="Busqueda..." onkeyup="busca_emp_sup(event)">
                                <datalist id="datalistOptions">
                                    <?php
                                        $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                        $exe_1 = sqlsrv_query($conn, $query1);
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
                        <div id="response_super_emp">
                            <input type="hidden" id="inp_fname_sup" value="">
                            <input type="hidden" id="inp_lname_sup" value="">
                            <input type="hidden" id="inp_depto_sup" value="">
                            <input type="hidden" id="inp_pos_sup" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="btn_save_super()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Registro de horas -->
        <div class="modal fade" id="mdl_he_emp" tabindex="-1" aria-labelledby="mdl_he_empLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdl_he_empLabel">Registros de horas extra</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped" id="tb_te">
                            <thead>
                                <tr>
                                    <th scope="col">Num Emp</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Horas extra</th>
                                    <th scope="col">Fecha de tiempo extra</th>
                                    <th scope="col">Parte</th>
                                    <th scope="col">Lote</th>
                                    <th scope="col">Descripci&oacute;n</th>
                                    <th scope="col">Fecha de registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($super_conf > 0) {
                                        $sql_los_del_2 = "SELECT * FROM rh_supers_emps WHERE id_super = '$sesion_num_emp'";
                                        $exe_los_del_2 = sqlsrv_query($cnx, $sql_los_del_2);
                                        while ($fila_los_del_2 = sqlsrv_fetch_array($exe_los_del_2, SQLSRV_FETCH_ASSOC)) {
                                            $num_emp_asign_2 = $fila_los_del_2['num_emp_asign'];
                                            $sql_te_1 = "SELECT * FROM rh_te_hrs WHERE num_emp = '$num_emp_asign_2' AND fecha_te > '$f_y_h'";
                                            $exe_te_1 = sqlsrv_query($cnx, $sql_te_1);
                                            while ($fila_te_1 = sqlsrv_fetch_array($exe_te_1, SQLSRV_FETCH_ASSOC)) {
                                                $sql_te_2 = "SELECT [first_name], [last_name] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$num_emp_asign_2'";
                                                $exe_te_2 = sqlsrv_query($conn, $sql_te_2);
                                                $fila_te_2 = sqlsrv_fetch_array($exe_te_2, SQLSRV_FETCH_ASSOC);
                                                $first_name_2 = utf8_encode($fila_te_2['first_name']);
                                                $last_name_2 = utf8_encode($fila_te_2['last_name']);

                                                $num_orden_2 = $fila_te_1['num_orden'];
                                                $parte_2 = $fila_te_1['parte'];
                                                $lote_2 = $fila_te_1['lote'];
                                                $desc_parte_2 = $fila_te_1['desc_parte'];
                                                $fecha_te_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['fecha_te'])))), 5, 20);
                                                $tot_hrs_2 = $fila_te_1['tot_hrs'];
                                                $insert_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['insert_date'])))), 5, 20);

                                                echo '
                                                <tr>
                                                    <th scope="row">'.$num_emp_asign_2.'</th>
                                                    <td>'.$last_name_2.' '.$first_name_2.'</td>
                                                    <td>'.$tot_hrs_2.'</td>
                                                    <td>'.$fecha_te_2.'</td>
                                                    <td>'.$parte_2.'</td>
                                                    <td>'.$lote_2.'</td>
                                                    <td>'.$desc_parte_2.'</td>
                                                    <td>'.$insert_date_2.'</td>
                                                </tr>
                                                ';
                                            }
                                        }
                                    }else{
                                        $query_a = "SELECT
                                                pe.first_name, pe.last_name, pe.emp_code,
                                                pe.department_id, pe.position_id, pe.hire_date,
                                                pd.dept_name, ps.position_name, 
                                                pd.emp_code_charge, pd.parent_dept_id
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
                                                pe.enable_att = '1' AND pd.emp_code_charge = '$sesion_num_emp' OR pd.sub_emp_code_charge = '$sesion_num_emp' AND pe.enable_att = '1' ORDER BY pe.last_name ASC";
                                        $exe_a = sqlsrv_query($cnx, $query_a);
                                        while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                                            $first_name_a = utf8_encode($fila_a['first_name']);
                                            $last_name_a = utf8_encode($fila_a['last_name']);
                                            $emp_code_a = $fila_a['emp_code'];
                                            
                                            $sql_te_1 = "SELECT * FROM rh_te_hrs WHERE num_emp = '$emp_code_a' AND fecha_te > '$f_y_h'";
                                            $exe_te_1 = sqlsrv_query($cnx, $sql_te_1);
                                            while ($fila_te_1 = sqlsrv_fetch_array($exe_te_1, SQLSRV_FETCH_ASSOC)) {
                                                $sql_te_2 = "SELECT [first_name], [last_name] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_a'";
                                                $exe_te_2 = sqlsrv_query($conn, $sql_te_2);
                                                $fila_te_2 = sqlsrv_fetch_array($exe_te_2, SQLSRV_FETCH_ASSOC);
                                                $first_name_2 = utf8_encode($fila_te_2['first_name']);
                                                $last_name_2 = utf8_encode($fila_te_2['last_name']);

                                                $num_orden_2 = $fila_te_1['num_orden'];
                                                $parte_2 = $fila_te_1['parte'];
                                                $lote_2 = $fila_te_1['lote'];
                                                $desc_parte_2 = $fila_te_1['desc_parte'];
                                                $fecha_te_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['fecha_te'])))), 5, 10);
                                                $tot_hrs_2 = $fila_te_1['tot_hrs'];
                                                $insert_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['insert_date'])))), 5, 16);

                                                echo '
                                                <tr>
                                                    <th scope="row">'.$emp_code_a.'</th>
                                                    <td>'.$last_name_2.' '.$first_name_2.'</td>
                                                    <td><center>'.$tot_hrs_2.'</center></td>
                                                    <td>'.$fecha_te_2.'</td>
                                                    <td>'.$parte_2.'</td>
                                                    <td>'.$lote_2.'</td>
                                                    <td>'.$desc_parte_2.'</td>
                                                    <td>'.$insert_date_2.'</td>
                                                </tr>
                                                ';
                                            }
                                        }
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">Num Emp</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Horas extra</th>
                                    <th scope="col">Fecha de tiempo extra</th>
                                    <th scope="col">Parte</th>
                                    <th scope="col">Lote</th>
                                    <th scope="col">Descripci&oacute;n</th>
                                    <th scope="col">Fecha de registro</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!--DataTable-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#tb_empleados').DataTable({
                    "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    order: [[4, 'asc']]
                });
            });
            $(document).ready(function() {
                $('#tb_horarios').DataTable({
                    "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    }
                });
            });
            $(document).ready(function() {
                var r = 0;
                $('#tb_te').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    initComplete: function () {
                        this.api().columns().every(function () {
                            r++;
                            var column = this;
                            var select = $('<select class="form-select form-select-sm"><option value=""></option></select>').appendTo($(column.footer()).empty()).on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                        });
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excelHtml5',
                        title: 'reporte_TE',
                        text:'Descargar Excel'
                    }]
                });
            });
        </script>
    </body>
</html>

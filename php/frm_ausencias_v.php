<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."/////";
	}*/
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    include ('conn.php');
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

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

    $inp_id_empleado = $_POST['inp_id_empleado'];
    $inp_id_depto = $_POST['inp_id_depto'];
    $inp_finicial = $_POST['inp_finicial'];
    $inp_ffinal = $_POST['inp_ffinal'];
    $inp_hora_i = $_POST['inp_hora_i'];
    $inp_hora_s = $_POST['inp_hora_s'];
    $inp_f_hire = $_POST['inp_f_hire'];
    $txt_obs = $_POST['txt_obs'];
    $step = $_POST['step'];

    $v_ciclo = 'false';

    switch ($step) {
        case '1'://***1-> Solicitud

            $total_dias_sol = daysWeek($inp_finicial, $inp_ffinal);

            $inp_f_hire = date($inp_f_hire);
            //***sumar 1 año
            $v_hire_one = date("Y-m-d",strtotime($inp_f_hire."+ 1 year"));
            if ($fecha_now >= $v_hire_one) {
                //echo "||Ya tiene mas del año, puede pedir vacaciones";
                $v_inline = "true";
                $query_a = "SELECT TOT_VACACIONES FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$inp_id_empleado'";//***Numero de vacaciones por año
                $exe_a = sqlsrv_query($cnx, $query_a);
                $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                $TOT_VACACIONES = $fila_a['TOT_VACACIONES'];

                $query_b = "SELECT vacation_rule FROM personnel_employee WHERE emp_code = '$inp_id_empleado'";//***Numero de vacaciones restantes en el ciclo
                $exe_b = sqlsrv_query($cnx, $query_b);
                $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                $vacation_rule = $fila_b['vacation_rule'];

                if ($total_dias_sol > $vacation_rule) {
                    $v_ciclo = $vacation_rule;//***Solicita mas dias de los disponibles
                }else{
                    $v_ciclo = "false";//***Solicitud correcta
                }
            }else{
                //echo "||Tiene menos del año, sin vacaciones";
                $v_inline = "false";
            }

            echo $total_dias_sol."|".$v_inline."|".$v_ciclo;

            break;
        
        case '2'://***2-> Confirmacion
            
            $query_a = "
                INSERT INTO dbo.rh_vacaciones(
                    id_empleado, tipo_ausencia, f_ini,
                    f_fin, h_in, h_out,
                    f_ingreso, observaciones, dias_vac,
                    id_solicitante, id_depto, f_solicitud,
                    estatus)
                VALUES
                    ('$inp_id_empleado', 'Vacaciones', '$inp_finicial',
                    '$inp_ffinal', '$inp_hora_i', '$inp_hora_s',
                    '$inp_f_hire', '$txt_obs', '".daysWeek($inp_finicial, $inp_ffinal)."',
                    '$inp_id_empleado', '$inp_id_depto', '$fecha_hora_now',
                    '0');
                SELECT SCOPE_IDENTITY()";
            if ($exe_a = sqlsrv_query($cnx, $query_a)) {

                $query_select = "SELECT last_name, first_name, department_id FROM personnel_employee WHERE emp_code = '$inp_id_empleado'";
                $exe_select = sqlsrv_query($cnx, $query_select);
                $fila_select = sqlsrv_fetch_array($exe_select, SQLSRV_FETCH_ASSOC);
                $full_name = $fila_select['last_name']." ".$fila_select['first_name'];

                sqlsrv_next_result($exe_a); 
                sqlsrv_fetch($exe_a);
                $id_inserted = sqlsrv_get_field($exe_a, 0);

                $sql_valida = "SELECT COUNT(id) AS validacion FROM rh_supers_emps WHERE num_emp_asign = '$inp_id_empleado'";
                $exe_valida = sqlsrv_query($cnx, $sql_valida);
                $fila_valida = sqlsrv_fetch_array($exe_valida, SQLSRV_FETCH_ASSOC);
                $validacion = $fila_valida['validacion'];
                if ($validacion > 0) {
                    $sql_super = "SELECT id_super FROM rh_supers_emps WHERE num_emp_asign = '$inp_id_empleado'";
                    $exe_super = sqlsrv_query($cnx, $sql_super);
                    $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
                    $id_super = $fila_super['id_super'];
                    if ($id_super == $inp_id_empleado) {
                        $sql_A = "SELECT * FROM rh_jefe_directo WHERE num_emp = '$inp_id_empleado'";
                        $exe_A = sqlsrv_query($cnx, $sql_A);
                        $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
                        $num_emp_boss = $fila_A['num_emp_boss'];
        
                        if ($num_emp_boss == null) {
                            $query_boss = "SELECT
                                            p_dept.dept_name, sis.name, sis.email, p_dept.emp_code_charge 
                                        FROM
                                            personnel_department p_dept
                                        INNER JOIN
                                            rh_user_sys sis
                                        ON
                                            sis.num_empleado = p_dept.emp_code_charge
                                        WHERE
                                            p_dept.dept_code = '$dept_code'";
                            $exe_boss = sqlsrv_query($cnx, $query_boss);
                            $fila_boss = sqlsrv_fetch_array($exe_boss, SQLSRV_FETCH_ASSOC);
                            if($fila_boss != null){
                                $email_boss = $fila_boss['email'];
                                $emp_code_charge_boss = $fila_boss['emp_code_charge'];
                            }else{
                                $email_boss = '';
                            }
                        }else{
                            $query_B = "SELECT
                                    pe.email
                                FROM
                                    dbo.personnel_employee pe
                                WHERE
                                    pe.emp_code = '$num_emp_boss'";
                            $exe_B = sqlsrv_query($cnx, $query_B);
                            $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
                            $email_boss = $fila_B['email'];
                            $emp_code_charge_boss = $num_emp_boss;
        
                            if ($email_boss == null) {
                                $query_C = "SELECT * FROM rh_directivos WHERE CLAVE = '$num_emp_boss'";
                                $exe_C = sqlsrv_query($cnx, $query_C);
                                $fila_C = sqlsrv_fetch_array($exe_C, SQLSRV_FETCH_ASSOC);
                                $email_boss = $fila_C['CORREO'];
        
                                if ($email_boss == null) {
                                    $email_boss = '';
                                }
                            }
                        }
                    } else {
                        $sql_mail = "SELECT email FROM rh_user_sys WHERE rh_user_sys.num_empleado = '$id_super'";
                        $exe_mail = sqlsrv_query($cnx, $sql_mail);
                        $fila_mail = sqlsrv_fetch_array($exe_mail, SQLSRV_FETCH_ASSOC);
        
                        $email_boss = $fila_mail['email'];
                        $emp_code_charge_boss = $id_super;
                    }
                }else{
                    $sql_A = "SELECT * FROM rh_jefe_directo WHERE num_emp = '$inp_id_empleado'";
                    $exe_A = sqlsrv_query($cnx, $sql_A);
                    $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
                    $num_emp_boss = $fila_A['num_emp_boss'];

                    if ($num_emp_boss == null) {
                        $query_boss = "SELECT
                                        p_dept.dept_name, sis.name, sis.email, p_dept.emp_code_charge 
                                    FROM
                                        personnel_department p_dept
                                    INNER JOIN
                                        rh_user_sys sis
                                    ON
                                        sis.num_empleado = p_dept.emp_code_charge
                                    WHERE
                                        p_dept.dept_code = '".$fila_select['department_id']."'";
                        $exe_boss = sqlsrv_query($cnx, $query_boss);
                        $fila_boss = sqlsrv_fetch_array($exe_boss, SQLSRV_FETCH_ASSOC);
                        if($fila_boss != null){
                            $email_boss = $fila_boss['email'];
                            $emp_code_charge_boss = $fila_boss['emp_code_charge'];
                        }else{
                            $email_boss = '';
                        }
                    }else{
                        $query_B = "SELECT
                                pe.email
                            FROM
                                dbo.personnel_employee pe
                            WHERE
                                pe.emp_code = '$num_emp_boss'";
                        $exe_B = sqlsrv_query($cnx, $query_B);
                        $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
                        $email_boss = $fila_B['email'];
                        $emp_code_charge_boss = $num_emp_boss;

                        if ($email_boss == null) {
                            $query_C = "SELECT * FROM rh_directivos WHERE CLAVE = '$num_emp_boss'";
                            $exe_C = sqlsrv_query($cnx, $query_C);
                            $fila_C = sqlsrv_fetch_array($exe_C, SQLSRV_FETCH_ASSOC);
                            $email_boss = $fila_C['CORREO'];

                            if ($email_boss == null) {
                                $email_boss = '';
                            }
                        }
                    }
                }
                
                //*****************************************
                if(!empty($email_boss)){
                    $datos_bd = "db=rh_vacaciones&idbd=$id_inserted&cod_boss=$emp_code_charge_boss";
                    $datos_bd_b64 = base64_encode($datos_bd);
                    $msn ='
                        <html lang="es">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Solicitudes Novag</title>
                            </head>
                            <body>
                                <img src="http://aplicativo_rh.novag/images/novag_logo_small.png" width="160" height="100">
                                <h3>Estimado usuario</h3>
                                <p>El empleado <strong>'.$full_name.' ('.$inp_id_empleado.')</strong> solicita '.daysWeek($inp_finicial, $inp_ffinal).' dias de vacaciones del <strong>'.$inp_finicial.'</strong>, al <strong>'.$inp_ffinal.'</strong>.</p>
                                <p>Observaciones: <i>'.$txt_obs.'</i></p>
                                <p><strong>Acciones.</strong></p>
                                <!--<a href="http://aplicativo_rh.novag/action_mail/solicitud_s.php?action=1&db=rh_vacaciones&idbd='.$id_inserted.'&cod_boss='.$emp_code_charge_boss.'" target="_blank">Aceptar</a>&nbsp;&nbsp;<a href="http://aplicativo_rh.novag/action_mail/solicitud_s.php?action=2&db=rh_vacaciones&idbd='.$id_inserted.'&cod_boss='.$emp_code_charge_boss.'" target="_blank">Rechazar</a>&nbsp;&nbsp;<a href="http://192.168.1.12:88?user_mail='.$email_boss.'" target="_blank"">Modificar</a>-->

                                <a href="http://187.217.99.180:89/action_mail/solicitud_s.php?action=1&info='.$datos_bd_b64.'" target="_blank">Aceptar</a>&nbsp;&nbsp;<a href="http://187.217.99.180:89/action_mail/solicitud_s.php?action=2&info='.$datos_bd_b64.'" target="_blank">Rechazar</a>
                                <br><br>
                                <p><small>*No responder este correo.</small></p>
                            </body>
                        </html>';
                    $mail = new PHPMailer(true);
                    try {
                        //$mail->SMTPDebug = 2;  // Sacar esta línea para no mostrar salida debug
                        $mail->isSMTP();
                        $mail->Host = 'smtp.office365.com';  // Host de conexión SMTP
                        $mail->SMTPAuth = true;
                        $mail->Username = 'rh.novag@novag.com.mx';                 // Usuario SMTP
                        $mail->Password = 'Saq9263112';                           // Password SMTP
                        $mail->SMTPSecure = 'tls';                            // Activar seguridad TLS
                        $mail->Port = 587;                                    // Puerto SMTP
                    
                        #$mail->SMTPOptions = ['ssl'=> ['allow_self_signed' => true]];  // Descomentar si el servidor SMTP tiene un certificado autofirmado
                        #$mail->SMTPSecure = false;				// Descomentar si se requiere desactivar cifrado (se suele usar en conjunto con la siguiente línea)
                        #$mail->SMTPAutoTLS = false;			// Descomentar si se requiere desactivar completamente TLS (sin cifrado)
                    
                        $mail->setFrom('rh.novag@novag.com.mx');		// Mail del remitente
                        //$mail->addAddress('reramirez@novag.com.mx');     // Mail del destinatario
                        $mail->addAddress($email_boss);     // Mail del destinatario

                        $mail->isHTML(true);
                        $mail->Subject = 'Solicitud de vacaciones #'.$id_inserted;  // Asunto del mensaje
                        $mail->Body    = $msn;    // Contenido del mensaje (acepta HTML)
                        //$mail->AltBody = 'Este es el contenido del mensaje en texto plano';    // Contenido del mensaje alternativo (texto plano)
                    
                        $mail->send();
                        //ec6ho 'El mensaje ha sido enviado';
                    } catch (Exception $e) {
                        //echo 'El mensaje no se ha podido enviar, error: ', $mail->ErrorInfo;
                    }
                }
                //*****************************************

                /*sqlsrv_next_result($exe_a); 
                sqlsrv_fetch($exe_a);
                $id_inserted = sqlsrv_get_field($exe_a, 0);*/
                echo "1|".$id_inserted;
            }else{
                echo 2;
            }
            break;
    }
?>
<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."/////";
	}*/
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $fecha_now = date('Y-m-d');
    $y_today = date('Y');
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
                //$days--;//***-> Omitir los fines de semana
            }elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;//***-> Quitar los dias de descanso oficial laboral
            }
        }

        //echo $days;
        return($days);
    }
    //************************

    $inp_id_empleado = $_POST['inp_id_empleado'];
    $inp_id_depto = $_POST['inp_id_depto'];
    /*$inp_finicial = $_POST['inp_finicial'];
    $inp_ffinal = $_POST['inp_ffinal'];*/
    $inp_arrdates = $_POST['inp_arrdates'];
    $inp_f_regreso = $_POST['inp_f_regreso'];
    $chk_lun = $_POST['chk_lun'];
    $chk_lun_res = ($chk_lun == 'true') ? '1' : '0' ;
    $chk_mar = $_POST['chk_mar'];
    $chk_mar_res = ($chk_mar == 'true') ? '1' : '0' ;
    $chk_mie = $_POST['chk_mie'];
    $chk_mie_res = ($chk_mie == 'true') ? '1' : '0' ;
    $chk_jue = $_POST['chk_jue'];
    $chk_jue_res = ($chk_jue == 'true') ? '1' : '0' ;
    $chk_vie = $_POST['chk_vie'];
    $chk_vie_res = ($chk_vie == 'true') ? '1' : '0' ;
    $chk_sab = $_POST['chk_sab'];
    $chk_sab_res = ($chk_sab == 'true') ? '1' : '0' ;
    $chk_dom = $_POST['chk_dom'];
    $chk_dom_res = ($chk_dom == 'true') ? '1' : '0' ;
    $inp_hora_i = $_POST['inp_hora_i'];
    $inp_hora_s = $_POST['inp_hora_s'];
    $inp_f_hire = $_POST['inp_f_hire'];
    $txt_obs = $_POST['txt_obs'];
    $step = $_POST['step'];

    $v_ciclo = 'false';
    $total_dias_sol = count($inp_arrdates);

    switch ($step) {
        case '1'://***1-> Solicitud

            $total_dias_sol = count($inp_arrdates);

            $unicos_dias_arr = array_unique($inp_arrdates);
            $total_dias_unico = count($unicos_dias_arr);

            if ($total_dias_sol > $total_dias_unico) {
                echo "NA|NA|NA";
                exit();
            }else{
                foreach ($inp_arrdates as $key => $value) {
                    $busca_coincidencia = "SELECT COUNT(id) AS coincidencia FROM rh_vacaciones WHERE rh_vacaciones.id_empleado = '$inp_id_empleado' AND estatus != '2' AND fecha_array LIKE '%$value%'";
                    $exe_coincidencia = sqlsrv_query($cnx, $busca_coincidencia);
                    $fila_coincidencia = sqlsrv_fetch_array($exe_coincidencia, SQLSRV_FETCH_ASSOC);
                    $coincidencia = $fila_coincidencia['coincidencia'];
                    if ($coincidencia > 0) {
                        $busca_coincidencia_c = "SELECT id FROM rh_vacaciones WHERE rh_vacaciones.id_empleado = '$inp_id_empleado' AND estatus != '2' AND fecha_array LIKE '%$value%'";
                        $exe_coincidencia_c = sqlsrv_query($cnx, $busca_coincidencia_c);
                        $fila_coincidencia_c = sqlsrv_fetch_array($exe_coincidencia_c, SQLSRV_FETCH_ASSOC);
                        $id_c = $fila_coincidencia_c['id'];
                        echo "1|".$value.",".$id_c."|NA";
                        exit();
                    }else{
                        if ($inp_f_regreso == $value) {
                            echo "2|NA|NA";
                            exit();
                        }
                    }
                }
            }

            $inp_f_hire = date($inp_f_hire);
            //***sumar 1 año
            $v_hire_one = date("Y-m-d",strtotime($inp_f_hire."+ 1 year"));
            if ($fecha_now >= $v_hire_one) {
                $v_inline = "true";
                $v_vence_dias = date("Y",strtotime($fecha_hora_now."- 547 days"));
                for ($i=$v_vence_dias; $i <= $y_today; $i++) { 
                    $query_a = "SELECT y_$i FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$inp_id_empleado'";
                    $exe_a = sqlsrv_query($cnx, $query_a);
                    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                    $y_dias_v = $fila_a['y_'.$i];
                    if ($y_dias_v > 0) {
                        break;
                    }
                }

                if ($total_dias_sol > $y_dias_v) {
                    $v_ciclo = $y_dias_v;//***Solicita mas dias de los disponibles
                }else{
                    $v_ciclo = "false";//***Solicitud correcta
                }
            }else{//***Tiene menos del año, sin vacaciones
                $v_inline = "false";
            }

            echo $total_dias_sol."|".$v_inline."|".$v_ciclo;

            break;
        
        case '2'://***2-> Confirmacion
            
            $msn_body = '';
            $txt_arr_date = '';
            foreach ($inp_arrdates as $key => $value) {
                $pos = $key+1;
                $msn_body .= '
                    <tr>
                        <td><center>'.$pos.'</center></td>
                        <td><center>'.$value.'</center></td>
                    </tr>
                ';
                $txt_arr_date .= $value.'|';
            }

            $query_a = "
                INSERT INTO dbo.rh_vacaciones(
                    id_empleado, tipo_ausencia, 
                    h_in, h_out,
                    f_ingreso, observaciones, dias_vac,
                    id_solicitante, id_depto, f_solicitud,
                    estatus, fecha_array, dia_lun,
                    dia_mar, dia_mie, dia_jue,
                    dia_vie, dia_sab, dia_dom,
                    fecha_regreso)
                VALUES
                    ('$inp_id_empleado', 'Vacaciones', 
                    '$inp_hora_i', '$inp_hora_s',
                    '$inp_f_hire', '$txt_obs', '$total_dias_sol',
                    '$inp_id_empleado', '$inp_id_depto', '$fecha_hora_now',
                    '0', '$txt_arr_date', '$chk_lun_res',
                    '$chk_mar_res', '$chk_mie_res', '$chk_jue_res', 
                    '$chk_vie_res', '$chk_sab_res', '$chk_dom_res',
                    '$inp_f_regreso');
                SELECT SCOPE_IDENTITY()";
            if ($exe_a = sqlsrv_query($cnx, $query_a)) {

                $query_select = "SELECT last_name, first_name, department_id FROM personnel_employee WHERE emp_code = '$inp_id_empleado'";
                $exe_select = sqlsrv_query($cnx, $query_select);
                $fila_select = sqlsrv_fetch_array($exe_select, SQLSRV_FETCH_ASSOC);
                $full_name = $fila_select['last_name']." ".$fila_select['first_name'];

                $query_dept = "SELECT dept_code FROM personnel_department WHERE id = '".$fila_select['department_id']."'";
                $exe_dept = sqlsrv_query($cnx, $query_dept);
                $fila_dept = sqlsrv_fetch_array($exe_dept, SQLSRV_FETCH_ASSOC);
                $dept_code = $fila_dept['dept_code'];

                sqlsrv_next_result($exe_a); 
                sqlsrv_fetch($exe_a);
                $id_inserted = sqlsrv_get_field($exe_a, 0);

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
                        //$email_boss = 'reramirez@novag.com.mx';
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

                if ($email_boss == '') {
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

                            if(empty($email_boss)){
                                $query_B = "SELECT
                                        pe.email
                                    FROM
                                        dbo.personnel_employee pe
                                    WHERE
                                        pe.emp_code = '$id_super'";
                                $exe_B = sqlsrv_query($cnx, $query_B);
                                $fila_B = sqlsrv_fetch_array($exe_B, SQLSRV_FETCH_ASSOC);
                                $email_boss = $fila_B['email'];
                                //$emp_code_charge_boss = $id_super;
                            }
                        }
                    }
                }

                
                //*****************************************
                if(!empty($email_boss)){
                    $datos_bd = "db=rh_vacaciones&idbd=$id_inserted&cod_boss=$emp_code_charge_boss";
                    $datos_bd_b64 = base64_encode($datos_bd);
                    $msn = '
                        <html lang="es">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Solicitudes Novag</title>
                            </head>
                            <body>
                                <img src="http://aplicativo_rh.novag/images/novag_logo_small.png" width="160" height="100">
                                <h3>Estimado usuario, </h3>
                                <p>El empleado <strong>'.$full_name.' ('.$inp_id_empleado.')</strong> solicita <strong>'.$total_dias_sol.' dias</strong> de vacaciones en las siguientes fechas:</p>
                                <table border="1">
                                    <thead>
                                        <tr>
                                            <td><center>#</center></td>
                                            <td><center>Fechas</center></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        '.$msn_body.'
                                    </tbody>
                                </table>
                                <p>Observaciones: <i>'.$txt_obs.'</i></p>
                                <p><strong>Respuestas automaticas</strong></p>
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

                $btn_imprime = '
                    <button type="button" class="w-100 btn btn-primary btn-lg" title="Descargar formato" onclick="download_formatos(`rh_vacaciones`, '.$id_inserted.', '.$inp_id_empleado.')">
                        Descargar formato
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </button>
                ';

                echo "1|".$id_inserted."|".$btn_imprime;
            }else{
                echo 2;
            }
            break;
    }
?>
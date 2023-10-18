<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."/////";
	}
    exit();*/
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('conn.php');
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    
    $rdo_tipo_Val_ausencia = $_POST['rdo_tipo_Val_ausencia'];
    $tipo_Val_ausencia = '';
    switch ($rdo_tipo_Val_ausencia) {
        case '1':
            $tipo_Val_ausencia = 'Permiso';
            break;
        
        case '2':
            $tipo_Val_ausencia = 'Comision';
            break;

        case '3':
            $tipo_Val_ausencia = 'Suspension';
            break;

        case '4':
            $tipo_Val_ausencia = 'Otros';
            break;
    }
    $rdo_tipo_Val_goce = $_POST['rdo_tipo_Val_goce'];
    $tipo_Val_goce = '';
    switch ($rdo_tipo_Val_goce) {
        case '1':
            $tipo_Val_goce = 'Con goce de sueldo';
            break;
        
        case '2':
            $tipo_Val_goce = 'Sin goce de sueldo';
            break;
    }
    $inp_finicial = $_POST['inp_finicial'];
    $inp_ffinal = $_POST['inp_ffinal'];
    $txt_obs = $_POST['txt_obs'];
    $inp_id_empleado = $_POST['inp_id_empleado'];
    $inp_id_depto = $_POST['inp_id_depto'];

    if ((($rdo_tipo_Val_ausencia == '3') || ($rdo_tipo_Val_ausencia == '4')) && ($rdo_tipo_Val_goce == '1')) {
        $inp_hr_in = '';
        $inp_hr_out = '';
    }else{
        $inp_hr_in = $_POST['inp_hr_in'];
        $inp_hr_out = $_POST['inp_hr_out'];
    }
    
    $rdo_tipo_txt_permisos = '';
    if ($rdo_tipo_Val_ausencia == '1') {
        $rdo_tipo_Val_permisos = $_POST['rdo_tipo_Val_permisos'];
        switch ($rdo_tipo_Val_permisos) {
            case '1':
                $rdo_tipo_txt_permisos = 'Paternidad';
                break;
            
            case '2':
                $rdo_tipo_txt_permisos = 'Defunsión';
                break;
            
            case '3':
                $rdo_tipo_txt_permisos = 'Incapacidad interna(Uso exclusivo medico Novag)';
                break;

            case '4':
                $rdo_tipo_txt_permisos = 'Otros';
                break;
        }
    }else{
        $rdo_tipo_Val_permisos = "0";
    }

    $query_a = "
        INSERT INTO dbo.rh_solicitudes(
            id_empleado, tipo_ausencia, tipo_goce,
            f_ini, f_fin, observaciones,
            f_solicitud, id_solicitante, id_depto,
            estatus, hr_in, hr_out, tipo_permiso)
        VALUES
            ('$inp_id_empleado', '$tipo_Val_ausencia', '$tipo_Val_goce',
            '$inp_finicial', '$inp_ffinal', '$txt_obs',
            '$fecha_hora_now', '$inp_id_empleado', '$inp_id_depto',
            '0', '$inp_hr_in', '$inp_hr_out', '$rdo_tipo_Val_permisos');
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
                //$email_boss = 'reramirez@novag.com.mx';
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
                }
            }
        }
        
        $retVal_a = ($rdo_tipo_Val_ausencia == '1') ? ' por <strong>'.$rdo_tipo_txt_permisos.'</strong>' : '' ;
        //*****************************************
        if(!empty($email_boss)){
            $datos_bd = "db=rh_solicitudes&idbd=$id_inserted&cod_boss=$emp_code_charge_boss";
            $datos_bd_b64 = base64_encode($datos_bd);

            $msn ='
                <html lang="es">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Solicitudes Novag</title>
                    </head>
                    <body>
                        <img src="http://aplicativo_rh.novag/images/novag_logo_small.png" width="160" height="100">
                        <h3>Estimado usuario, </h3>
                        <p>El empleado <strong>'.$full_name.' ('.$inp_id_empleado.')</strong> envia una solicitud de tipo <strong>'.$tipo_Val_ausencia.'</strong>'.utf8_decode($retVal_a).', del <strong>'.$inp_finicial.'</strong> al <strong>'.$inp_ffinal.'</strong>.</p>
                        <p>Observaciones: <i>'.$txt_obs.'</i></p>
                        <p><strong>Acciones.</strong></p>
                        <!--<a href="http://aplicativo_rh.novag/action_mail/solicitud_s.php?action=1&db=rh_solicitudes&idbd='.$id_inserted.'&cod_boss='.$emp_code_charge_boss.'" target="_blank">Aceptar</a>&nbsp;&nbsp;<a href="http://aplicativo_rh.novag/action_mail/solicitud_s.php?action=2&db=rh_solicitudes&idbd='.$id_inserted.'&cod_boss='.$emp_code_charge_boss.'" target="_blank">Rechazar</a>&nbsp;&nbsp;<a href="http://192.168.1.12:88?user_mail='.$email_boss.'" target="_blank"">Modificar</a>-->

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
                $mail->Subject = 'Solicitud de ausencia #'.$id_inserted;  // Asunto del mensaje
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
            <button type="button" class="w-100 btn btn-primary btn-lg" title="Descargar formato" onclick="download_formatos(`rh_solicitudes`, '.$id_inserted.', '.$inp_id_empleado.')">
                Descargar formato
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            </button>
        ';

        echo "1|".$id_inserted."|".$btn_imprime;
    }else{
        echo 2;
    }
?>
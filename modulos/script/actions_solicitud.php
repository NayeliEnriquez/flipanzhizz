<?php
    /*while($post = each($_POST)){
		echo $post[0]." = ".$post[1]."||";
	}*/
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../php/PHPMailer/src/Exception.php';
    require '../../php/PHPMailer/src/PHPMailer.php';
    require '../../php/PHPMailer/src/SMTP.php';
    session_start();
    $name_a_session = $_SESSION['name_a'];
    $num_empleado_a_session = $_SESSION['num_empleado_a'];
    $cadena_dg = "7050, 2382, 7046, 2729, 7048, 7051, 7035, 7042, 7049, 2983, 2822";
    $val_dg = strpos($cadena_dg, $num_empleado_a_session);

    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('../../php/conn.php');
    $mov = $_POST['mov'];
    $inp_id_table = $_POST['inp_id_table'];
    $inp_table = $_POST['inp_table'];
    $txt_obs = $_POST['txt_obs'];

    $tipo_Val_goce = '';

    //$mov_desc = ($mov == '1') ? 'aceptada' : 'rechazada' ;
    if ($mov == '1') {
        $mov_desc = 'aceptada';
    }elseif ($mov == '2') {
        $mov_desc = 'rechazada';
    }elseif ($mov == '5') {
        $mov_desc = 'rechazo posterior';
    }
    $txt_hist = "\nSolicitud ".$mov_desc." por ".$name_a_session." con fecha ".$fecha_hora_now;

    $query_slct = "SELECT id_empleado FROM $inp_table WHERE id = '$inp_id_table'";
    $exe_slct = sqlsrv_query($cnx, $query_slct);
    $fila_slct = sqlsrv_fetch_array($exe_slct, SQLSRV_FETCH_ASSOC);
    $id_empleado = $fila_slct['id_empleado'];

    switch ($inp_table) {
        case 'rh_solicitudes':
            $v_soli = "AUSENCIA";
            $rdo_tipo_Val_goce = $_POST['rdo_tipo_Val_goce'];
            switch ($rdo_tipo_Val_goce) {
                case '1':
                    $tipo_Val_goce = 'Con goce de sueldo';
                    if ($val_dg !== false) {
                        $mov = $mov;
                    }else{
                        $mov = 3;
                    }
                    break;
                
                case '2':
                    $tipo_Val_goce = 'Sin goce de sueldo';
                    break;
            }

            $query_a = "UPDATE $inp_table SET tipo_goce = '$tipo_Val_goce', observaciones = '$txt_obs', estatus = '$mov', historico = CONCAT(historico, '$txt_hist') WHERE id = '$inp_id_table'";
            break;
        
        case 'rh_salida':
            $v_soli = "ENTRADA/SALIDA";
            $rdo_tipo_Val_goce = $_POST['rdo_tipo_Val_goce'];
            if ($val_dg !== false) {
                $mov = $mov ;
            }else{
                $mov = ($rdo_tipo_Val_goce == '1') ? '3' : $mov ;
            }
            $inp_fecha_p = $_POST['inp_fecha_p'];
            $inp_hora_p = $_POST['inp_hora_p'];
            $chk_reponer = $_POST['chk_reponer'];
            if ($chk_reponer == 'true') {
                $reponer_response = "1";//***Si va a reponer tiempo
            }else{
                $reponer_response = "0";//***No va a reponer tiempo
            }

            $query_a = "UPDATE $inp_table SET sueldo = '$rdo_tipo_Val_goce', f_permiso = '$inp_fecha_p', h_permiso = '$inp_hora_p', reposicion = '$reponer_response', observaciones = '$txt_obs', estatus = '$mov', historico = CONCAT(historico, '$txt_hist') WHERE id = '$inp_id_table'";
            break;

        case 'rh_vacaciones':
            $v_soli = "VACACIONES";
            $inp_arrdates = $_POST['inp_arrdates'];
            $inp_dg = $_POST['inp_dg'];

            if ($inp_dg == '1') {
                $mov = ($mov == '1') ? '3' : $mov ;
            }else{
                $total_dias_sol = count($inp_arrdates);
                $fecha_now = date('Y-m-d');
                $y_today = date('Y');

                $query_a = "SELECT id, hire_date, first_name, last_name FROM personnel_employee WHERE emp_code = '$id_empleado'";
                $exe_a = sqlsrv_query($conn, $query_a);
                $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                $id_1 = $fila_a['id'];
                $first_name_1 = $fila_a['first_name'];
                $last_name_1 = $fila_a['last_name'];
                $hire_date = date(substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10));
                $hire_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 8, 4);
                $v_hire_one = date("Y-m-d",strtotime($hire_date."+ 1 year"));

                $v_vence_dias = date("Y",strtotime($fecha_now."- 18 month"));

                for ($i=$v_vence_dias; $i <= $y_today; $i++) { 
                    $v_caduca = date("Y-m-d",strtotime($i."-".$hire_date_2."+ 18 month"));
                    if ($fecha_now > $v_caduca) {
                        continue;
                    }
                    $query_a = "SELECT y_$i FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$id_empleado'";
                    $exe_a = sqlsrv_query($cnx, $query_a);
                    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
                    $y_dias_v = $fila_a['y_'.$i];
                    if ($y_dias_v > 0) {
                        break;
                    }
                }

                //echo "solicitados: ".$total_dias_sol." dias disponibles: ".$y_dias_v;
                if ($total_dias_sol > $y_dias_v) {
                    //***Tomamos los dias restantes del ciclo
                    $restamos_dias = $total_dias_sol-$y_dias_v;
                    //echo "<br>Tomamos: ".$y_dias_v." y los restamos a los solicitados ".$total_dias_sol." Entonces quedan: ".$restamos_dias." Por lo tanto debemos actualizar el primer ciclo y revisar el siguiente para ver si hay disponibles o mandar un mensaje de aviso";
                    $query_paso_A = "UPDATE rh_employee_gen SET y_$i = 0 WHERE rh_employee_gen.CLAVE = '$id_empleado'";
                    sqlsrv_query($cnx, $query_paso_A);
                    $j = $i+1;//***Incrementamos un año para descontar del siguiente periodo
                    $query_paso_B = "UPDATE rh_employee_gen SET y_$j = CASE WHEN y_$j IS NULL THEN 0 - $restamos_dias ELSE y_$j - $restamos_dias END WHERE rh_employee_gen.CLAVE = '$id_empleado'";
                    sqlsrv_query($cnx, $query_paso_B);
                }else{
                    $query_updt = "UPDATE rh_employee_gen SET y_$i = y_$i - $total_dias_sol WHERE rh_employee_gen.CLAVE = '$id_empleado'";
                    sqlsrv_query($cnx, $query_updt);
                }
            }

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

            if ($mov == '1') {
                $d_txt = ($total_dias_sol > 1) ? 's' : '' ;
                $msn ='
                    <html lang="es">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <title>Vacaciones aprobadas</title>
                        </head>
                        <body>
                            <center><img src="http://aplicativo_rh.novag/images/novag_logo_small.png" width="185" height="90"></center>
                            <h3>A quien corresponda.</h3>
                            <p>El empleado '.$id_empleado.' - '.$last_name_1.' '.$first_name_1.' solicito '.$total_dias_sol.' d&iacute;a'.$d_txt.' de vacaciones y fue '.substr($txt_hist, 11).'.</p>
                            <p>Dentro del aplicativo se encuentran los detalles de la solicitud con <strong>folio #'.$inp_id_table.'</strong>.</p>
                            <p>Para mayor informaci&oacute;n, dar click <a href="http://aplicativo_rh.novag" target="_blank"> aqu&iacute;</a></p>
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
                
                    $mail->setFrom('rh.novag@novag.com.mx');		// Mail del remitente
                    $mail->addAddress('smejia@novag.com.mx');
                    $mail->addAddress('recursos.humanos@novag.com.mx');
                    $mail->addAddress('cmendoza@novag.com.mx');
                    $mail->addAddress('recursoshumanos.tz@novag.com.mx');
                    //$mail->addAddress('reramirez@novag.com.mx');
                    $mail->isHTML(true);
                    $mail->Subject = 'Solicitud de vacaciones';  // Asunto del mensaje
                    $mail->Body    = $msn;    // Contenido del mensaje (acepta HTML)
                    //$mail->send();
                    //echo 'El mensaje ha sido enviado';
                } catch (Exception $e) {
                    //echo 'El mensaje no se ha podido enviar, error: ', $mail->ErrorInfo;
                }

                $id_zks = "";
                /*foreach ($inp_arrdates as $key => $value) {
                    $sql_zk1 = "INSERT INTO [dbo].[workflow_abstractexception]
                            ([audit_status])
                        VALUES
                            ('1');
                        SELECT SCOPE_IDENTITY()";
                    if ($exe_zk1 = sqlsrv_query($conn, $sql_zk1)) {
                        sqlsrv_next_result($exe_zk1); 
                        sqlsrv_fetch($exe_zk1);
                        $abstractexception_ptr_id = sqlsrv_get_field($exe_zk1, 0);
                    }
                    
                    $id_zks .= $abstractexception_ptr_id.",";

                    $sql_zk2 = "INSERT INTO [dbo].[att_leave]
                            ([abstractexception_ptr_id],[start_time],[end_time]
                            ,[type],[apply_reason],[apply_time]
                            ,[audit_time],[vacation_number]
                            ,[category_id],[employee_id])
                        VALUES
                            ('$abstractexception_ptr_id','$value 00:00:00.000','$value 23:00:00.000'
                            ,'1','VACACIONES','$fecha_hora_now'
                            ,'$fecha_hora_now','1'
                            ,'7','$id_1')";
                    sqlsrv_query($conn, $sql_zk2);
                }
                $id_zks = substr($id_zks, 0, -1);*/
            }elseif ($mov == '5') {
                $query_slct = "SELECT id_empleado, abstractexception_ptr_id FROM $inp_table WHERE id = '$inp_id_table'";
                $exe_slct = sqlsrv_query($cnx, $query_slct);
                $fila_slct = sqlsrv_fetch_array($exe_slct, SQLSRV_FETCH_ASSOC);
                $id_empleado = $fila_slct['id_empleado'];
                $abstractexception_ptr_id = $fila_slct['abstractexception_ptr_id'];

                /*$query_c = "DELETE FROM [zkbiotime].[dbo].[att_leave] WHERE [abstractexception_ptr_id] IN ($abstractexception_ptr_id)";
                sqlsrv_query($conn, $query_c);
                $query_d = "DELETE FROM [zkbiotime].[dbo].[workflow_abstractexception]  WHERE id IN ($abstractexception_ptr_id)";
                sqlsrv_query($conn, $query_d);*/

                $id_zks = $abstractexception_ptr_id;
            }

            $query_a = "UPDATE $inp_table SET fecha_array = '$txt_arr_date', observaciones = '$txt_obs', estatus = '$mov', rh_status = '0', historico = CONCAT(historico, '$txt_hist'), abstractexception_ptr_id = '$id_zks' WHERE id = '$inp_id_table'";
            
            break;
    }

    /*echo ($query_a);
    exit();*/
    if (sqlsrv_query($cnx, $query_a)) {
        $query_b = "SELECT [first_name], [last_name], [email] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$id_empleado'";
        $exe_b = sqlsrv_query($conn, $query_b);
        $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
        $email_zk = $fila_b['email'];
        $first_name_zk = $fila_b['first_name'];
        $last_name_zk = $fila_b['last_name'];
        if (!empty($email_zk)) {
            $msn ='
                <html lang="es">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Respuesta de solicitud</title>
                    </head>
                    <body>
                        <center><img src="http://aplicativo_rh.novag/images/novag_logo_small.png" width="185" height="90"></center>
                        <h3>Estimado '.$first_name_zk.' '.$last_name_zk.'.</h3>
                        <p>Le comentamos que su solicitud de tipo <strong>'.$v_soli.' con folio #'.$inp_id_table.'</strong> ha sido '.substr($txt_hist, 11).'.</p>
                        <p>Para mayor informacion, dar click <a href="http://aplicativo_rh.novag/public_formats/estatus_solicitud.php?sol='.$inp_table.'&id_bd='.$inp_id_table.'&emp_code='.$id_empleado.'" target="_blank"> aqui</a></p>
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
                $mail->addAddress($email_zk);     // Mail del destinatario
    
                /*$attachment = 'file/'.$slc_year.'_semana'.$slc_semana.'_'.$ahora.'.pdf';
                $mail->AddAttachment($attachment);*/
    
                $mail->isHTML(true);
                $mail->Subject = 'Respuesta de solicitud';  // Asunto del mensaje
                $mail->Body    = $msn;    // Contenido del mensaje (acepta HTML)
                //$mail->AltBody = 'Este es el contenido del mensaje en texto plano';    // Contenido del mensaje alternativo (texto plano)
            
                $mail->send();
                //echo 'El mensaje ha sido enviado';
            } catch (Exception $e) {
                //echo 'El mensaje no se ha podido enviar, error: ', $mail->ErrorInfo;
            }
        }
        echo 1;
    }else{
        echo 0;
    }
    /*$query_a = "SELECT * FROM $inp_table WHERE id = '$inp_id_table'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $id_empleado_a = $fila_a['id_empleado'];
    echo ($id_empleado_a);*/
?>
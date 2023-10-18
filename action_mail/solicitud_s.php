<?php
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require '../php/PHPMailer/src/Exception.php';
   require '../php/PHPMailer/src/PHPMailer.php';
   require '../php/PHPMailer/src/SMTP.php';
   include ('../php/conn.php');
   date_default_timezone_set("America/Mazatlan");
   $fecha_hora_now = date('Y-m-d H:i:s');
   $fecha_now = date('Y-m-d');
   $action = $_GET['action'];
   $db = $_GET['db'];
   $idbd = $_GET['idbd'];
   $cod_boss = $_GET['cod_boss'];
   $info = $_GET['info'];

   $datos_bd_b64 = $info;
   $datos_bd = base64_decode($datos_bd_b64);
   $datos_bd_arr = explode('&', $datos_bd);

   $db_arr = $datos_bd_arr[0];
   $db_arr = explode('=', $db_arr);
   $db = $db_arr[1];

   $idbd_arr = $datos_bd_arr[1];
   $idbd_arr = explode('=', $idbd_arr);
   $idbd = $idbd_arr[1];

   $cod_boss_arr = $datos_bd_arr[2];
   $cod_boss_arr = explode('=', $cod_boss_arr);
   $cod_boss = $cod_boss_arr[1];

   $query_comp = "";

   $query_boss = "SELECT * FROM rh_user_sys WHERE num_empleado = '$cod_boss'";
   $exe_boss = sqlsrv_query($cnx, $query_boss);
   $fila_boss = sqlsrv_fetch_array($exe_boss, SQLSRV_FETCH_ASSOC);
   $name_boss = $fila_boss['name'];

   $query_check = "SELECT * FROM $db WHERE id = '$idbd' AND estatus = '0'";
   $exe_check = sqlsrv_query($cnx, $query_check);
   $fila_check = sqlsrv_fetch_array($exe_check, SQLSRV_FETCH_ASSOC);
   if($fila_check != null){
      $mov_desc = ($action == '1') ? 'aceptada' : 'rechazada' ;
      $txt_hist = "\nSolicitud ".$mov_desc." por ".$name_boss." con fecha ".$fecha_hora_now;
      $v_id_empleado = $fila_check['id_empleado'];
      
      $query_b = "SELECT [id], [first_name], [last_name], [email] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$v_id_empleado'";
      $exe_b = sqlsrv_query($conn, $query_b);
      $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
      $id_1 = $fila_b['id'];
      $email_zk = $fila_b['email'];
      $first_name_zk = $fila_b['first_name'];
      $last_name_zk = $fila_b['last_name'];

      switch ($db) {
         case 'rh_solicitudes':
               $v_soli = "AUSENCIA";
               $v_tipo_goce = $fila_check['tipo_goce'];
               if ($v_tipo_goce == 'Con goce de sueldo') {
                  $v_sueldo = 1;
               }
               break;

         case 'rh_salida':
               $v_soli = "ENTRADA/SALIDA";
               $v_sueldo = $fila_check['sueldo'];
               break;
         
         default:
            $v_soli = "VACACIONES";
            $inp_arrdates = substr(trim($fila_check['fecha_array']), 0, -1);
            $inp_arrdates = explode("|", $inp_arrdates);

            $total_dias_sol = count($inp_arrdates);
            $fecha_now = date('Y-m-d');
            $y_today = date('Y');

            $query_a = "SELECT id, hire_date, first_name, last_name FROM personnel_employee WHERE emp_code = '$v_id_empleado'";
            $exe_a = sqlsrv_query($conn, $query_a);
            $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
            $id_1 = $fila_a['id'];
            $first_name_1 = $fila_a['first_name'];
            $last_name_1 = $fila_a['last_name'];
            $hire_date = date(substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10));
            $hire_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 8, 4);

            if ($action == '1') {
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
                           <p>El empleado '.$v_id_empleado.' - '.$last_name_1.' '.$first_name_1.' solicito '.$total_dias_sol.' d&iacute;a'.$d_txt.' de vacaciones y fue '.substr($txt_hist, 11).'.</p>
                           <p>Dentro del aplicativo se encuentran los detalles de la solicitud con <strong>folio #'.$idbd.'</strong>.</p>
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
            }

            break;
      }

      if($action == '1'){
         if($v_sueldo == 1){
               $action = '3';
         }
      }
      
      $query_a = ($v_soli == "VACACIONES") ? "UPDATE $db SET estatus = '$action', rh_status = '0', historico = CONCAT(historico, '$txt_hist'), abstractexception_ptr_id = '$id_zks' WHERE id = '$idbd'" : "UPDATE $db SET estatus = '$action', historico = CONCAT(historico, '$txt_hist')$query_comp WHERE id = '$idbd'" ;

      //$query_a = "UPDATE $db SET estatus = '$action', historico = CONCAT(historico, '$txt_hist')$query_comp WHERE id = '$idbd'";
      /*echo($query_a);
      exit();*/
      sqlsrv_query($cnx, $query_a);

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
                     <p>Su solicitud de tipo <strong>'.$v_soli.' con folio #'.$idbd.'</strong> ha sido '.substr($txt_hist, 11).'.</p>
                     <p>Para mayor informacion, dar click <a href="http://aplicativo_rh.novag/public_formats/estatus_solicitud.php?sol='.$db.'&id_bd='.$idbd.'&emp_code='.$v_id_empleado.'" target="_blank"> aqui</a></p>
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

      ?>
      <script>
         alert('Solicitud <?php echo($mov_desc); ?> correctamente.');
         window.close();
      </script>
      <?php
   }else{
      ?>
      <script>
         alert('Ya se atendio esta solicitud');
         window.close();
      </script>
      <?php
   }
?>
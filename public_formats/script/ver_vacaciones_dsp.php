<?php
   date_default_timezone_set("America/Mazatlan");
   $fecha_hora_now = date('Y-m-d H:i:s');
   $fecha_now = date('Y-m-d');
   $y_today = date('Y');
   include ('../../php/conn.php');
   $num_empl = $_POST['num_empl'];
   $vacation_rule = "";

   $query_a = "SELECT hire_date FROM personnel_employee WHERE emp_code = '$num_empl'";
   $exe_a = sqlsrv_query($conn, $query_a);
   $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
   $hire_date = date(substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10));
   $v_hire_one = date("Y-m-d",strtotime($hire_date."+ 1 year"));

   $v_vence_dias = date("Y",strtotime($fecha_hora_now."- 18 month"));

   if ($fecha_now >= $v_hire_one) {
      $color_card = "info";

      $hire_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 10, 5);

      for ($i=$v_vence_dias; $i <= $y_today; $i++) { 
         $v_caduca = date("Y-m-d",strtotime($i."-".$hire_date_2."+ 18 month"));//SE SUMAN 30 MESES POR QUE SON 18 MESES DE LA CADUCIDAD Y 12 MESES POR EL AÑO TRANSCURRIDO DE TRABAJO, EJEMPLO UNA PERSONA ENTRA EL 1/06/22, CUMPLE UN AÑO EL 1/06/23 ENTONCES A PARTIR DE ESTA FECHA PUEDE HACER USO DE VACACIONES Y SE CUENTAN 18 MESES DE CADUCIDAD, EN ESTE EJEMPLO LAS VACACIONES CADUCAN EL 1/12/24
         if ($fecha_now > $v_caduca) {
               continue;
         }
         $query_a = "SELECT y_$i FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$num_empl'";
         $exe_a = sqlsrv_query($cnx, $query_a);
         $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
         $y_dias_v = $fila_a['y_'.$i];
         if ($y_dias_v > 0) {
               $vacation_rule .= "Cuenta con <strong>".$y_dias_v."</strong> dias disponibles del periodo <strong>".$i."</strong><br><small>(Fecha de caducidad <b>".$v_caduca.")</b></small> <br>";
         }
      }
   }else{
      $color_card = "danger";
      $vacation_rule = "Aun no cumple el año laboral, bajo aprobaci&oacute;n del siguiente periodo.";
   }

   /*$query_a = "SELECT * FROM personnel_employee WHERE emp_code = '$num_empl'";
   $exe_a = sqlsrv_query($cnx, $query_a);
   $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
   $vacation_rule = $fila_a['vacation_rule'];
   if ($vacation_rule > 0) {
      $color_card = "info";
   } else {
      $color_card = "danger";
   }*/
   $contar_string = strlen($vacation_rule);
   if ($contar_string < 1) {
      $color_card = "warning";
      $vacation_rule = "Sin informaci&oacute;n de vacaciones.";
   }
   
   function saber_dia($nombredia) {
      $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
      $fecha = $dias[date('N', strtotime($nombredia))];
      return $fecha;
   }
?>
<center>
   <div class="card text-bg-<?php echo($color_card); ?> mb-3no" style="max-width: 24rem;">
      <div class="card-header">
         Vacaciones restantes
      </div>
      <div class="card-body">
         <p class="card-text"><?php echo($vacation_rule); ?></p>
      </div>
      <div class="card-footer text-bg-<?php echo($color_card); ?>">
         <p style="font-size: 9px;"><em>***Esta informacion puede <strong>NO</strong> estar actualizada en tiempo real***</em></p>
      </div>
   </div>
</center>
<hr>
<center><label>Historial de vacaciones</label></center>
<table class="table">
   <thead>
      <tr>
         <th scope="col"># Folio</th>
         <th scope="col">Solicitud hecha el</th>
         <th scope="col">Total de dias</th>
         <th scope="col">Fechas solicitadas</th>
         <th scope="col">Estatus</th>
      </tr>
   </thead>
   <tbody>
      <?php
         $dia_dia = saber_dia($f_recorre);
         $numeracion = 0;
         $query_historial = "SELECT id, CONVERT(varchar, f_solicitud, 34) AS f_solicitud_v, CONVERT(varchar, f_ingreso, 34) AS f_ingreso_v, dias_vac, fecha_array, estatus FROM rh_vacaciones WHERE id_empleado = '$num_empl' ORDER BY f_solicitud DESC, estatus ASC";
         $exe_historial = sqlsrv_query($cnx, $query_historial);
         while ($fila_historial = sqlsrv_fetch_array($exe_historial, SQLSRV_FETCH_ASSOC)) {
               $numeracion++;
               $str_solicitadas = '';
               $id_v = $fila_historial['id'];
               $f_solicitud_v = $fila_historial['f_solicitud_v'];
               $f_ingreso_v = $fila_historial['f_ingreso_v'];
               $dias_vac_v = $fila_historial['dias_vac'];
               $fecha_array_v = substr($fila_historial['fecha_array'], 0, -1);
               $f_array_v = explode("|", $fecha_array_v);
               foreach ($f_array_v as $key => $value) {
                  $str_solicitadas .= saber_dia($value)." ".$value."<br>";
               }
               $estatus_v = $fila_historial['estatus'];
               switch ($estatus_v) {
                  case '0':
                     $sts_text = 'No revisado';
                     break;
                  
                  case '1':
                     $sts_text = 'Solicitud aceptada';
                     break;
                  
                  case '2':
                     $sts_text = 'Solicitud rechazada';
                     break;
               }
               echo '
                  <tr>
                     <th scope="row" class="align-middle">'.$id_v.'</th>
                     <td class="align-middle">'.$f_solicitud_v.'</td>
                     <td class="align-middle">'.$dias_vac_v.'</td>
                     <td class="align-middle">'.$str_solicitadas.'</td>
                     <td class="align-middle">'.$sts_text.'</td>
                  </tr>
               ';
         }
      ?>
   </tbody>
</table>
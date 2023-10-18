<?php
   /*while($post = each($_POST)){
      echo $post[0]." = ".$post[1]."||";
   }*/
   date_default_timezone_set("America/Mazatlan");
   session_start();
   include ('../../php/conn.php');
   $name_a_session = $_SESSION['name_a'];
   $num_empleado_a_session = $_SESSION['num_empleado_a'];
   $fecha_hora_now = date('Y-m-d H:i:s');
   $y_today = date('Y');

   $mov = $_POST['mov'];

   $inp_id_table = $_POST['inp_id_table'];
   $inp_table = $_POST['inp_table'];
   $obs_extra = $_POST['obs_extra'];

   $sql_complemento = '';
   $query_slct = "SELECT id_empleado, dias_vac, fecha_array, rh_status, abstractexception_ptr_id FROM $inp_table WHERE id = '$inp_id_table'";
   $exe_slct = sqlsrv_query($cnx, $query_slct);
   $fila_slct = sqlsrv_fetch_array($exe_slct, SQLSRV_FETCH_ASSOC);
   $id_empleado = $fila_slct['id_empleado'];
   $abstractexception_ptr_id = $fila_slct['abstractexception_ptr_id'];
   $fecha_array = substr($fila_slct['fecha_array'], 0, -1);
   $rh_status = $fila_slct['rh_status'];
   $total_dias_sol = $fila_slct['dias_vac'];

   if ($mov == '1') {
      if (($rh_status == '1') || ($rh_status == '2')) {
         echo 7;
         exit();
      }
      $mov_desc = 'revisada y aceptada';

      //***Descontar los dias de vacaciones***
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
      //**************************************
        
      /*$query_a = "SELECT id FROM personnel_employee WHERE emp_code = '$id_empleado'";
      $exe_a = sqlsrv_query($conn, $query_a);
      $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
      $id_1 = $fila_a['id'];*/
      $inp_arrdates = explode("|", $fecha_array);
      foreach ($inp_arrdates as $key => $value) {
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
      $id_zks = substr($id_zks, 0, -1);

      $sql_complemento = ", abstractexception_ptr_id = '$id_zks'";
        
   }elseif ($mov == '2') {
      if ($rh_status == '2') {
         echo 8;
         exit();
      }
        $mov_desc = 'revisada y rechazada';

      if (!empty($abstractexception_ptr_id)) {
         $query_c = "DELETE FROM [zkbiotime].[dbo].[att_leave] WHERE [abstractexception_ptr_id] IN ($abstractexception_ptr_id)";
         sqlsrv_query($conn, $query_c);
         $query_d = "DELETE FROM [zkbiotime].[dbo].[workflow_abstractexception]  WHERE id IN ($abstractexception_ptr_id)";
         sqlsrv_query($conn, $query_d);
      }

      //***Se busca cuando fue la ultima fecha para regresarle los dias***
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

      $regresa_dias_sql = "UPDATE rh_employee_gen SET y_$i = CASE WHEN y_$i IS NULL THEN $y_dias_v ELSE y_$i + $y_dias_v END WHERE CLAVE = '$id_empleado'";
      sqlsrv_query($cnx, $regresa_dias_sql);
      //******************************************************************
      //exit();
   }

   $txt_hist = "\nSolicitud ".$mov_desc." por ".$name_a_session." con fecha ".$fecha_hora_now;
   $txt_obs = (empty($obs_extra)) ? '' : "\n".$obs_extra." - ".$name_a_session ;

   switch ($inp_table) {
      case 'rh_vacaciones':
         $query_a = "UPDATE $inp_table SET observaciones = CONCAT(observaciones, '$txt_obs'), estatus = '$mov', rh_status = '$mov', historico = CONCAT(historico, '$txt_hist')$sql_complemento WHERE id = '$inp_id_table'";
         break;
   }
   if (sqlsrv_query($cnx, $query_a)) {
      echo 1;
   }else{
      echo 0;
   }
?>
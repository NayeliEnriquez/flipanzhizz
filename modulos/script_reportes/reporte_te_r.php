<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');
   session_start();
   $num_empleado_session = $_SESSION['num_empleado_a'];

   function getFirstDayWeek($week, $year){
      $dt = new DateTime();
      $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
      $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
      return $return;
   }
   //***
   function saber_dia($nombredia) {
      $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
      $fecha = $dias[date('N', strtotime($nombredia))];
      return $fecha;
   }

   $slc_year = $_POST['slc_year'];
   $slc_semana = $_POST['slc_semana'];
   $slc_quincena = $_POST['slc_quincena'];

   $str_emps = '';
   $SQL_a = "SELECT * FROM personnel_employee WHERE personnel_employee.department_id IN (2, 3, 4, 5, 6, 7, 8, 30, 44, 45, 46, 58, 61) AND enable_att = '1' ORDER BY last_name ASC";
   $exe_a = sqlsrv_query($conn, $SQL_a);
   while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
      $emp_code_a = $fila_a['emp_code'];
      $str_emps .= $emp_code_a.', ';
   }
   $str_emps = substr($str_emps, 0, -2);

   $txt_tipo_r = '';
   if (empty($slc_semana)) {
      switch ($slc_quincena) {
         case '1'://***<option value="1">Del 1ro al 15 de Enero</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 1;
               break;
         
         case '2'://***<option value="2">Del 16 al 31 de Enero</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 1;
               break;

         case '3'://***<option value="3">Del 1ro al 15 de Febrero</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 2;
               break;

         case '4'://***<option value="4">Del 16 al 28 de Febrero</option>
               $d_ini = 16;
               $d_fin = 28;
               $v_mes = 2;
               break;

         case '5'://***<option value="5">Del 1ro al 15 de Marzo</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 3;
               break;

         case '6'://***<option value="6">Del 16 al 31 de Marzo</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 3;
               break;

         case '7'://***<option value="7">Del 1ro al 15 de Abril</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 4;
               break;

         case '8'://***<option value="8">Del 16 al 30 de Abril</option>
               $d_ini = 16;
               $d_fin = 30;
               $v_mes = 4;
               break;

         case '9'://***<option value="9">Del 1ro al 15 de Mayo</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 5;
               break;

         case '10'://***<option value="10">Del 16 al 31 de Mayo</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 5;
               break;

         case '11'://***<option value="11">Del 1ro al 15 de Junio</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 6;
               break;

         case '12'://***<option value="12">Del 16 al 30 de Junio</option>
               $d_ini = 16;
               $d_fin = 30;
               $v_mes = 6;
               break;

         case '13'://***<option value="13">Del 1ro al 15 de Julio</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 7;
               break;

         case '14'://***<option value="14">Del 16 al 31 de Julio</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 7;
               break;

         case '15'://***<option value="15">Del 1ro al 15 de Agosto</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 8;
               break;

         case '16'://***<option value="16">Del 16 al 31 de Agosto</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 8;
               break;

         case '17'://***<option value="17">Del 1ro al 15 de Septiembre</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 9;
               break;

         case '18'://***<option value="18">Del 16 al 30 de Septiembre</option>
               $d_ini = 16;
               $d_fin = 30;
               $v_mes = 9;
               break;

         case '19'://***<option value="19">Del 1ro al 15 de Octubre</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 10;
               break;

         case '20'://***<option value="20">Del 16 al 31 de Octubre</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 10;
               break;

         case '21'://***<option value="21">Del 1ro al 15 de Noviembre</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 11;
               break;

         case '22'://***<option value="22">Del 16 al 30 de Noviembre</option>
               $d_ini = 16;
               $d_fin = 30;
               $v_mes = 11;
               break;

         case '23'://***<option value="23">Del 1ro al 15 de Diciembre</option>
               $d_ini = 1;
               $d_fin = 15;
               $v_mes = 12;
               break;

         case '24'://***<option value="24">Del 16 al 31 de Diciembre</option>
               $d_ini = 16;
               $d_fin = 31;
               $v_mes = 12;
               break;
      }

      $v_mes = ($v_mes < 10) ? '0'.$v_mes : $v_mes;

      //echo "d_ini: ".$d_ini." d_fin:".$d_fin;
      $f_start = strtotime($slc_year."-".$v_mes."-".$d_ini);
      $f_end = strtotime($slc_year."-".$v_mes."-".$d_fin);
      $txt_tipo_r = 'QUINCENA';
   }else{
      $semanas = getFirstDayWeek($slc_semana, $slc_year);
      $f_start = strtotime($semanas[start]);
      $f_end = strtotime($semanas[end]);
      $txt_tipo_r = 'SEMANA #'.$slc_semana;
   }

   echo '
      <table style="border-collapse: collapse; width: 100%;">
         <thead>
            <tr>
               <th style="border: 3px solid; text-align: center;" colspan="22"><strong>TIEMPO EXTRA ('.$txt_tipo_r.' DEL '.date("Y-m-d", $f_start).' AL '.date("Y-m-d", $f_end).') Área: Almacen</strong></th>
            </tr>
            <tr>
               <th style="border: 1px solid; text-align: center;">Empleado</th>
   ';

   if (empty($slc_semana)) {
      echo '
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Lun</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Mar</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Mie</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Jue</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Vie</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Sab</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Dom</th>
         <th style="border: 1px solid; text-align: center; font-size: 10px;">Totales</th>
      ';
   }else{
      for ($i=$f_start; $i <= $f_end ; $i+=86400) {
         echo '
            <th style="border: 1px solid; text-align: center; font-size: 10px;">'.substr(saber_dia(date("Y-m-d", $i)), 0, 3).'<br>'.date("Y-m-d", $i).'</th>
         ';
      }
      echo '<th style="border: 1px solid; text-align: center; font-size: 10px;">Totales</th>';
   }

   echo '
            </tr>
         </thead>
   ';

   if (empty($slc_semana)) {
      $meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
      $dias_tmp = array (
         "0" => "Lunes",
         "1" => "Martes",
         "2" => "Miercoles",
         "3" => "Jueves",
         "4" => "Viernes",
         "5" => "Sabado",
         "6" => "Domingo"
      );

      $str_emps = str_replace("'", "", $str_emps);
      $str_emps_arr = explode(",", $str_emps);
      echo '
            <tbody style="font-size: 12px;">';
      
      foreach ($str_emps_arr as $key => $value) {
         $tot_extras = 0;
         $tot_extras_b = 0;
         $txt_textra_a = '';
         $query_shift_a = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '".trim($value)."'";
         $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
         $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
         echo '
               <tr>
                  <td style="border-bottom:1px; border: 1px solid; width: 100px; word-wrap: break-word; font-size: 8px;"> '.utf8_encode($fila_shift_a['emp_code']).' '.utf8_encode($fila_shift_a['last_name']).' '.utf8_encode($fila_shift_a['first_name']).'</td>
         ';

         //***CODIGO PARA USAR MI TABLA TEMPORAL***
         //****************************************
         $query_tmp_1 = "SELECT COUNT(id) AS existencia FROM [dbo].[rh_15_pdf_tmp] WHERE usuario = '$num_empleado_session'";
         $exe_tmp_1 = sqlsrv_query($cnx, $query_tmp_1);
         $fila_tmp_1 = sqlsrv_fetch_array($exe_tmp_1, SQLSRV_FETCH_ASSOC);
         $existencia_tmp = $fila_tmp_1['existencia'];
         if ($existencia_tmp > 0) {
            $query_tmp_2 = "DELETE FROM [dbo].[rh_15_pdf_tmp] WHERE usuario = '$num_empleado_session'";
            sqlsrv_query($cnx, $query_tmp_2);

            $query_tmp_3 = "INSERT INTO [dbo].[rh_15_pdf_tmp] ([usuario]) VALUES ('$num_empleado_session');
               SELECT SCOPE_IDENTITY();";
            
            $exe_tmp_3 = sqlsrv_query($cnx, $query_tmp_3);
            sqlsrv_next_result($exe_tmp_3);
            sqlsrv_fetch($exe_tmp_3);
            $id_inserted_tmp = sqlsrv_get_field($exe_tmp_3, 0);
         } else {
            $query_tmp_3 = "INSERT INTO [dbo].[rh_15_pdf_tmp] ([usuario]) VALUES ('$num_empleado_session');
               SELECT SCOPE_IDENTITY();";
            
            $exe_tmp_3 = sqlsrv_query($cnx, $query_tmp_3);
            sqlsrv_next_result($exe_tmp_3);
            sqlsrv_fetch($exe_tmp_3);
            $id_inserted_tmp = sqlsrv_get_field($exe_tmp_3, 0);
         }

         $int_tmp = 1;
         $int_tots = 0;
         //***CODIGO PARA USAR MI TABLA TEMPORAL***
         //****************************************
         

         for ($i=$f_start; $i <= $f_end ; $i+=86400) {
            $f_recorre = date("Y-m-d", $i);
            $dia_semana = saber_dia($f_recorre);
            $sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '".trim($value)."' AND rh_master_emptime.f_recorre = '$f_recorre'";
            $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
            $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
            if ($fila_date_emp == null) {
               $tot_hrs_e_new_a = 0;
            } else {
               $id_a = $fila_date_emp['id'];
               $emp_code_a = $fila_date_emp['emp_code'];
               $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
               $f_recorre_a = substr($fila_date_emp, 5, 10);
               $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
               $txt_textra_a = $fila_date_emp['txt_textra'];
               $estatus_a = $fila_date_emp['estatus'];
               $historico_a = $fila_date_emp['historico'];
               $historico_arr = explode("|", $historico_a);
               $historico_a = $historico_arr[0];
               $historico_arrr = explode(",", $historico_a);
               $historico_a = $historico_arrr[0];
               $historico_a = substr($historico_a, 16, -21);
               $historico_b = $historico_arrr[1];
            }

            foreach ($dias_tmp as $key_tmp => $value_tmp) {
               if ($value_tmp == $dia_semana) {
                  $dia_semana_short = strtolower(substr($dia_semana, 0, 3));
                  $query_tmp_4 = "UPDATE [dbo].[rh_15_pdf_tmp] SET ".$dia_semana_short."_".$int_tmp." = '".$f_recorre."|".$tot_hrs_e_new_a."|".$txt_textra_a."|".$historico_a."|".$historico_b."' WHERE id = '$id_inserted_tmp'";
                  //echo "<br> -> ".$query_tmp_4;
                  sqlsrv_query($cnx, $query_tmp_4);
                  $int_tots += $tot_hrs_e_new_a;
                  $query_tmp_5 = "UPDATE [dbo].[rh_15_pdf_tmp] SET tot_".$int_tmp." = '$int_tots' WHERE id = '$id_inserted_tmp'";
                  //echo "<br> -> ".$query_tmp_5."<br><br>";
                  sqlsrv_query($cnx, $query_tmp_5);
                  if ($value_tmp == 'Domingo') {
                     $int_tmp++;
                     $int_tots = 0;
                  }
               } else {
                  continue;
               }
               
            }
         }

         //***OBTENER LA INFO DE LA TABLA TMP***
         //*************************************
         $query_tmp_6 = "SELECT [id]
                     ,[usuario]
                     ,[lun_1], [mar_1], [mie_1], [jue_1], [vie_1], [sab_1], [dom_1], [tot_1]
                     ,[lun_2], [mar_2], [mie_2], [jue_2], [vie_2], [sab_2], [dom_2], [tot_2]
                     ,[lun_3], [mar_3], [mie_3], [jue_3], [vie_3], [sab_3], [dom_3], [tot_3]
                     ,[lun_4], [mar_4], [mie_4], [jue_4], [vie_4], [sab_4], [dom_4], [tot_4]
                     ,[tot_all]
               FROM [dbo].[rh_15_pdf_tmp] WHERE id = '$id_inserted_tmp'";
         $exe_tmp_6 = sqlsrv_query($cnx, $query_tmp_6);
         $fila_tmp_6 = sqlsrv_fetch_array($exe_tmp_6, SQLSRV_FETCH_ASSOC);
         $luns_tmp = '';
         {
            $lun_1_tmp = $fila_tmp_6['lun_1'];
            if (empty($lun_1_tmp)) {
               //$lun_1_tmp_arr[0] = '-';
               $lunes1 = '-';
               $lun_txt_1 = '';
            } else {
               $lun_1_tmp_arr = explode("|", $lun_1_tmp);
               $lunes1 = date("j", strtotime($lun_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($lun_1_tmp_arr[0]))].' del '.date("Y", strtotime($lun_1_tmp_arr[0]));
               if ($lun_1_tmp_arr[1] > 0) {
                  $lun_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($lun_1_tmp_arr[3], 25, "<br>", 1)).', '.$lun_1_tmp_arr[4].'</i>';
               } else {
                  $lun_txt_1 = '';
               }
            }
            $lun_2_tmp = $fila_tmp_6['lun_2'];
            if (empty($lun_2_tmp)) {
               //$lun_2_tmp_arr[0] = '-';
               $lunes2 = '-';
               $lun_txt_2 = '';
            } else {
               $lun_2_tmp_arr = explode("|", $lun_2_tmp);
               $lunes2 = date("j", strtotime($lun_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($lun_2_tmp_arr[0]))].' del '.date("Y", strtotime($lun_2_tmp_arr[0]));
               if ($lun_2_tmp_arr[1] > 0) {
                  $lun_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($lun_2_tmp_arr[3], 25, "<br>", 1)).', '.$lun_2_tmp_arr[4].'</i>';
               } else {
                  $lun_txt_2 = '';
               }
            }
            $lun_3_tmp = $fila_tmp_6['lun_3'];
            if (empty($lun_3_tmp)) {
               //$lun_3_tmp_arr[0] = '-';
               $lunes3 = '-';
               $lun_txt_3 = '';
            } else {
               $lun_3_tmp_arr = explode("|", $lun_3_tmp);
               $lunes3 = date("j", strtotime($lun_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($lun_3_tmp_arr[0]))].' del '.date("Y", strtotime($lun_3_tmp_arr[0]));
               if ($lun_3_tmp_arr[1] > 0) {
                  $lun_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($lun_3_tmp_arr[3], 25, "<br>", 1)).', '.$lun_3_tmp_arr[4].'</i>';
               } else {
                  $lun_txt_3 = '';
               }
            }
            $lun_4_tmp = $fila_tmp_6['lun_4'];
            if (empty($lun_4_tmp)) {
               //$lun_4_tmp_arr[0] = '-';
               $lunes4 = '-';
               $lun_txt_4 = '';
            } else {
               $lun_4_tmp_arr = explode("|", $lun_4_tmp);
               $lunes4 = date("j", strtotime($lun_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($lun_4_tmp_arr[0]))].' del '.date("Y", strtotime($lun_4_tmp_arr[0]));
               if ($lun_4_tmp_arr[1] > 0) {
                  $lun_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($lun_4_tmp_arr[3], 25, "<br>", 1)).', '.$lun_4_tmp_arr[4].'</i>';
               } else {
                  $lun_txt_4 = '';
               }
            }
            $luns_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;">
                           <a style="font-size: 11px; color: #000000;">'.$lunes1.'</a><br><strong>'.$lun_1_tmp_arr[1].'</strong>
                           '.$lun_txt_1.'
                        </td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;">
                           <a style="font-size: 11px; color: #000000;">'.$lunes2.'</a><br><strong>'.$lun_2_tmp_arr[1].'</strong>
                           '.$lun_txt_2.'
                        </td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;">
                           <a style="font-size: 11px; color: #000000;">'.$lunes3.'</a><br><strong>'.$lun_3_tmp_arr[1].'</strong>
                           '.$lun_txt_3.'
                        </td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;">
                           <a style="font-size: 11px; color: #000000;">'.$lunes4.'</a><br><strong>'.$lun_4_tmp_arr[1].'</strong>
                           '.$lun_txt_4.'
                        </td>
                     </tr>
                  </table>
               </center>
            ';
         }
         
         {
            $mars_tmp = '';
            $mar_1_tmp = $fila_tmp_6['mar_1'];
            if (empty($mar_1_tmp)) {
               //$mar_1_tmp_arr[0] = '-';
               $martes1 = '-';
               $mar_txt_1 = '';
            } else {
               $mar_1_tmp_arr = explode("|", $mar_1_tmp);
               $martes1 = date("j", strtotime($mar_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($mar_1_tmp_arr[0]))].' del '.date("Y", strtotime($mar_1_tmp_arr[0]));
               if ($mar_1_tmp_arr[1] > 0) {
                  $mar_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mar_1_tmp_arr[3], 25, "<br>", 1)).', '.$mar_1_tmp_arr[4].'</i>';
               } else {
                  $mar_txt_1 = '';
               }
            }
            $mar_2_tmp = $fila_tmp_6['mar_2'];
            if (empty($mar_2_tmp)) {
               //$mar_2_tmp_arr[0] = '-';
               $martes2 = '-';
               $mar_txt_2 = '';
            } else {
               $mar_2_tmp_arr = explode("|", $mar_2_tmp);
               $martes2 = date("j", strtotime($mar_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($mar_2_tmp_arr[0]))].' del '.date("Y", strtotime($mar_2_tmp_arr[0]));
               if ($mar_2_tmp_arr[1] > 0) {
                  $mar_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mar_2_tmp_arr[3], 25, "<br>", 1)).', '.$mar_2_tmp_arr[4].'</i>';
               } else {
                  $mar_txt_2 = '';
               }
            }
            $mar_3_tmp = $fila_tmp_6['mar_3'];
            if (empty($mar_3_tmp)) {
               //$mar_3_tmp_arr[0] = '-';
               $martes3 = '-';
               $mar_txt_3 = '';
            } else {
               $mar_3_tmp_arr = explode("|", $mar_3_tmp);
               $martes3 = date("j", strtotime($mar_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($mar_3_tmp_arr[0]))].' del '.date("Y", strtotime($mar_3_tmp_arr[0]));
               if ($mar_3_tmp_arr[1] > 0) {
                  $mar_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mar_3_tmp_arr[3], 25, "<br>", 1)).', '.$mar_3_tmp_arr[4].'</i>';
               } else {
                  $mar_txt_3 = '';
               }
            }
            $mar_4_tmp = $fila_tmp_6['mar_4'];
            if (empty($mar_4_tmp)) {
               //$mar_4_tmp_arr[0] = '-';
               $martes4 = '-';
               $mar_txt_4 = '';
            } else {
               $mar_4_tmp_arr = explode("|", $mar_4_tmp);
               $martes4 = date("j", strtotime($mar_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($mar_4_tmp_arr[0]))].' del '.date("Y", strtotime($mar_4_tmp_arr[0]));
               if ($mar_4_tmp_arr[1] > 0) {
                  $mar_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mar_4_tmp_arr[3], 25, "<br>", 1)).', '.$mar_4_tmp_arr[4].'</i>';
               } else {
                  $mar_txt_4 = '';
               }
            }
            $mars_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$martes1.'</a><br><strong>'.$mar_1_tmp_arr[1].'</strong>'.$mar_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$martes2.'</a><br><strong>'.$mar_2_tmp_arr[1].'</strong>'.$mar_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                     <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$martes3.'</a><br><strong>'.$mar_3_tmp_arr[1].'</strong>'.$mar_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$martes4.'</a><br><strong>'.$mar_4_tmp_arr[1].'</strong>'.$mar_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         {
            $mies_tmp = '';
            $mie_1_tmp = $fila_tmp_6['mie_1'];
            if (empty($mie_1_tmp)) {
               //$mie_1_tmp_arr[0] = '-';
               $miercoles1 = '-';
               $mie_txt_1 = '';
            } else {
               $mie_1_tmp_arr = explode("|", $mie_1_tmp);
               $miercoles1 = date("j", strtotime($mie_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($mie_1_tmp_arr[0]))].' del '.date("Y", strtotime($mie_1_tmp_arr[0]));
               if ($mie_1_tmp_arr[1] > 0) {
                  $mie_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mie_1_tmp_arr[3], 25, "<br>", 1)).', '.$mie_1_tmp_arr[4].'</i>';
               } else {
                  $mie_txt_1 = '';
               }
            }
            $mie_2_tmp = $fila_tmp_6['mie_2'];
            if (empty($mie_2_tmp)) {
               //$mie_2_tmp_arr[0] = '-';
               $miercoles2 = '-';
               $mie_txt_2 = '';
            } else {
               $mie_2_tmp_arr = explode("|", $mie_2_tmp);
               $miercoles2 = date("j", strtotime($mie_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($mie_2_tmp_arr[0]))].' del '.date("Y", strtotime($mie_2_tmp_arr[0]));
               if ($mie_2_tmp_arr[1] > 0) {
                  $mie_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mie_2_tmp_arr[3], 25, "<br>", 1)).', '.$mie_2_tmp_arr[4].'</i>';
               } else {
                  $mie_txt_2 = '';
               }
            }
            $mie_3_tmp = $fila_tmp_6['mie_3'];
            if (empty($mie_3_tmp)) {
               //$mie_3_tmp_arr[0] = '-';
               $miercoles3 = '-';
               $mie_txt_3 = '';
            } else {
               $mie_3_tmp_arr = explode("|", $mie_3_tmp);
               $miercoles3 = date("j", strtotime($mie_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($mie_3_tmp_arr[0]))].' del '.date("Y", strtotime($mie_3_tmp_arr[0]));
               if ($mie_3_tmp_arr[1] > 0) {
                  $mie_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mie_3_tmp_arr[3], 25, "<br>", 1)).', '.$mie_3_tmp_arr[4].'</i>';
               } else {
                  $mie_txt_3 = '';
               }
            }
            $mie_4_tmp = $fila_tmp_6['mie_4'];
            if (empty($mie_4_tmp)) {
               $mie_4_tmp_arr[0] = '-';
               $miercoles4 = '-';
               $mie_txt_4 = '';
            } else {
               $mie_4_tmp_arr = explode("|", $mie_4_tmp);
               $miercoles4 = date("j", strtotime($mie_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($mie_4_tmp_arr[0]))].' del '.date("Y", strtotime($mie_4_tmp_arr[0]));
               if ($mie_4_tmp_arr[1] > 0) {
                  $mie_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($mie_4_tmp_arr[3], 25, "<br>", 1)).', '.$mie_4_tmp_arr[4].'</i>';
               } else {
                  $mie_txt_4 = '';
               }
            }
            $mies_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$miercoles1.'</a><br><strong>'.$mie_1_tmp_arr[1].'</strong>'.$mie_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$miercoles2.'</a><br><strong>'.$mie_2_tmp_arr[1].'</strong>'.$mie_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$miercoles3.'</a><br><strong>'.$mie_3_tmp_arr[1].'</strong>'.$mie_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$miercoles4.'</a><br><strong>'.$mie_4_tmp_arr[1].'</strong>'.$mie_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         {
            $jues_tmp = '';
            $jue_1_tmp = $fila_tmp_6['jue_1'];
            if (empty($jue_1_tmp)) {
               //$jue_1_tmp_arr[0] = '-';
               $jueves1 = '-';
               $jue_txt_1 = '';
            } else {
               $jue_1_tmp_arr = explode("|", $jue_1_tmp);
               $jueves1 = date("j", strtotime($jue_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($jue_1_tmp_arr[0]))].' del '.date("Y", strtotime($jue_1_tmp_arr[0]));
               if ($jue_1_tmp_arr[1] > 0) {
                  $jue_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($jue_1_tmp_arr[3], 25, "<br>", 1)).', '.$jue_1_tmp_arr[4].'</i>';
               } else {
                  $jue_txt_1 = '';
               }
            }
            $jue_2_tmp = $fila_tmp_6['jue_2'];
            if (empty($jue_2_tmp)) {
               //$jue_2_tmp_arr[0] = '-';
               $jueves2 = '-';
               $jue_txt_2 = '';
            } else {
               $jue_2_tmp_arr = explode("|", $jue_2_tmp);
               $jueves2 = date("j", strtotime($jue_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($jue_2_tmp_arr[0]))].' del '.date("Y", strtotime($jue_2_tmp_arr[0]));
               if ($jue_2_tmp_arr[1] > 0) {
                  $jue_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($jue_2_tmp_arr[3], 25, "<br>", 1)).', '.$jue_2_tmp_arr[4].'</i>';
               } else {
                  $jue_txt_2 = '';
               }
            }
            $jue_3_tmp = $fila_tmp_6['jue_3'];
            if (empty($jue_3_tmp)) {
               //$jue_3_tmp_arr[0] = '-';
               $jueves3 = '-';
               $jue_txt_3 = '';
            } else {
               $jue_3_tmp_arr = explode("|", $jue_3_tmp);
               $jueves3 = date("j", strtotime($jue_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($jue_3_tmp_arr[0]))].' del '.date("Y", strtotime($jue_3_tmp_arr[0]));
               if ($jue_3_tmp_arr[1] > 0) {
                  $jue_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($jue_3_tmp_arr[3], 25, "<br>", 1)).', '.$jue_3_tmp_arr[4].'</i>';
               } else {
                  $jue_txt_3 = '';
               }
            }
            $jue_4_tmp = $fila_tmp_6['jue_4'];
            if (empty($jue_4_tmp)) {
               //$jue_4_tmp_arr[0] = '-';
               $jueves4 = '-';
               $jue_txt_4 = '';
            } else {
               $jue_4_tmp_arr = explode("|", $jue_4_tmp);
               $jueves4 = date("j", strtotime($jue_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($jue_4_tmp_arr[0]))].' del '.date("Y", strtotime($jue_4_tmp_arr[0]));
               if ($jue_4_tmp_arr[1] > 0) {
                  $jue_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($jue_4_tmp_arr[3], 25, "<br>", 1)).', '.$jue_4_tmp_arr[4].'</i>';
               } else {
                  $jue_txt_4 = '';
               }
            }
            $jues_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$jueves1.'</a><br><strong>'.$jue_1_tmp_arr[1].'</strong>'.$jue_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$jueves2.'</a><br><strong>'.$jue_2_tmp_arr[1].'</strong>'.$jue_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$jueves3.'</a><br><strong>'.$jue_3_tmp_arr[1].'</strong>'.$jue_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$jueves4.'</a><br><strong>'.$jue_4_tmp_arr[1].'</strong>'.$jue_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         {
            $vies_tmp = '';
            $vie_1_tmp = $fila_tmp_6['vie_1'];
            if (empty($vie_1_tmp)) {
               //$vie_1_tmp_arr[0] = '-';
               $viernes1 = '-';
               $vie_txt_1 = '';
            } else {
               $vie_1_tmp_arr = explode("|", $vie_1_tmp);
               $viernes1 = date("j", strtotime($vie_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($vie_1_tmp_arr[0]))].' del '.date("Y", strtotime($vie_1_tmp_arr[0]));
               if ($vie_1_tmp_arr[1] > 0) {
                  $vie_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($vie_1_tmp_arr[3], 25, "<br>", 1)).', '.$vie_1_tmp_arr[4].'</i>';
               } else {
                  $vie_txt_1 = '';
               }
            }
            $vie_2_tmp = $fila_tmp_6['vie_2'];
            if (empty($vie_2_tmp)) {
               //$vie_2_tmp_arr[0] = '-';
               $viernes2 = '-';
               $vie_txt_2 = '';
            } else {
               $vie_2_tmp_arr = explode("|", $vie_2_tmp);
               $viernes2 = date("j", strtotime($vie_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($vie_2_tmp_arr[0]))].' del '.date("Y", strtotime($vie_2_tmp_arr[0]));
               if ($vie_2_tmp_arr[1] > 0) {
                  $vie_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($vie_2_tmp_arr[3], 25, "<br>", 1)).', '.$vie_2_tmp_arr[4].'</i>';
               } else {
                  $vie_txt_2 = '';
               }
            }
            $vie_3_tmp = $fila_tmp_6['vie_3'];
            if (empty($vie_3_tmp)) {
               $vie_3_tmp_arr[0] = '-';
               $viernes3 = '-';
               $vie_txt_3 = '';
            } else {
               $vie_3_tmp_arr = explode("|", $vie_3_tmp);
               $viernes3 = date("j", strtotime($vie_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($vie_3_tmp_arr[0]))].' del '.date("Y", strtotime($vie_3_tmp_arr[0]));
               if ($vie_3_tmp_arr[1] > 0) {
                  $vie_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($vie_3_tmp_arr[3], 25, "<br>", 1)).', '.$vie_3_tmp_arr[4].'</i>';
               } else {
                  $vie_txt_3 = '';
               }
            }
            $vie_4_tmp = $fila_tmp_6['vie_4'];
            if (empty($vie_4_tmp)) {
               //$vie_4_tmp_arr[0] = '-';
               $viernes4 = '-';
               $vie_txt_4 = '';
            } else {
               $vie_4_tmp_arr = explode("|", $vie_4_tmp);
               $viernes4 = date("j", strtotime($vie_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($vie_4_tmp_arr[0]))].' del '.date("Y", strtotime($vie_4_tmp_arr[0]));
               if ($vie_4_tmp_arr[1] > 0) {
                  $vie_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($vie_4_tmp_arr[3], 25, "<br>", 1)).', '.$vie_4_tmp_arr[4].'</i>';
               } else {
                  $vie_txt_4 = '';
               }
            }
            $vies_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$viernes1.'</a><br><strong>'.$vie_1_tmp_arr[1].'</strong>'.$vie_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$viernes2.'</a><br><strong>'.$vie_2_tmp_arr[1].'</strong>'.$vie_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$viernes3.'</a><br><strong>'.$vie_3_tmp_arr[1].'</strong>'.$vie_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$viernes4.'</a><br><strong>'.$vie_4_tmp_arr[1].'</strong>'.$vie_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         {
            $sabs_tmp = '';
            $sab_1_tmp = $fila_tmp_6['sab_1'];
            if (empty($sab_1_tmp)) {
               //$sab_1_tmp_arr[0] = '-';
               $sabado1 = '-';
               $sab_txt_1 = '';
            } else {
               $sab_1_tmp_arr = explode("|", $sab_1_tmp);
               $sabado1 = date("j", strtotime($sab_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($sab_1_tmp_arr[0]))].' del '.date("Y", strtotime($sab_1_tmp_arr[0]));
               if ($sab_1_tmp_arr[1] > 0) {
                  $sab_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($sab_1_tmp_arr[3], 25, "<br>", 1)).', '.$sab_1_tmp_arr[4].'</i>';
               } else {
                  $sab_txt_1 = '';
               }
            }
            $sab_2_tmp = $fila_tmp_6['sab_2'];
            if (empty($sab_2_tmp)) {
               //$sab_2_tmp_arr[0] = '-';
               $sabado2 = '-';
               $sab_txt_2 = '';
            } else {
               $sab_2_tmp_arr = explode("|", $sab_2_tmp);
               $sabado2 = date("j", strtotime($sab_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($sab_2_tmp_arr[0]))].' del '.date("Y", strtotime($sab_2_tmp_arr[0]));
               if ($sab_2_tmp_arr[1] > 0) {
                  $sab_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($sab_2_tmp_arr[3], 25, "<br>", 1)).', '.$sab_2_tmp_arr[4].'</i>';
               } else {
                  $sab_txt_2 = '';
               }
            }
            $sab_3_tmp = $fila_tmp_6['sab_3'];
            if (empty($sab_3_tmp)) {
               //$sab_3_tmp_arr[0] = '-';
               $sabado3 = '-';
               $sab_txt_3 = '';
            } else {
               $sab_3_tmp_arr = explode("|", $sab_3_tmp);
               $sabado3 = date("j", strtotime($sab_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($sab_3_tmp_arr[0]))].' del '.date("Y", strtotime($sab_3_tmp_arr[0]));
               if ($sab_3_tmp_arr[1] > 0) {
                  $sab_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($sab_3_tmp_arr[3], 25, "<br>", 1)).', '.$sab_3_tmp_arr[4].'</i>';
               } else {
                  $sab_txt_3 = '';
               }
            }
            $sab_4_tmp = $fila_tmp_6['sab_4'];
            if (empty($sab_4_tmp)) {
               //$sab_4_tmp_arr[0] = '-';
               $sabado4 = '-';
               $sab_txt_4 = '';
            } else {
               $sab_4_tmp_arr = explode("|", $sab_4_tmp);
               $sabado4 = date("j", strtotime($sab_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($sab_4_tmp_arr[0]))].' del '.date("Y", strtotime($sab_4_tmp_arr[0]));
               if ($sab_4_tmp_arr[1] > 0) {
                  $sab_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($sab_4_tmp_arr[3], 25, "<br>", 1)).', '.$sab_4_tmp_arr[4].'</i>';
               } else {
                  $sab_txt_4 = '';
               }
            }
            $sabs_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$sabado1.'</a><br><strong>'.$sab_1_tmp_arr[1].'</strong>'.$sab_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$sabado2.'</a><br><strong>'.$sab_2_tmp_arr[1].'</strong>'.$sab_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$sabado3.'</a><br><strong>'.$sab_3_tmp_arr[1].'</strong>'.$sab_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$sabado4.'</a><br><strong>'.$sab_4_tmp_arr[1].'</strong>'.$sab_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         {
            $doms_tmp = '';
            $dom_1_tmp = $fila_tmp_6['dom_1'];
            if (empty($dom_1_tmp)) {
               //$dom_1_tmp_arr[0] = '-';
               $domingo1 = '-';
               $dom_txt_1 = '';
            } else {
               $dom_1_tmp_arr = explode("|", $dom_1_tmp);
               $domingo1 = date("j", strtotime($dom_1_tmp_arr[0])).' de '.$meses[date("n", strtotime($dom_1_tmp_arr[0]))].' del '.date("Y", strtotime($dom_1_tmp_arr[0]));
               if ($dom_1_tmp_arr[1] > 0) {
                  $dom_txt_1 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($dom_1_tmp_arr[3], 25, "<br>", 1)).', '.$dom_1_tmp_arr[4].'</i>';
               } else {
                  $dom_txt_1 = '';
               }
            }
            $dom_2_tmp = $fila_tmp_6['dom_2'];
            if (empty($dom_2_tmp)) {
               //$dom_2_tmp_arr[0] = '-';
               $domingo2 = '-';
               $dom_txt_2 = '';
            } else {
               $dom_2_tmp_arr = explode("|", $dom_2_tmp);
               $domingo2 = date("j", strtotime($dom_2_tmp_arr[0])).' de '.$meses[date("n", strtotime($dom_2_tmp_arr[0]))].' del '.date("Y", strtotime($dom_2_tmp_arr[0]));
               if ($dom_2_tmp_arr[1] > 0) {
                  $dom_txt_2 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($dom_2_tmp_arr[3], 25, "<br>", 1)).', '.$dom_2_tmp_arr[4].'</i>';
               } else {
                  $dom_txt_2 = '';
               }
            }
            $dom_3_tmp = $fila_tmp_6['dom_3'];
            if (empty($dom_3_tmp)) {
               //$dom_3_tmp_arr[0] = '-';
               $domingo3 = '-';
               $dom_txt_3 = '';
            } else {
               $dom_3_tmp_arr = explode("|", $dom_3_tmp);
               $domingo3 = date("j", strtotime($dom_3_tmp_arr[0])).' de '.$meses[date("n", strtotime($dom_3_tmp_arr[0]))].' del '.date("Y", strtotime($dom_3_tmp_arr[0]));
               if ($dom_3_tmp_arr[1] > 0) {
                  $dom_txt_3 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($dom_3_tmp_arr[3], 25, "<br>", 1)).', '.$dom_3_tmp_arr[4].'</i>';
               } else {
                  $dom_txt_3 = '';
               }
            }
            $dom_4_tmp = $fila_tmp_6['dom_4'];
            if (empty($dom_4_tmp)) {
               //$dom_4_tmp_arr[0] = '-';
               $domingo4 = '-';
               $dom_txt_4 = '';
            } else {
               $dom_4_tmp_arr = explode("|", $dom_4_tmp);
               $domingo4 = date("j", strtotime($dom_4_tmp_arr[0])).' de '.$meses[date("n", strtotime($dom_4_tmp_arr[0]))].' del '.date("Y", strtotime($dom_4_tmp_arr[0]));
               if ($dom_4_tmp_arr[1] > 0) {
                  $dom_txt_4 = '<br><i style="font-size: 9px;">Aprobado por:<br>'.utf8_decode(wordwrap($dom_4_tmp_arr[3], 25, "<br>", 1)).', '.$dom_4_tmp_arr[4].'</i>';
               } else {
                  $dom_txt_4 = '';
               }
            }
            $doms_tmp = '
               <center>
                  <table>
                     <tr style="background-color: #D1FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$domingo1.'</a><br><strong>'.$dom_1_tmp_arr[1].'</strong>'.$dom_txt_1.'</td>
                     </tr>
                     <tr style="background-color: #00FF00; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$domingo2.'</a><br><strong>'.$dom_2_tmp_arr[1].'</strong>'.$dom_txt_2.'</td>
                     </tr>
                     <tr style="background-color: #00F0FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$domingo3.'</a><br><strong>'.$dom_3_tmp_arr[1].'</strong>'.$dom_txt_3.'</td>
                     </tr>
                     <tr style="background-color: #3A00FF; text-align: center;">
                        <td style="height: 50px; word-wrap: break-word;"><a style="font-size: 11px; color: #000000;">'.$domingo4.'</a><br><strong>'.$dom_4_tmp_arr[1].'</strong>'.$dom_txt_4.'</td>
                     </tr>
                  </table>
               </center>
            ';
         }

         $tot_1_tmp = ($fila_tmp_6['tot_1'] < 1) ? 0 : $fila_tmp_6['tot_1'];
         $tot_2_tmp = ($fila_tmp_6['tot_2'] < 1) ? 0 : $fila_tmp_6['tot_2'];
         $tot_3_tmp = ($fila_tmp_6['tot_3'] < 1) ? 0 : $fila_tmp_6['tot_3'];
         $tot_4_tmp = ($fila_tmp_6['tot_4'] < 1) ? 0 : $fila_tmp_6['tot_4'];
         $tots_tmp = '
            <center>
               <table>
                  <tr style="background-color: #D1FF00; text-align: center;">
                     <td style="font-size: 15px; height: 50px;"><strong>'.$tot_1_tmp.'</strong></td>
                  </tr>
                  <tr style="background-color: #00FF00; text-align: center;">
                     <td style="font-size: 15px; height: 50px;"><strong>'.$tot_2_tmp.'</strong></td>
                  </tr>
                  <tr style="background-color: #00F0FF; text-align: center;">
                     <td style="font-size: 15px; height: 50px;"><strong>'.$tot_3_tmp.'</strong></td>
                  </tr>
                  <tr style="background-color: #3A00FF; text-align: center;">
                     <td style="font-size: 15px; height: 50px;"><strong>'.$tot_4_tmp.'</strong></td>
                  </tr>
               </table>
            </center>
         ';

         echo '
            <td>'.$luns_tmp.'</td>
            <td>'.$mars_tmp.'</td>
            <td>'.$mies_tmp.'</td>
            <td>'.$jues_tmp.'</td>
            <td>'.$vies_tmp.'</td>
            <td>'.$sabs_tmp.'</td>
            <td>'.$doms_tmp.'</td>
            <td>'.$tots_tmp.'</td>
         ';
         //***OBTENER LA INFO DE LA TABLA TMP***
         //*************************************

         echo '
            </tr>';
      }
      echo '</tbody>';
   }else{
      $str_emps = str_replace("'", "", $str_emps);
      $str_emps_arr = explode(",", $str_emps);
      $i_candado = 0;
      foreach ($str_emps_arr as $key => $value) {
         $i_candado++;
         $emp_code_emps = trim($value);
         $query_shift_a = "SELECT * FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_emps'";
         $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
         $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
         $tot_extras = 0;
         
         for ($i=$f_start; $i <= $f_end ; $i+=86400) {
               $f_recorre = date("Y-m-d", $i);
               $dia_semana = saber_dia($f_recorre);
               $sql_date_emp = "SELECT * FROM dbo.rh_master_emptime WHERE rh_master_emptime.emp_code = '$emp_code_emps' AND rh_master_emptime.f_recorre = '$f_recorre'";
               $exe_date_emp = sqlsrv_query($cnx, $sql_date_emp);
               $fila_date_emp = sqlsrv_fetch_array($exe_date_emp, SQLSRV_FETCH_ASSOC);
               if ($fila_date_emp == null) {
                  $tot_hrs_e_new_a = 0;
               } else {
                  $id_a = $fila_date_emp['id'];
                  $emp_code_a = $fila_date_emp['emp_code'];
                  $f_recorre_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_date_emp['f_recorre']))));
                  $f_recorre_a = substr($fila_date_emp, 5, 10);
                  $tot_hrs_e_new_a = $fila_date_emp['tot_hrs_e_new'];
                  $txt_textra_a = $fila_date_emp['txt_textra'];
                  $txt_textra_a = wordwrap($txt_textra_a, 18, "<br>", 1);
                  $estatus_a = $fila_date_emp['estatus'];
                  $historico_a = $fila_date_emp['historico'];
                  $historico_arr = explode("|", $historico_a);
                  $historico_a = $historico_arr[0];
                  $historico_arrr = explode(",", $historico_a);
                  $historico_a = $historico_arrr[0];
                  $historico_a = substr($historico_a, 16, -21);
                  $historico_b = $historico_arrr[1];
               }
               switch ($dia_semana) {
                  case 'Lunes':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_lun = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_lun = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
                  
                  case 'Martes':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_mar = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_mar = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
                  
                  case 'Miercoles':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_mie = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_mie = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
      
                  case 'Jueves':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_jue = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_jue = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
      
                  case 'Viernes':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_vie = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_vie = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
      
                  case 'Sabado':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_sab = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_sab = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
      
                  case 'Domingo':
                     if ($tot_hrs_e_new_a > 0) {
                           $t_dom = '
                           <td style="text-align: center; border-bottom:1px;">
                              <strong>'.$tot_hrs_e_new_a.'</strong><br><i style="font-size: 9px;">'.$txt_textra_a.'</i>
                              <br><i style="font-size: 9px;"><span style="background-color: #64CDD8;">Aprobado por:<br>'.utf8_decode(wordwrap($historico_a, 20, "<br>", 1)).', '.$historico_b.'</span></i>
                           </td>';
                           $tot_extras += $tot_hrs_e_new_a;
                     } else {
                           $t_dom = '
                           <td style="text-align: center; border-bottom:1px;">
                              
                           </td>';
                     }
                     break;
               }
         }
         echo '
               <tbody style="font-size: 12px; border: 1px solid;">
                  <tr>
                     <td style="border-bottom:1px; width: 180px; word-wrap: break-word;">'.$emp_code_emps.' - '.utf8_encode($fila_shift_a['last_name']).' '.utf8_encode($fila_shift_a['first_name']).'</td>
         ';
         echo $t_lun.$t_mar.$t_mie.$t_jue.$t_vie.$t_sab.$t_dom.'
                     <td style="text-align: center; border-bottom:1px;"><strong>'.$tot_extras.' horas</strong></td>
                  </tr>
               </tbody>';
      }
   }
   echo '</table>';
?>
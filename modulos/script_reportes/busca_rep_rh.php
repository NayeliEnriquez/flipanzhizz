<table class="table table-dark align-middle" id="table_vacas_rh" style="display: block; white-space: nowrap;">
   <thead>
      <tr style="font-size: 12px;">
         <th scope="col">Folio</th>
         <th scope="col">Num empleado</th>
         <th scope="col">Nombre completo</th>
         <th scope="col">Fecha de contrataci&oacute;n</th>
         <th scope="col">Departamento</th>
         <th scope="col">Posici&oacute;n</th>
         <th scope="col">Fecha de solicitud</th>
         <th scope="col">Dias solicitados</th>
         <th scope="col">Estatus</th>
         <th scope="col">Fechas solicitadas</th>
         <th scope="col">Fecha de reincorporaci&oacute;n</th>
         <th scope="col">Estatus RH</th>
         <th scope="col">BioTime</th>
         <th scope="col">Observaciones</th>
         <th scope="col">Historico</th>
      </tr>
   </thead>
   <tbody>
<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');
   $year_current = date('Y');

   function getFirstDayWeek($week, $year){
      $dt = new DateTime();
      $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
      $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
      return $return;
   }

   function saber_dia($nombredia) {
      $dias = array('','Lun','Mar','Mie','Jue','Vie','Sab','Dom');
      $fecha = $dias[date('N', strtotime($nombredia))];
      return $fecha;
   }

   $inp_num_empl_rh = $_POST['inp_num_empl_rh'];
   $inp_f_busqueda = $_POST['inp_f_busqueda'];
   $slc_semana = $_POST['slc_semana'];
   $slc_quincena = $_POST['slc_quincena'];

   $sql_start = "SELECT * FROM rh_vacaciones WHERE tipo_ausencia LIKE 'Vacaciones'";
   //fecha_array LIKE '%2023-07-11%' AND id_empleado = '5596' AND estatus = '1'

   if (!empty($inp_num_empl_rh)) {
      $sql_comp_a = " AND id_empleado = '$inp_num_empl_rh'";
   }else{
      $sql_comp_a = "";
   }

   if (!empty($inp_f_busqueda)) {
      $sql_comp_b = " AND fecha_array LIKE '%$inp_f_busqueda%'";
   } else {
      $sql_comp_b = "";
   }

   if (!empty($slc_semana)) {
      $semanas = getFirstDayWeek($slc_semana, $year_current);
      $f_start = strtotime($semanas[start]);
      $f_end = strtotime($semanas[end]);
      $f_recorre = '';
      for ($i=$f_start; $i <= $f_end ; $i+=86400) {
         $f_recorre .= date("Y-m-d", $i)."|";
         //$dia_dia = saber_dia($f_recorre);
      }
      $f_recorre_arr = explode("|", $f_recorre);
      $sql_comp_c = " AND fecha_array LIKE '%$f_recorre_arr[0]%' OR fecha_array LIKE '%$f_recorre_arr[1]%' OR fecha_array LIKE '%$f_recorre_arr[2]%' OR fecha_array LIKE '%$f_recorre_arr[3]%' OR fecha_array LIKE '%$f_recorre_arr[4]%' OR fecha_array LIKE '%$f_recorre_arr[5]%' OR fecha_array LIKE '%$f_recorre_arr[6]%'";
   } else {
      $sql_comp_c = "";
   }

   if (!empty($slc_quincena)) {
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
     $f_start = strtotime($year_current."-".$v_mes."-".$d_ini);
     $f_end = strtotime($year_current."-".$v_mes."-".$d_fin);

     $f_recorre = '';
      for ($i=$f_start; $i <= $f_end ; $i+=86400) {
         $f_recorre .= date("Y-m-d", $i)."|";
         //$dia_dia = saber_dia($f_recorre);
      }

      $f_recorre_arr = explode("|", $f_recorre);
      $tam_arr = count($f_recorre_arr);
      $sql_comp_d = " AND fecha_array LIKE '%$f_recorre_arr[0]%'";
      for ($i=1; $i <= $tam_arr; $i++) { 
         if (!empty($f_recorre_arr[$i])) {
            $sql_comp_d .= " OR fecha_array LIKE '%$f_recorre_arr[$i]%'";
         }
      }
      //echo $sql_comp_d = "fecha_array LIKE '%$f_recorre_arr[0]%' OR fecha_array LIKE '%$f_recorre_arr[1]%' OR fecha_array LIKE '%$f_recorre_arr[2]%' OR fecha_array LIKE '%$f_recorre_arr[3]%' OR fecha_array LIKE '%$f_recorre_arr[4]%' OR fecha_array LIKE '%$f_recorre_arr[5]%' OR fecha_array LIKE '%$f_recorre_arr[6]%' OR fecha_array LIKE '%$f_recorre_arr[7]%' OR fecha_array LIKE '%$f_recorre_arr[8]%' OR fecha_array LIKE '%$f_recorre_arr[9]%' OR fecha_array LIKE '%$f_recorre_arr[10]%' OR fecha_array LIKE '%$f_recorre_arr[11]%' OR fecha_array LIKE '%$f_recorre_arr[12]%' OR fecha_array LIKE '%$f_recorre_arr[13]%' OR fecha_array LIKE '%$f_recorre_arr[14]%'";

      $sql_comp_d;
   } else {
      $sql_comp_d = "";
   }

   $sql_full = $sql_start.$sql_comp_a.$sql_comp_b.$sql_comp_c.$sql_comp_d." ORDER BY estatus ASC, f_solicitud DESC";
   $exe_full = sqlsrv_query($cnx, $sql_full);
   while ($fila_full = sqlsrv_fetch_array($exe_full, SQLSRV_FETCH_ASSOC)) {
      $id = $fila_full['id'];
      $id_empleado = $fila_full['id_empleado'];
      $sql_emp_info = "SELECT 
               personnel_employee.id, personnel_employee.first_name, personnel_employee.last_name, 
               personnel_employee.department_id, personnel_employee.position_id, personnel_department.dept_name, 
               personnel_position.position_name, dbo.personnel_employee.hire_date 
            FROM 
               personnel_employee 
            INNER JOIN 
               personnel_department 
            ON 
               personnel_employee.department_id = personnel_department.id 
            INNER JOIN 
               personnel_position 
            ON 
               personnel_employee.position_id = personnel_position.id 
            WHERE 
               personnel_employee.emp_code = '$id_empleado'";
      $exe_emp_info = sqlsrv_query($conn, $sql_emp_info);
      $fila_emp_info = sqlsrv_fetch_array($exe_emp_info, SQLSRV_FETCH_ASSOC);
      $full_name = utf8_encode($fila_emp_info['last_name'])." ".utf8_encode($fila_emp_info['first_name']);
      $dept_name = $fila_emp_info['dept_name'];
      $position_name = $fila_emp_info['position_name'];
      $tipo_ausencia = $fila_full['tipo_ausencia'];
      $f_ini = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['f_ini'])))), 5, 10);//$fila_full['f_ini'];
      $f_fin = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['f_fin'])))), 5, 10);//$fila_full['f_fin'];
      $h_in = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['h_in'])))), 16, 8);//$fila_full['h_in'];
      $h_out = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['h_out'])))), 16, 8);//$fila_full['h_out'];
      $f_ingreso = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['f_ingreso'])))), 5, 10);//$fila_full['f_ingreso'];
      $observaciones = $fila_full['observaciones'];
      $dias_vac = $fila_full['dias_vac'];
      $id_solicitante = $fila_full['id_solicitante'];
      $id_depto = $fila_full['id_depto'];
      $f_solicitud = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['f_solicitud'])))), 5, 10);//$fila_full['f_solicitud'];
      $estatus = $fila_full['estatus'];
      switch ($estatus) {
         case '0':
            $estatus_dsc = "Pendiente de revision por jefe directo";
            break;
         
         case '1':
            $estatus_dsc = "Aceptada por jefe directo";
            break;

         case '2':
            $estatus_dsc = "Rechazada por jefe directo";
            break;
      }
      $historico = $fila_full['historico'];
      $fecha_array = $fila_full['fecha_array'];
      $fecha_array_arr = explode("|", $fecha_array);
      $fecha_array_dsc = '';
      foreach ($fecha_array_arr as $key => $value) {
         if (!empty($value)) {
            $fecha_array_dsc .= saber_dia($value)." ".date("d-m-Y", strtotime($value))."<br>";
         }
      }
      $dia_lun = $fila_full['dia_lun'];
      $dia_mar = $fila_full['dia_mar'];
      $dia_mie = $fila_full['dia_mie'];
      $dia_jue = $fila_full['dia_jue'];
      $dia_vie = $fila_full['dia_vie'];
      $dia_sab = $fila_full['dia_sab'];
      $dia_dom = $fila_full['dia_dom'];
      $rh_status = $fila_full['rh_status'];
      switch ($rh_status) {
         case '0':
            $rh_status_dsc = "No revisado";
            break;
         
         case '1':
            $rh_status_dsc = "Aprobada por RH";
            break;

         case '2':
            $rh_status_dsc = "Rechazada por RH";
            break;

         default:
            $rh_status_dsc = "No revisado";
            break;
      }
      $fecha_regreso = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_full['fecha_regreso'])))), 5, 10);//$fila_full['fecha_regreso'];
      $fecha_regreso_dsc = (empty($fecha_regreso)) ? 'No info' : saber_dia($fecha_regreso).' '.date("d-m-Y", strtotime($fecha_regreso)) ;
      $abstractexception_ptr_id = $fila_full['abstractexception_ptr_id'];
      if (!empty($abstractexception_ptr_id)) {
         $biotime_icon = 'Registrado';
      }else{
         $biotime_icon = 'Sin registro';
      }
      
      echo'
         <tr style="font-size: 11px;">
            <th scope="row">'.$id.'</th>
            <td>'.$id_empleado.'</td>
            <td>'.$full_name.'</td>
            <td>'.$f_ingreso.'</td>
            <td>'.$dept_name.'</td>
            <td>'.$position_name.'</td>
            <td>'.saber_dia($f_solicitud).' '.date("d-m-Y", strtotime($f_solicitud)).'</td>
            <td>'.$dias_vac.'</td>
            <td>'.$estatus_dsc.'</td>
            <td>'.$fecha_array_dsc.'</td>
            <td>'.$fecha_regreso_dsc.'</td>
            <td>'.$rh_status_dsc.'</td>
            <td>'.$biotime_icon.'</td>
            <td>'.wordwrap($observaciones, 35, "<br>", 1).'</td>
            <td>'.wordwrap($historico, 35, "<br>", 1).'</td>
         </tr>
      ';
   }
?>
   </tbody>
   <tfoot>
      <tr style="font-size: 12px;">
         <th scope="col">Folio</th>
         <th scope="col">Num empleado</th>
         <th scope="col">Nombre completo</th>
         <th scope="col">Fecha de contrataci&oacute;n</th>
         <th scope="col">Departamento</th>
         <th scope="col">Posici&oacute;n</th>
         <th scope="col">Fecha de solicitud</th>
         <th scope="col">Dias solicitados</th>
         <th scope="col">Estatus</th>
         <th scope="col">Fechas solicitadas</th>
         <th scope="col">Fecha de reincorporaci&oacute;n</th>
         <th scope="col">Estatus RH</th>
         <th scope="col">BioTime</th>
         <th scope="col">Observaciones</th>
         <th scope="col">Historico</th>
      </tr>
   </tfoot>
</table>

<script>
   $(document).ready(function() {
      var r = 0;
      $('#table_vacas_rh').DataTable({
         "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
         },
         //scrollY:        "400px",
         scrollX:        true,
         scrollCollapse: true,
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excelHtml5',
            title: 'reporte_TE',
            text:'Descargar Excel'
         }]
      });
   });
</script>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Loading...</title>
   </head>
   <body>        
   </body>
</html>
<?php
   include ('../php/conn.php');

   date_default_timezone_set("America/Mazatlan");
   $fecha_hora_now = date('Y-m-d H:i:s');
   $fecha_now = date('Y-m-d');
   $year_now = date('Y');
   $year_upd = date('Y');
   $month_now = date('m');
   $day_now = date('d');
   /*$month_now = '10';
   $day_now = '16';*/

   $sql_aniv = "SELECT * FROM personnel_employee WHERE MONTH(hire_date) = '$month_now' AND DAY(hire_date) = '$day_now' AND enable_att = '1'";
   $exe_aniv = sqlsrv_query($conn, $sql_aniv);
   while ($fila_aniv = sqlsrv_fetch_array($exe_aniv, SQLSRV_FETCH_ASSOC)) {
      $emp_code_aniv = $fila_aniv['emp_code'];
      $hire_date_aniv = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_aniv['hire_date'])))), 5, 10);

      $diff_year = $fecha_now - $hire_date_aniv;
      if ($diff_year > 35) {
         $sql_update_v = "UPDATE rh_employee_gen SET y_$year_upd = CASE WHEN y_$year_upd IS NULL THEN 37 ELSE y_$year_upd + 37 END WHERE CLAVE = '$emp_code_aniv'";
      } elseif ($diff_year > 0 && $diff_year <= 35) {
         $sql_dias_v = "SELECT cant_dias FROM cat_dias_vacaciones WHERE int_year = '$diff_year'";
         $exe_dias_v = sqlsrv_query($cnx, $sql_dias_v);
         $fila_dias_v = sqlsrv_fetch_array($exe_dias_v, SQLSRV_FETCH_ASSOC);
         $cant_dias_v = $fila_dias_v['cant_dias']+2;

         $sql_update_v = "UPDATE rh_employee_gen SET y_$year_upd = CASE WHEN y_$year_upd IS NULL THEN $cant_dias_v ELSE y_$year_upd + $cant_dias_v END WHERE CLAVE = '$emp_code_aniv' AND NOMINA NOT LIKE 'SEMANA TLALPAN'";
      } else {
         continue;
      }

      /*if ($diff_year == 1) {
         $sql_dias_v = "SELECT cant_dias FROM cat_dias_vacaciones WHERE int_year = '$diff_year'";
         $exe_dias_v = sqlsrv_query($cnx, $sql_dias_v);
         $fila_dias_v = sqlsrv_fetch_array($exe_dias_v, SQLSRV_FETCH_ASSOC);
         $cant_dias_v = $fila_dias_v['cant_dias']+2;

         echo "<br>".$sql_update_v = "UPDATE rh_employee_gen SET y_$year_upd = CASE WHEN y_$year_upd IS NULL THEN $cant_dias_v ELSE y_$year_upd + $cant_dias_v END WHERE CLAVE = '$emp_code_aniv'";
      } else {
         continue;
      }*/
      

      echo "<br>".$emp_code_aniv." - ".$hire_date_aniv." - DIFF ".$diff_year." - SQL ".$sql_update_v;
      //sqlsrv_query($cnx, $sql_update_v);
      echo "<br>*********************************************";
   }
?>
<script>
   //window.open('','_self','rh_vacaciones_emps.php'); window.close();
</script>
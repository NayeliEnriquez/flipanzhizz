<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');

   $num_emp_super = $_POST['num_emp_super'];

   $sql_A = "SELECT * FROM [rh_novag_system].[dbo].[rh_supervisores] WHERE num_emp = '$num_emp_super'";
   $exe_A = sqlsrv_query($cnx, $sql_A);
   $fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC);
   
   $permiso_te_A = $fila_A['permiso_te'];

   if ($permiso_te_A == '1') {
      $upd_comp = 0;//***Desactivado
   } else {
      $upd_comp = 1;//***Activado
   }

   $query_upd = "UPDATE [rh_novag_system].[dbo].[rh_supervisores] SET permiso_te = '$upd_comp' WHERE num_emp = '$num_emp_super'";
   sqlsrv_query($cnx, $query_upd);

   echo $upd_comp;
?>
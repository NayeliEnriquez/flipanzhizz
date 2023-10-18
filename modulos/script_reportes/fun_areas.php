<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');

   $mov = $_POST['mov'];

   switch ($mov) {
      case '1':
         $inp_id_bd_nn = $_POST['inp_id_bd_nn'];
         $slc_new_areas_nn_list = $_POST['slc_new_areas_nn_list'];

         $query_a = "UPDATE [dbo].[rh_area_prod] SET [area_prod] = '$slc_new_areas_nn_list' WHERE [id] = '$inp_id_bd_nn'";
         sqlsrv_query($cnx, $query_a);
         break;
      
      case '2':
         $id_bdarea = $_POST['id_bdarea'];
         
         $query_a = "DELETE FROM [dbo].[rh_area_prod] WHERE [id] = '$id_bdarea'";
         sqlsrv_query($cnx, $query_a);
         break;

      case '3':
         $id_bdarea = $_POST['id_bdarea'];

         $query_a = "SELECT [id], [area_prod], [num_emp] FROM [dbo].[rh_area_prod] WHERE id = '$id_bdarea'";
         $exe_a = sqlsrv_query($cnx, $query_a);
         $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
         $num_emp = $fila_a['num_emp'];
         $area_prod = $fila_a['area_prod'];

         $query_b = "SELECT [first_name], [last_name] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$num_emp'";
         $exe_b = sqlsrv_query($conn, $query_b);
         $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
         $first_name_zk = utf8_encode($fila_b['first_name']);
         $last_name_zk = utf8_encode($fila_b['last_name']);

         $query_c = "SELECT DISTINCT([area_prod]) FROM [dbo].[rh_area_prod]";
         $exe_c = sqlsrv_query($cnx, $query_c);
         $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);

         echo '
            <div class="row">
               <div class="col-md-4 col-sm-12">
                  <label for="inp_nombre_xxx" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="inp_nombre_xxx" value="'.$first_name_zk.'" readonly>
               </div>
               <div class="col-md-4 col-sm-12">
                  <label for="inp_apell_xxx" class="form-label">Apellido</label>
                  <input type="text" class="form-control" id="inp_apell_xxx" value="'.$last_name_zk.'" readonly>
               </div>
               <div class="col-md-4 col-sm-12">
                  <label for="inp_area_xxx" class="form-label">Area actual</label>
                  <input type="text" class="form-control" id="inp_area_xxx" value="'.$area_prod.'" readonly>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4 col-sm-12">
                  <label class="form-label" for="slc_new_areas_nn_list">&Aacute;reas</label>
                  <select class="form-select" id="slc_new_areas_nn_list">
                     <option value="Tabletas" selected>Tabletas</option>
                     <option value="Fabricacion">Fabricacion</option>
                     <option value="Acondicionado">Acondicionado</option>
                     <option value="Liquidos">Liquidos</option>
                  </select>
               </div>
               <input type="hidden" id="inp_id_bd_nn" value="'.$id_bdarea.'">
            </div>
         ';
         break;

      case '4':
         $inp_num_empl_sup = $_POST['inp_num_empl_sup'];
         $slc_areas_pal_new = $_POST['slc_areas_pal_new'];
         $query_a = "INSERT INTO [dbo].[rh_area_prod] ([area_prod], [num_emp]) VALUES ('$slc_areas_pal_new', '$inp_num_empl_sup')";
         sqlsrv_query($cnx, $query_a);
         break;
   }
?>
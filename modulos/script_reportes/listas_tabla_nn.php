<br>
<table class="table table-striped" id="tb_listass_nn">
   <thead>
      <tr>
         <th scope="col">Num Emp</th>
         <th scope="col">Nombre</th>
         <th scope="col">&Aacute;rea</th>
         <th scope="col">Reubicar</th>
         <th scope="col">Eliminar</th>
      </tr>
   </thead>
   <tbody>
<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');

   $slc_area = $_POST['slc_area'];

   $query_a = "SELECT [id], [area_prod], [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_area'";
   $exe_a = sqlsrv_query($cnx, $query_a);
   while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
      $id_bdarea = $fila_a['id'];
      $num_emp = $fila_a['num_emp'];
      $query_b = "SELECT [first_name], [last_name] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$num_emp'";
      $exe_b = sqlsrv_query($conn, $query_b);
      $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
      $first_name_zk = utf8_encode($fila_b['first_name']);
      $last_name_zk = utf8_encode($fila_b['last_name']);
      echo '
         <tr>
            <td>'.$num_emp.'</td>
            <td>'.$last_name_zk.' '.$first_name_zk.'</td>
            <td>'.$slc_area.'</td>
            <td><button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_reubica_nn" onclick="fun_areas(3, '.$id_bdarea.')">Reubicar</button></td>
            <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="fun_areas(2, '.$id_bdarea.')">Eliminar</button></td>
         </tr>
      ';
   }
?>
   </tbody>
   <tfoot>
      <tr>
         <th scope="col">Num Emp</th>
         <th scope="col">Nombre</th>
         <th scope="col">&Aacute;rea</th>
         <th scope="col">Reubicar</th>
         <th scope="col">Eliminar</th>
      </tr>
   </tfoot>
</table>
<script>
   $(document).ready(function() {
      $('#tb_listass_nn').DataTable({
         "language": {
         "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
         }
      });
   });
</script>
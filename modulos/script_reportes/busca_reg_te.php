<br>
<table class="table table-striped" id="tb_te_nn">
   <thead>
      <tr>
         <th scope="col">Num Emp</th>
         <th scope="col">Nombre</th>
         <th scope="col">Horas extra</th>
         <th scope="col">Fecha de tiempo extra</th>
         <th scope="col">Parte</th>
         <th scope="col">Lote</th>
         <th scope="col">Descripci&oacute;n</th>
         <th scope="col">Fecha de registro</th>
      </tr>
   </thead>
   <tbody>
<?php
   date_default_timezone_set("America/Mazatlan");
   include ('../../php/conn.php');

   $slc_areas_nn = $_POST['slc_areas_nn'];
   $inp_fecha_nn = $_POST['inp_fecha_nn'];

   $query_a = "SELECT [id], [area_prod], [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_areas_nn'";
   $exe_a = sqlsrv_query($cnx, $query_a);
   while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
      $num_emp = $fila_a['num_emp'];
      $sql_te_1 = "SELECT * FROM rh_te_hrs WHERE num_emp = '$num_emp' AND fecha_te = '$inp_fecha_nn'";
      $exe_te_1 = sqlsrv_query($cnx, $sql_te_1);
      while ($fila_te_1 = sqlsrv_fetch_array($exe_te_1, SQLSRV_FETCH_ASSOC)) {
         $sql_te_2 = "SELECT [first_name], [last_name] FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$num_emp'";
         $exe_te_2 = sqlsrv_query($conn, $sql_te_2);
         $fila_te_2 = sqlsrv_fetch_array($exe_te_2, SQLSRV_FETCH_ASSOC);
         $first_name_2 = utf8_encode($fila_te_2['first_name']);
         $last_name_2 = utf8_encode($fila_te_2['last_name']);

         $num_orden_2 = $fila_te_1['num_orden'];
         $parte_2 = $fila_te_1['parte'];
         $lote_2 = $fila_te_1['lote'];
         $desc_parte_2 = $fila_te_1['desc_parte'];
         $fecha_te_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['fecha_te'])))), 5, 10);
         $tot_hrs_2 = $fila_te_1['tot_hrs'];
         $insert_date_2 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_te_1['insert_date'])))), 5, 16)." hrs";

         echo '
         <tr>
               <th scope="row">'.$num_emp.'</th>
               <td>'.$last_name_2.' '.$first_name_2.'</td>
               <td><center>'.$tot_hrs_2.'</center></td>
               <td><center>'.$fecha_te_2.'</center></td>
               <td>'.$parte_2.'</td>
               <td>'.$lote_2.'</td>
               <td>'.$desc_parte_2.'</td>
               <td>'.$insert_date_2.'</td>
         </tr>
         ';
      }
   }
?>
   </tbody>
   <tfoot>
      <tr>
         <th scope="col">Num Emp</th>
         <th scope="col">Nombre</th>
         <th scope="col">Horas extra</th>
         <th scope="col">Fecha de tiempo extra</th>
         <th scope="col">Parte</th>
         <th scope="col">Lote</th>
         <th scope="col">Descripci&oacute;n</th>
         <th scope="col">Fecha de registro</th>
      </tr>
   </tfoot>
</table>

<script>
   $(document).ready(function() {
      var r = 0;
      $('#tb_te_nn').DataTable({
         "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
         },
         initComplete: function () {
            this.api().columns().every(function () {
                  r++;
                  var column = this;
                  var select = $('<select class="form-select form-select-sm"><option value=""></option></select>').appendTo($(column.footer()).empty()).on('change', function () {
                     var val = $.fn.dataTable.util.escapeRegex($(this).val());
                     column.search(val ? '^' + val + '$' : '', true, false).draw();
                  });

                  column.data().unique().sort().each(function (d, j) {
                     select.append('<option value="' + d + '">' + d + '</option>');
                  });
            });
         },
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excelHtml5',
            title: 'reporte_TE',
            text:'Descargar Excel'
         }]
      });
   });
</script>
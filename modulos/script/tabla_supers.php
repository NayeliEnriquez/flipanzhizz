<div class="row">
    <div class="col-md-9">
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_nuevo_super">
            Nuevo supervisor
        </button>
    </div>
</div>
<br>
<table class="table table-dark table-hover" id="tb_superts">
   <thead>
      <tr>
         <th scope="col">#</th>
         <th scope="col">Departamento</th>
         <th scope="col"># Empleado</th>
         <th scope="col">Nombre completo</th>
         <th scope="col">Posici&oacute;n</th>
         <th scope="col">Fecha de contrataci&oacute;n</th>
         <th scope="col">Tipo de nomina</th>
         <th scope="col">Agregar registros TE</th>
      </tr>
   </thead>
   <tbody>
      <?php
         include ('../../php/conn.php');
         $sql_A = "SELECT * FROM rh_supervisores";
         $exe_A = sqlsrv_query($cnx, $sql_A);
         while ($fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC)) {
            $id_super = $fila_A['id'];
            $num_emp_super = $fila_A['num_emp'];
            $permiso_te = $fila_A['permiso_te'];
            if ($permiso_te == '1') {
               $swich_offon = "checked";
               $swich_desc = "Activado";
            } else {
               $swich_offon = "";
               $swich_desc = "Desactivado";
            }
            
            //$full_name_super = $fila_A['full_name'];
            $query_a = "SELECT
                  pe.first_name, pe.last_name, pe.emp_code,
                  pe.department_id, pe.position_id, pe.hire_date,
                  pd.dept_name, ps.position_name, pd.emp_code_charge, pd.parent_dept_id
               FROM
                  dbo.personnel_employee pe
               INNER JOIN
                  dbo.personnel_department pd
               ON
                  pe.department_id = pd.id
               INNER JOIN
                  dbo.personnel_position ps
               ON
                  pe.position_id = ps.id
               WHERE
                  pe.emp_code = '$num_emp_super'";
            $exe_a = sqlsrv_query($cnx, $query_a);
            $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
            $first_name_a = utf8_encode($fila_a['first_name']);
            $last_name_a = utf8_encode($fila_a['last_name']);
            $emp_code_a = $fila_a['emp_code'];
            $hire_date_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['hire_date'])))), 5, 10);
            $dept_name_a = $fila_a['dept_name'];
            $position_name_a = $fila_a['position_name'];

            $query_gen = "SELECT NOMINA AS pago, PUESTO AS puesto_novag FROM dbo.rh_employee_gen WHERE CLAVE = '$emp_code_a'";
            $exe_gen = sqlsrv_query($cnx, $query_gen);
            $fila_gen = sqlsrv_fetch_array($exe_gen, SQLSRV_FETCH_ASSOC);
            if ($fila_gen == null) {
               $pago_a = 'No info';
               $puesto_novag_a = 'No info';
               $full_name = $last_name_a." ".$first_name_a;
               $sql_inserta = "INSERT INTO [dbo].[rh_employee_gen] ([CLAVE], [NOMBRE COMPLETO], [PUESTO], [DEPARTAMENTO]) VALUES ('$emp_code_a', '$full_name', '$position_name_a', '$dept_name_a')";
               sqlsrv_query($cnx, $sql_inserta);
            } else {
               $pago_a = $fila_gen['pago'];
               if ((empty($pago_a)) || ($pago_a == null)) {
                  $pago_a = 'No info';
               }
               $puesto_novag_a = $fila_gen['puesto_novag'];
               if ((empty($puesto_novag_a)) || ($puesto_novag_a == null)) {
                  $puesto_novag_a = 'No info';
               }
            }

            $emp_code_charge_a = $fila_a['emp_code_charge'];
            $parent_dept_id_a = $fila_a['parent_dept_id'];
            
            $contador++;
            echo '
               <tr>
                  <th scope="row">'.$contador.'</th>
                  <td><center>'.$dept_name_a.'</center></td>
                  <td><center>'.$emp_code_a.'</center></td>
                  <td><center>'.$last_name_a.' '.$first_name_a.'</center></td>
                  <td><center>'.$position_name_a.'</center></td>
                  <td><center>'.$hire_date_a.'</center></td>
                  <td><center>'.$pago_a.'</center></td>
                  <td>
                     <center>
                        <div class="form-check form-switch">
                           <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck'.$contador.'" '.$swich_offon.' onchange="cambiar_estatus('.$num_emp_super.')">
                           <!--<label class="form-check-label" for="flexSwitchCheck'.$contador.'">'.$swich_desc.'</label>-->
                        </div>
                     </center>
                  </td>
                  <!--<td>
                        <center>
                           <button type="button" class="btn btn-sm btn-outline-info" title="Cambiar de jefe directo" data-bs-toggle="modal" data-bs-target="#mdl_change_boss" onclick="ver_boss('.$emp_code_a.')">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                           </button>
                        </center>
                  </td>-->
               </tr>
            ';
         }
      ?>
   </tbody>
   <tfoot>
      <tr>
         <th scope="col">#</th>
         <th scope="col">Departamento</th>
         <th scope="col"># Empleado</th>
         <th scope="col">Nombre completo</th>
         <th scope="col">Posici&oacute;n</th>
         <th scope="col">Fecha de contrataci&oacute;n</th>
         <th scope="col">Tipo de nomina</th>
         <th scope="col">Agregar registros de TE</th>
      </tr>
   </tfoot>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_superts').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
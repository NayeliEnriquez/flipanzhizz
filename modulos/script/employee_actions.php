<?php
   include ('../../php/conn.php');
   require_once '../../html2pdf_v4.03/html2pdf.class.php';
   date_default_timezone_set("America/Mazatlan");
   session_start();
   $fecha_hora_now = date('Y-m-d H:i:s');
   $fecha_ym_now = date('Y-m');
   $date_full = date('Y-m-d');
   $name_a_session = $_SESSION['name_a'];
   $num_empleado_a_session = $_SESSION['num_empleado_a'];
   $txt_hist = "-Cambio realizado por <br><strong>".$name_a_session."</strong><br>con fecha <strong>".$fecha_hora_now."</strong>-";

    $mov = $_POST['mov'];
    $emp_code = trim($_POST['emp_code']);

   function saber_dia($nombredia) {
      $dias = array('', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
      $fecha = $dias[date('N', strtotime($nombredia))];
      return $fecha;
   }
   $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
   $destino = "file/";

    /*$sql_hr_1 = "SELECT id FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code'";
    $exe_hr_1 = sqlsrv_query($conn, $sql_hr_1);
    $fila_hr_1 = sqlsrv_fetch_array($exe_hr_1, SQLSRV_FETCH_ASSOC);
    $id_bd_1 = $fila_hr_1['id'];*/
   $sql_hr_1 = "SELECT
         personnel_employee.id,
         personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
         personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name,
         dbo.personnel_employee.hire_date
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
         personnel_employee.emp_code = '$emp_code'";
   $exe_hr_1 = sqlsrv_query($conn, $sql_hr_1);
   $fila_hr_1 = sqlsrv_fetch_array($exe_hr_1, SQLSRV_FETCH_ASSOC);
   $id_bd_1 = $fila_hr_1['id'];
   $first_name_1 = $fila_hr_1['first_name'];
   $last_name_1 = $fila_hr_1['last_name'];
   $department_id_1 = $fila_hr_1['department_id'];
   $dept_name_1 = $fila_hr_1['dept_name'];
   $position_id_1 = $fila_hr_1['position_id'];
   $position_name_1 = $fila_hr_1['position_name'];
   $hire_date_1 = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_1['hire_date'])))), 5, 10);

    switch ($mov) {
        case '1':
            if ($id_bd_1 != '') {
               $sql_hr_2 = "SELECT shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_1'";
               $exe_hr_2 = sqlsrv_query($conn, $sql_hr_2);
               $fila_hr_2 = sqlsrv_fetch_array($exe_hr_2, SQLSRV_FETCH_ASSOC);
               $shift_id = $fila_hr_2['shift_id'];
               if ($shift_id != '') {
                  $sql_hr_3 = "SELECT
                           CAST(att_s.in_time AS time)[hora_entrada], 
                           att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                           CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                     FROM
                           [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                     INNER JOIN
                           [zkbiotime].[dbo].[att_timeinterval] AS att_t
                     ON
                           att_s.time_interval_id = att_t.id
                     WHERE
                           att_s.shift_id = '$shift_id'
                     ORDER BY
                           att_s.day_index ASC
                  ";
                  $exe_hr_3 = sqlsrv_query($conn, $sql_hr_3);
                  while ($fila_hr_3 = sqlsrv_fetch_array($exe_hr_3, SQLSRV_FETCH_ASSOC)) {
                     $h_in = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_entrada']))));
                     $h_in = substr($h_in, 16, 5);
                     $h_out = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_salida']))));
                     $h_out = substr($h_out, 16, 5);
                     $day = $fila_hr_3['day_index'];
                     $alias = $fila_hr_3['alias'];
                     $v_desc = strpos($alias, "escanso");
                     switch ($day) {
                           case '0':
                              if ($v_desc !== false) {
                                 $v_dom = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Domingo</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_dom = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Domingo</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
                           
                           case '1':
                              if ($v_desc !== false) {
                                 $v_lun = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Lunes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_lun = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Lunes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              
                              break;
      
                           case '2':
                              if ($v_desc !== false) {
                                 $v_mar = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Martes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_mar = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Martes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '3':
                              if ($v_desc !== false) {
                                 $v_mie = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Miercoles</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_mie = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Miercoles</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '4':
                              if ($v_desc !== false) {
                                 $v_jue = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Jueves</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_jue = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Jueves</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '5':
                              if ($v_desc !== false) {
                                 $v_vie = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Viernes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_vie = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Viernes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '6':
                              if ($v_desc !== false) {
                                 $v_sab = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Sabado</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_sab = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Sabado</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
                     }
                  }
                  echo '
                     <div class="row">
                           <div class="col-md-1">
                           </div>
                           <div class="col-md-2">
                              '.$v_lun.'
                           </div>
                           <div class="col-md-2">
                              '.$v_mar.'
                           </div>
                           <div class="col-md-2">
                              '.$v_mie.'
                           </div>
                           <div class="col-md-2">
                              '.$v_jue.'
                           </div>
                           <div class="col-md-2">
                              '.$v_vie.'
                           </div>
                           <div class="col-md-1">
                           </div>
                     </div>
                     <div class="row">
                           <div class="col-md-4">
                           </div>
                           <div class="col-md-2">
                              '.$v_sab.'
                           </div>
                           <div class="col-md-2">
                              '.$v_dom.'
                           </div>
                           <div class="col-md-4">
                           </div>
                     </div>
                     <hr>
                     <div class="row">
                           <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                              <button class="btn btn-outline-info me-md-2 btn-sm" type="button" onclick="employee_actions(`'.$emp_code.'`, 2)">Cambiar horario</button>
                           </div>
                     </div>
                  ';
               }else{
                  $sql_hr_3 = "INSERT INTO [dbo].[att_attschedule] ([start_date], [end_date], [employee_id], [shift_id]) VALUES ('$fecha_ym_now-01 00:00:00.000', '$fecha_ym_now-30 00:00:00.000', '$id_bd_1', '3')";
                  sqlsrv_query($conn, $sql_hr_3);

                  $sql_hr_3 = "SELECT
                           CAST(att_s.in_time AS time)[hora_entrada], 
                           att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                           CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                     FROM
                           [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                     INNER JOIN
                           [zkbiotime].[dbo].[att_timeinterval] AS att_t
                     ON
                           att_s.time_interval_id = att_t.id
                     WHERE
                           att_s.shift_id = '3'
                     ORDER BY
                           att_s.day_index ASC
                  ";
                  $exe_hr_3 = sqlsrv_query($conn, $sql_hr_3);
                  while ($fila_hr_3 = sqlsrv_fetch_array($exe_hr_3, SQLSRV_FETCH_ASSOC)) {
                     $h_in = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_entrada']))));
                     $h_in = substr($h_in, 16, 5);
                     $h_out = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_salida']))));
                     $h_out = substr($h_out, 16, 5);
                     $day = $fila_hr_3['day_index'];
                     $alias = $fila_hr_3['alias'];
                     $v_desc = strpos($alias, "escanso");
                     switch ($day) {
                           case '0':
                              if ($v_desc !== false) {
                                 $v_dom = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Domingo</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_dom = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Domingo</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
                           
                           case '1':
                              if ($v_desc !== false) {
                                 $v_lun = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Lunes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_lun = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Lunes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              
                              break;
      
                           case '2':
                              if ($v_desc !== false) {
                                 $v_mar = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Martes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_mar = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Martes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '3':
                              if ($v_desc !== false) {
                                 $v_mie = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Miercoles</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_mie = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Miercoles</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '4':
                              if ($v_desc !== false) {
                                 $v_jue = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Jueves</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_jue = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Jueves</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '5':
                              if ($v_desc !== false) {
                                 $v_vie = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Viernes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_vie = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Viernes</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
      
                           case '6':
                              if ($v_desc !== false) {
                                 $v_sab = ' 
                                       <div class="card border-success mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Sabado</div>
                                          <div class="card-body">
                                             <h5 class="card-title">Dia de descanso</h5>
                                          </div>
                                       </div>
                                 ';
                              } else {
                                 $v_sab = ' 
                                       <div class="card border-info mb-3" style="max-width: 18rem;">
                                          <div class="card-header">Sabado</div>
                                          <div class="card-body">
                                             <h5 class="card-title">'.$alias.'</h5>
                                             <p class="card-text"><b>Hora de entrada: </b>'.$h_in.'</p>
                                             <p class="card-text"><b>Hora de salida: </b>'.$h_out.'</p>
                                          </div>
                                       </div>
                                 ';
                              }
                              break;
                     }
                  }
                  echo '
                     <div class="row">
                           <div class="col-md-1"></div>
                           <div class="col-md-2">
                              '.$v_lun.'
                           </div>
                           <div class="col-md-2">
                              '.$v_mar.'
                           </div>
                           <div class="col-md-2">
                              '.$v_mie.'
                           </div>
                           <div class="col-md-2">
                              '.$v_jue.'
                           </div>
                           <div class="col-md-2">
                              '.$v_vie.'
                           </div>
                           <div class="col-md-1"></div>
                     </div>
                     <div class="row">
                           <div class="col-md-4"></div>
                           <div class="col-md-2">
                              '.$v_sab.'
                           </div>
                           <div class="col-md-2">
                              '.$v_dom.'
                           </div>
                           <div class="col-md-4"></div>
                     </div>
                     <hr>
                     <div class="row">
                           <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                              <button class="btn btn-outline-info me-md-2 btn-sm" type="button" onclick="employee_actions(`'.$emp_code.'`, 2)">Cambiar horario</button>
                           </div>
                     </div>
                  ';
                  /*echo '
                  <div class="card border-warning">
                     <div class="card-body">
                           <center><h2>Sin horario asignado.</h2></center>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                           <button class="btn btn-outline-success me-md-2 btn-sm" type="button" onclick="employee_actions(`'.$emp_code.'`, 2)">Asignar horario</button>
                     </div>
                  </div>';*/
               }
            }else{
                echo '
                    <div class="card border-danger">
                        <div class="card-body">
                            <center><h2>Favor de intentar mas tarde.</h2></center>
                        </div>
                    </div>';
            }
            break;
        
        case '2':
            echo '
               <div class="row">
                  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                     <div class="col-md-3">
                        <label for="inp_date_hr" class="form-label" style="background-color: khaki; font: small-caption;">Si desea actualizar el horario por un solo dia, seleccione la fecha</label>
                     </div>
                     <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm" id="inp_date_hr">
                     </div>
                     <button class="btn btn-outline-info me-md-2 btn-sm" type="button" onclick="employee_actions(`'.$emp_code.'`, 1)">Ver horario actual</button>
                     <button class="btn btn-outline-success me-md-2 btn-sm" type="button" onclick="employee_actions(`'.$emp_code.'`, 3)">Actualizar horario</button>
                  </div>
               </div><br>
               <table class="table table-sm table-hover align-middle" id="tb_horarios">
                  <thead>
                     <tr>
                           <th scope="col">#</th>
                           <th scope="col">Alias</th>
                           <th scope="col">Lunes</th>
                           <th scope="col">Martes</th>
                           <th scope="col">Miercoles</th>
                           <th scope="col">Jueves</th>
                           <th scope="col">Viernes</th>
                           <th scope="col">Sabado</th>
                           <th scope="col">Domingo</th>
                     </tr>
                  </thead>
                  <tbody>
            ';
            $sql_hr_2 = "SELECT shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_1'";
            $exe_hr_2 = sqlsrv_query($conn, $sql_hr_2);
            $fila_hr_2 = sqlsrv_fetch_array($exe_hr_2, SQLSRV_FETCH_ASSOC);
            $shift_id = $fila_hr_2['shift_id'];

            $sql_hr_3 = "SELECT id, alias FROM [zkbiotime].[dbo].[att_attshift]";
            $exe_hr_3 = sqlsrv_query($conn, $sql_hr_3);
            while ($fila_hr_3 = sqlsrv_fetch_array($exe_hr_3, SQLSRV_FETCH_ASSOC)) {
                $id_3 = $fila_hr_3['id'];
                if ($shift_id == $id_3) {
                    $chk_hrs = '
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rdo_horarios" id="rdo_'.$id_3.'" value="'.$id_3.'" checked>
                            <label class="form-check-label" for="rdo_'.$id_3.'">
                                Actual
                            </label>
                        </div>
                    ';
                } else {
                    $chk_hrs = '
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rdo_horarios" id="rdo_'.$id_3.'" value="'.$id_3.'">
                        </div>
                    ';
                }
                
                $alias_3 = $fila_hr_3['alias'];
                $sql_hr_4 = "SELECT
                        CAST(att_s.in_time AS time)[hora_entrada], 
                        att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                        CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                    FROM
                        [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                    INNER JOIN
                        [zkbiotime].[dbo].[att_timeinterval] AS att_t
                    ON
                        att_s.time_interval_id = att_t.id
                    WHERE
                        att_s.shift_id = '$id_3'
                    ORDER BY
                        att_s.day_index ASC
                ";
                $exe_hr_4 = sqlsrv_query($conn, $sql_hr_4);
                while ($fila_hr_4 = sqlsrv_fetch_array($exe_hr_4, SQLSRV_FETCH_ASSOC)) {
                    $h_in = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_4['hora_entrada']))));
                    $h_in = substr($h_in, 16, 5);
                    $h_out = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_4['hora_salida']))));
                    $h_out = substr($h_out, 16, 5);
                    $day_index_4 = $fila_hr_4['day_index'];
                    $alias_4 = $fila_hr_4['alias'];
                    $v_desc = strpos($alias_4, "escanso");
                    switch ($day_index_4) {
                        case '0':
                            if ($v_desc !== false) {
                                $h_dom = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_dom = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
                        
                        case '1':
                            if ($v_desc !== false) {
                                $h_lun = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_lun = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            
                            break;
    
                        case '2':
                            if ($v_desc !== false) {
                                $h_mar = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_mar = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
    
                        case '3':
                            if ($v_desc !== false) {
                                $h_mie = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_mie = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
    
                        case '4':
                            if ($v_desc !== false) {
                                $h_jue = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_jue = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
    
                        case '5':
                            if ($v_desc !== false) {
                                $h_vie = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_vie = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
    
                        case '6':
                            if ($v_desc !== false) {
                                $h_sab = ' 
                                    <td>
                                        <center>
                                            <div class="text-bg-success p-3">Dia de descanso</div>
                                        </center>
                                    </td>
                                ';
                            } else {
                                $h_sab = ' 
                                    <td>
                                        <center>
                                            <table class="table table-bordered border-warning">
                                                <tr>
                                                    <td><b>Hora de entrada: </b></td>
                                                    <td>'.$h_in.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Hora de salida: </b></td>
                                                    <td>'.$h_out.'</td>
                                                </tr>
                                            </table>
                                        </center>
                                    </td>
                                ';
                            }
                            break;
                    }
                }
                echo '
                    <tr>
                        <th scope="row">'.$chk_hrs.'</th>
                        <td><center>'.$alias_3.'</center></td>
                        '.$h_lun.'
                        '.$h_mar.'
                        '.$h_mie.'
                        '.$h_jue.'
                        '.$h_vie.'
                        '.$h_sab.'
                        '.$h_dom.'
                    </tr>
                ';
            }
            echo '
                    </tbody>
                </table>
            ';
            break;

        case '3':
            $rdo_horarios_val = $_POST['rdo_horarios_val'];
            $v_observaciones = $_POST['v_observaciones'];
            $inp_date_hr = $_POST['inp_date_hr'];

            $sql_hr_2 = "SELECT id, shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_1'";
            $exe_hr_2 = sqlsrv_query($conn, $sql_hr_2);
            $fila_hr_2 = sqlsrv_fetch_array($exe_hr_2, SQLSRV_FETCH_ASSOC);
            $id_2 = $fila_hr_2['id'];
            $shift_id_2 = $fila_hr_2['shift_id'];

            //echo "Seleccionado: ".$rdo_horarios_val."| Anterior: ".$shift_id_2;

            if ($shift_id_2 == $rdo_horarios_val) {
               echo "1|El horario seleccionado es el actual.";//***Es el mismo horario
            } else {
               //***HORARIO ANTERIOR***
               $sql_hr_3 = "SELECT
                        CAST(att_s.in_time AS time)[hora_entrada], 
                        att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                        CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                  FROM
                        [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                  INNER JOIN
                        [zkbiotime].[dbo].[att_timeinterval] AS att_t
                  ON
                        att_s.time_interval_id = att_t.id
                  WHERE
                        att_s.shift_id = '$shift_id_2'
                  ORDER BY
                        att_s.day_index ASC
               ";
               $exe_hr_3 = sqlsrv_query($conn, $sql_hr_3);
               while ($fila_hr_3 = sqlsrv_fetch_array($exe_hr_3, SQLSRV_FETCH_ASSOC)) {
                  $h_in = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_entrada']))));
                  $h_in = substr($h_in, 16, 5);
                  $h_out = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3['hora_salida']))));
                  $h_out = substr($h_out, 16, 5);
                  $day = $fila_hr_3['day_index'];
                  $alias = $fila_hr_3['alias'];
                  $v_desc = strpos($alias, "escanso");
                  switch ($day) {
                        case '0':
                           if ($v_desc !== false) {
                              $v_dom = ' 
                                 <p><strong>Dom</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_dom = ' 
                                 <p><strong>Dom</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
                        
                        case '1':
                           if ($v_desc !== false) {
                              $v_lun = ' 
                                 <p><strong>Lun</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_lun = ' 
                                 <p><strong>Lun</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           
                           break;
   
                        case '2':
                           if ($v_desc !== false) {
                              $v_mar = ' 
                                 <p><strong>Mar</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_mar = ' 
                                 <p><strong>Mar</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
   
                        case '3':
                           if ($v_desc !== false) {
                              $v_mie = ' 
                                 <p><strong>Mie</strong><br>Descanso</p>
                              ';
                           } else {
                              $alias_z = $alias;
                              $v_mie = ' 
                                 <p><strong>Mie</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
   
                        case '4':
                           if ($v_desc !== false) {
                              $v_jue = ' 
                                 <p><strong>Jue</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_jue = ' 
                                 <p><strong>Jue</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
   
                        case '5':
                           if ($v_desc !== false) {
                              $v_vie = ' 
                                 <p><strong>Vie</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_vie = ' 
                                 <p><strong>Vie</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
   
                        case '6':
                           if ($v_desc !== false) {
                              $v_sab = ' 
                                 <p><strong>Sab</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_sab = ' 
                                 <p><strong>Sab</strong><br>'.$h_in.' - '.$h_out.'</p>
                              ';
                           }
                           break;
                  }
               }
               //***HORARIO ANTERIOR***
               //***HORARIO NUEVO***
               $sql_hr_3_B = "SELECT
                        CAST(att_s.in_time AS time)[hora_entrada], 
                        att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                        CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                  FROM
                        [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                  INNER JOIN
                        [zkbiotime].[dbo].[att_timeinterval] AS att_t
                  ON
                        att_s.time_interval_id = att_t.id
                  WHERE
                        att_s.shift_id = '$rdo_horarios_val'
                  ORDER BY
                        att_s.day_index ASC
               ";
               $exe_hr_3_B = sqlsrv_query($conn, $sql_hr_3_B);
               while ($fila_hr_3_B = sqlsrv_fetch_array($exe_hr_3_B, SQLSRV_FETCH_ASSOC)) {
                  $h_in_B = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3_B['hora_entrada']))));
                  $h_in_B = substr($h_in_B, 16, 5);
                  $h_out_B = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_hr_3_B['hora_salida']))));
                  $h_out_B = substr($h_out_B, 16, 5);
                  $day_B = $fila_hr_3_B['day_index'];
                  $alias_B = $fila_hr_3_B['alias'];
                  $v_desc_B = strpos($alias_B, "escanso");
                  switch ($day_B) {
                        case '0':
                           if ($v_desc_B !== false) {
                              $v_dom_B = ' 
                                 <p><strong>Dom</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_dom_B = ' 
                                 <p><strong>Dom</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
                        
                        case '1':
                           if ($v_desc_B !== false) {
                              $v_lun_B = ' 
                                 <p><strong>Lun</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_lun_B = ' 
                                 <p><strong>Lun</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';

                              $horario_dia_pdf = $h_in_B.' - '.$h_out_B;
                           }
                           
                           break;
   
                        case '2':
                           if ($v_desc_B !== false) {
                              $v_mar_B = ' 
                                 <p><strong>Mar</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_mar_B = ' 
                                 <p><strong>Mar</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
   
                        case '3':
                           if ($v_desc_B !== false) {
                              $v_mie_B = ' 
                                 <p><strong>Mie</strong><br>Descanso</p>
                              ';
                           } else {
                              $alias_z_B = $alias_B;
                              $v_mie_B = ' 
                                 <p><strong>Mie</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
   
                        case '4':
                           if ($v_desc_B !== false) {
                              $v_jue_B = ' 
                                 <p><strong>Jue</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_jue_B = ' 
                                 <p><strong>Jue</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
   
                        case '5':
                           if ($v_desc_B !== false) {
                              $v_vie_B = ' 
                                 <p><strong>Vie</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_vie_B = ' 
                                 <p><strong>Vie</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
   
                        case '6':
                           if ($v_desc_B !== false) {
                              $v_sab_B = ' 
                                 <p><strong>Sab</strong><br>Descanso</p>
                              ';
                           } else {
                              $v_sab_B = ' 
                                 <p><strong>Sab</strong><br>'.$h_in_B.' - '.$h_out_B.'</p>
                              ';
                           }
                           break;
                  }
               }
               //***HORARIO NUEVO***

               if (empty($inp_date_hr)) {
                  $inp_date_hr_desc = "";
               } else {
                  $inp_date_hr_desc = "- $alias_z_B // $horario_dia_pdf para el ".saber_dia($inp_date_hr)." ".date('j', strtotime($inp_date_hr))." de ".$meses[date('n', strtotime($inp_date_hr))]." del ".date("Y", strtotime($inp_date_hr))." -";
               }

               $strHTML = '
                  <style>
                     td, th {
                        //width: auto;
                        //border: 1px solid;
                        text-align: center;
                     }
                     table {
                        width: 100%;
                        border-collapse: collapse;
                     }
                  </style>
                  <page backleft="5mm">
                     <table>
                        <tbody>
                           <tr>
                              <td width="158" style="text-align: center; font-size: 20px;">
                                 <img src="../../images/novag_logo.png" style="width: 140px; height: 55px;"/>
                              </td>
                              <td width="535" style="text-align: center; font-size: 20px;">
                                 <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>CAMBIO DE TURNO
                              </td>
                              <td width="50" style="text-align: center; font-size: 12px;">
                                 <strong></strong>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <br>
                     <table>
                        <tbody>
                           <tr>
                              <td style="text-align: right;">Ciudad de México, a <strong>'.saber_dia($date_full)." ".date('j', strtotime($date_full))." de ".$meses[date('n', strtotime($date_full))]." del ".date('y', strtotime($date_full)).'</strong></td>
                           </tr>
                        </tbody>
                     </table>
                     <br><br>
                     <table style="font-size: 12px;" >
                        <thead>
                           <tr>
                              <th style="border: 1px solid; font-size: 10px;" width="auto" colspan="2">Datos personales</th>
                              <th style="border: 1px solid; font-size: 10px;" width="auto" colspan="7">Datos de cambio de horario</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Nombre</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$last_name_1.' '.$first_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Horario anterior - '.$alias_z.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Clave de empleado</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$emp_code.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_lun.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mar.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mie.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_jue.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_vie.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_sab.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_dom.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Fecha de ingreso</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$hire_date_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Horario nuevo - '.$alias_z_B.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Puesto</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$position_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_lun_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mar_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mie_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_jue_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_vie_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_sab_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_dom_B.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Área de adscripción</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$dept_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Observaciones</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left; font-style: italic;"colspan="2">'.$txt_hist.'</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;" colspan="7">'.$v_observaciones.'<br>'.$inp_date_hr_desc.'</td>
                           </tr>
                        </tbody>
                     </table>
                     <br><br><br><br>
                     <table>
                        <tbody>
                            <tr>
                                <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe inmediato</td>
                                <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe de produccion</td>
                                <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma recursos humanos</td>
                                <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma vigilancia</td>
                            </tr>
                        </tbody>
                    </table>
                     <!--PARTE 2-->
                     <br>
                     <table>
                        <tbody>
                           <tr>
                              <td>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>
                           </tr>
                        </tbody>
                     </table>
                     <br>
                     <table>
                        <tbody>
                           <tr>
                              <td width="158" style="text-align: center; font-size: 20px;">
                                 <img src="../../images/novag_logo.png" style="width: 140px; height: 55px;"/>
                              </td>
                              <td width="535" style="text-align: center; font-size: 20px;">
                                 <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>CAMBIO DE TURNO
                              </td>
                              <td width="50" style="text-align: center; font-size: 12px;">
                                 <strong></strong>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <br>
                     <table>
                        <tbody>
                           <tr>
                              <td style="text-align: right;">Ciudad de México, a <strong>'.saber_dia($date_full)." ".date('j', strtotime($date_full))." de ".$meses[date('n', strtotime($date_full))]." del ".date('y', strtotime($date_full)).'</strong></td>
                           </tr>
                        </tbody>
                     </table>
                     <br><br>
                     <table style="font-size: 12px;" >
                        <thead>
                           <tr>
                              <th style="border: 1px solid; font-size: 10px;" width="auto" colspan="2">Datos personales</th>
                              <th style="border: 1px solid; font-size: 10px;" width="auto" colspan="7">Datos de cambio de horario</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Nombre</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$last_name_1.' '.$first_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Horario anterior - '.$alias_z.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Clave de empleado</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$emp_code.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_lun.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mar.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mie.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_jue.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_vie.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_sab.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_dom.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Fecha de ingreso</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$hire_date_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Horario nuevo - '.$alias_z_B.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Puesto</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$position_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_lun_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mar_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_mie_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_jue_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_vie_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_sab_B.'</td>
                              <td style="border: 1px solid; font-size: 10px;">'.$v_dom_B.'</td>
                           </tr>
                           <tr>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">Área de adscripción</td>
                              <td style="border: 1px solid; font-size: 10px; text-align: left;">'.$dept_name_1.'</td>
                              <td style="border: 1px solid; font-size: 10px; font-style: italic;" colspan="7">Observaciones</td>
                           </tr>
                           <tr>
                           <td style="border: 1px solid; font-size: 10px; text-align: left; font-style: italic;"colspan="2">'.$txt_hist.'</td>
                           <td style="border: 1px solid; font-size: 10px; text-align: left;" colspan="7">'.$v_observaciones.'<br>'.$inp_date_hr_desc.'</td>
                           </tr>
                        </tbody>
                     </table>
                     <br><br><br><br>
                     <table>
                        <tbody>
                            <tr>
                              <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe inmediato</td>
                              <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe de produccion</td>
                              <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma recursos humanos</td>
                              <td width="175" style="font-size: 9px;">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma vigilancia</td>
                            </tr>
                        </tbody>
                    </table>
                  </page>';
               
               if (empty($inp_date_hr)) {
                  if (empty($id_2)) {
                     $sql_hr_3 = "INSERT INTO [dbo].[att_attschedule] ([start_date], [end_date], [employee_id], [shift_id]) VALUES ('$fecha_ym_now-01 00:00:00.000', '$fecha_ym_now-30 00:00:00.000', '$id_bd_1', '$rdo_horarios_val')";
                  }else{
                     $sql_hr_3 = "UPDATE [zkbiotime].[dbo].[att_attschedule] SET shift_id = '$rdo_horarios_val' WHERE id = '$id_2'";
                  }
                  sqlsrv_query($conn, $sql_hr_3);
               }else{
                  $sql_hr_3 = "
                     INSERT INTO dbo.rh_solicitudes(
                        id_empleado, tipo_ausencia, tipo_goce,
                        f_ini, f_fin, observaciones,
                        f_solicitud, id_solicitante, id_depto,
                        estatus, hr_in, hr_out, tipo_permiso,
                        historico)
                     VALUES
                        ('$emp_code', 'Cambio de horario', 'Sin goce de sueldo',
                        '$inp_date_hr', '$inp_date_hr', '$inp_date_hr_desc',
                        '$fecha_hora_now', '$emp_code', '$department_id_1',
                        '1', '00:00:00.0000000', '00:00:00.0000000', '4',
                        'Solicitud creada y aceptada por $name_a_session con fecha $fecha_hora_now'
                     )";
                  sqlsrv_query($cnx, $sql_hr_3);
               }

               $fichero_pdf = $destino.'formato_'.$emp_code.'.pdf';
               $html2pdf = new HTML2PDF('P', 'Letter', 'es', 'true', 'UTF-8');
               //***L -> Horizontal | P -> Vertical || A5 -> Media carta | Letter -> Carta
               $html2pdf->writeHTML($strHTML);
               $html2pdf->Output($fichero_pdf,'F');

               echo "2*formato_".$emp_code.".pdf|";//***Actualizacion satisfactoria
            }

            break;
    }
?>
<script>
    $(document).ready(function() {
        $('#tb_horarios').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            //searching: false,
            order: [[0, 'desc']],
        });
    });
</script>
<!DOCTYPE html>
<html>
  <head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <title>Calendario de vacaciones</title>
	  <link rel="stylesheet" type="text/css" href="css/fullcalendar.min.css">
	  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/home.css">
  </head>
  <body>
    <?php
      /*include('config.php');
      $SqlEventos   = ("SELECT * FROM eventoscalendar");
      $resulEventos = mysqli_query($con, $SqlEventos);*/
      include ('../php/conn.php');
      session_start();
      $num_empleado_session = $_SESSION['num_empleado_a'];

      $sql_super = "SELECT COUNT(id) AS super_conf FROM rh_supervisores WHERE num_emp = '$num_empleado_session'";
      $exe_super = sqlsrv_query($cnx, $sql_super);
      $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
      $super_conf = $fila_super['super_conf'];
      if ($super_conf > 0) {
        $in_emps = '';
        $sql_super_emp = "SELECT [id], [id_super], [num_emp_asign] FROM [rh_novag_system].[dbo].[rh_supers_emps] WHERE id_super = '$num_empleado_session'";
        $exe_super_emp = sqlsrv_query($cnx, $sql_super_emp);
        while ($fila_super_emp = sqlsrv_fetch_array($exe_super_emp, SQLSRV_FETCH_ASSOC)) {
          $in_emps .= "'".$fila_super_emp['num_emp_asign']."', ";
        }

        if (empty($in_emps)) {
         $query_search2 = "SELECT * FROM rh_jefe_directo WHERE num_emp_boss = '$num_empleado_session'";
         $exe_search2 = sqlsrv_query($cnx, $query_search2);
         while ($fila_search2 = sqlsrv_fetch_array($exe_search2, SQLSRV_FETCH_ASSOC)) {
            $in_emps .= "'".$fila_search2['num_emp']."', ";
         }
        }
        
        $in_emps = substr($in_emps, 0, -2);

        $query_e = "SELECT 
              rv.id, rv.tipo_ausencia, rv.f_solicitud,
              rv.id_empleado, pe.first_name, pe.last_name,
              rv.estatus, rv.fecha_array
          FROM 
              rh_vacaciones rv
          INNER JOIN
              personnel_employee pe
          ON 
              pe.emp_code = rv.id_empleado
          WHERE 
              rv.id_empleado IN (".$in_emps.")";
      }else{
        $query_a = "SELECT * FROM personnel_department WHERE emp_code_charge = '$num_empleado_session' OR sub_emp_code_charge = '$num_empleado_session'";
        $exe_a = sqlsrv_query($cnx, $query_a);
        $in_a = '';
        while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
            $id_bd_a = $fila_a['dept_code'];
            $in_a .= "'".$id_bd_a."', ";
        }

        $in_a = substr($in_a, 0, -2);
        //$query_b = "SELECT * FROM personnel_department WHERE parent_dept_id IN (".$in_a.") AND emp_code_charge = '$num_empleado_session'";
        $query_b = "SELECT * FROM personnel_department WHERE dept_code IN (".$in_a.") AND emp_code_charge = '$num_empleado_session' OR sub_emp_code_charge = '$num_empleado_session'";
        $exe_b = sqlsrv_query($cnx, $query_b);
        $in_a = '';
        while ($fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
            $id_bd_b = $fila_b['id'];
            $in_a .= "'".$id_bd_b."', ";
        }
        
        $in_a = substr($in_a, 0, -2);
        $query_e = "SELECT 
              rv.id, rv.tipo_ausencia, rv.f_solicitud,
              rv.id_empleado, pe.first_name, pe.last_name,
              rv.estatus, rv.fecha_array
          FROM 
              rh_vacaciones rv
          INNER JOIN
              personnel_employee pe
          ON 
              pe.emp_code = rv.id_empleado
          WHERE 
              rv.id_depto IN (".$in_a.")";
      }
      $exe_e = sqlsrv_query($cnx, $query_e);

      $query_fest = "SELECT * FROM [rh_novag_system].[dbo].[rh_festivos]";
      $exe_fest = sqlsrv_query($cnx, $query_fest);
    ?>
    <div class="mt-5"></div>
    <div class="container">
      <div class="row">
        <div class="col msjs">
          <?php
            include('msjs.php');
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-3">
          <h3 class="text-center" id="title">Calendario de vacaciones</h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 col-sm-12">
      </div>
      <div class="col-md-2 col-sm-12">
        <table>
          <thead>
            <tr>
              <th colspan="2">Paleta de colores</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Pendientes</td>
              <td style="background-color:#BECA09;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
              <td>Aprobadas</td>
              <td style="background-color:#20AB0D;"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br>
    <div id="calendar"></div>
    <?php  
      include('modalNuevoEvento.php');
      include('modalUpdateEvento.php');
    ?>
    <script src ="js/jquery-3.0.0.min.js"> </script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>	
    <script type="text/javascript" src="js/fullcalendar.min.js"></script>
    <script src='locales/es.js'></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#calendar").fullCalendar({
          header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek"
            //right: "month,agendaWeek,agendaDay"
          },
          locale: 'es',
          defaultView: "month",
          navLinks: true, 
          editable: true,
          eventLimit: true, 
          selectable: true,
          selectHelper: false,
          //Nuevo Evento
          /*select: function(start, end){
            $("#exampleModal").modal();
            $("input[name=fecha_inicio]").val(start.format('DD-MM-YYYY'));
            var valorFechaFin = end.format("DD-MM-YYYY");
            var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY'); //Le resto 1 dia
            $('input[name=fecha_fin').val(F_final);  
          },*/
          events: [
            <?php
              $contador = 0;
              while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
                $contador++;
                $id_e = $fila_e['id'];
                $id_empleado_e = $fila_e['id_empleado'];
                $first_name_e = utf8_encode($fila_e['first_name']);
                $last_name_e = utf8_encode($fila_e['last_name']);
                $tipo_ausencia_e = $fila_e['tipo_ausencia'];
                $estatus_e = $fila_e['estatus'];
                switch ($estatus_e) {
                    case '0':
                      $estatus_e_desc = '#BECA09';
                      break;
                    
                    case '1':
                        $estatus_e_desc = '#20AB0D';
                        break;
                    
                    case '2':
                        $estatus_e_desc = '#AB0D0D';
                        break;

                    case '3':
                        $estatus_e_desc = '#20AB0D';
                        break;
                }
                $fecha_array_e = $fila_e['fecha_array'];
                $txt_v = "#".$id_e." ".$first_name_e." ".$last_name_e;

                $revisa_super = "SELECT COUNT(id) AS to_super FROM rh_supers_emps WHERE num_emp_asign = '$id_empleado_e'";
                $exe_super = sqlsrv_query($cnx, $revisa_super);
                $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
                $to_super = $fila_super['to_super'];
                if ($to_super > 0) {
                    $revisa_super_a = "SELECT id_super FROM rh_supers_emps WHERE num_emp_asign = '$id_empleado_e'";
                    $exe_super_a = sqlsrv_query($cnx, $revisa_super_a);
                    $fila_super_a = sqlsrv_fetch_array($exe_super_a, SQLSRV_FETCH_ASSOC);
                    $id_super = $fila_super_a['id_super'];
                    if ($id_super == $num_empleado_session) {
                        continue;
                    }
                }

                $dias_varr = explode("|", $fecha_array_e);
                foreach ($dias_varr as $key => $value) {
            ?>
            {
              _id: '<?php echo $id_e; ?>',
              title: '<?php echo $txt_v; ?>',
              start: '<?php echo $value; ?>',
              end:   '<?php echo $value; ?>',
              color: '<?php echo $estatus_e_desc; ?>'
            },
            <?php
                }
              }
              while ($fila_fest = sqlsrv_fetch_array($exe_fest, SQLSRV_FETCH_ASSOC)) {
                $id_fest = $fila_fest['id'];
                $dia_festivo = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_fest['dia_festivo'])))), 5, 10);
            ?>
            {
              _id: '<?php echo $id_fest; ?>',
              title: 'DIA FESTIVO',
              start: '<?php echo $dia_festivo; ?>',
              end:   '<?php echo $dia_festivo; ?>',
              color: '#A02000'
            },
            <?php
              }
            ?>
          ],
          //Eliminar Evento
          /*eventRender: function(event, element) {
            element.find(".fc-content").prepend("<span id='btnCerrar'; class='closeon material-icons'>&#xe5cd;</span>");
            //Eliminar evento
            element.find(".closeon").on("click", function() {
              var pregunta = confirm("Deseas Borrar este Evento?");   
              if (pregunta) {
                $("#calendar").fullCalendar("removeEvents", event._id);
                $.ajax({
                  type: "POST",
                  url: 'deleteEvento.php',
                  data: {id:event._id},
                  success: function(datos){
                    $(".alert-danger").show();
                    setTimeout(function () {
                      $(".alert-danger").slideUp(500);
                    }, 3000); 
                  }
                });
              }
            });
          },
          //Moviendo Evento Drag - Drop
          eventDrop: function (event, delta) {
            var idEvento = event._id;
            var start = (event.start.format('DD-MM-YYYY'));
            var end = (event.end.format("DD-MM-YYYY"));
            $.ajax({
              url: 'drag_drop_evento.php',
              data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
              type: "POST",
              success: function (response) {
                // $("#respuesta").html(response);
              }
            });
          },*/
          //Modificar Evento del Calendario 
          eventClick:function(event){
            var idEvento = event._id;
            $('input[name=idEvento').val(idEvento);
            $('input[name=evento').val(event.title);
            $('input[name=fecha_inicio').val(event.start.format('DD-MM-YYYY'));
            $('input[name=fecha_fin').val(event.end.format("DD-MM-YYYY"));
            $("#modalUpdateEvento").modal();
          },
        });
        //Oculta mensajes de Notificacion
        setTimeout(function () {
          $(".alert").slideUp(300);
        }, 3000); 
      });
    </script>
  </body>
</html>
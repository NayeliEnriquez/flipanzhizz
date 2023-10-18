<?php
   session_start();
   date_default_timezone_set("America/Mazatlan");
   $num_empleado_session = $_SESSION['num_empleado_a'];
   include ('../php/conn.php');
   $js_active = 0;
   $tipo_de_reporte = 0;
   $year_current = date('Y');
   $today = date('Y-m-d');
   
   function getFirstDayWeek($week, $year){
      $dt = new DateTime();
      $return['start'] = $dt->setISODate($year, $week)->format('d-m-Y');
      $return['end'] = $dt->modify('+6 days')->format('d-m-Y');
      return $return;
   }

   $dept_rh = "23|39|50|54";
   if ($num_empleado_session != '7042') {
      $query_dept = "SELECT department_id FROM personnel_employee WHERE personnel_employee.emp_code = '$num_empleado_session'";
      $exe_dept = sqlsrv_query($conn, $query_dept);
      $fila_dept = sqlsrv_fetch_array($exe_dept, SQLSRV_FETCH_ASSOC);
      $department_id = $fila_dept['department_id'];
      $rev_dept = strpos($dept_rh, $department_id);
      if ($rev_dept !== false) {
         $num_empleado_session = "RH";
      }
   }

   switch ($num_empleado_session) {
      case '7042'://***Lourdes Mejia
         $tipo_de_reporte = 1;
         break;

      case 'RH'://***Personal RH
         $tipo_de_reporte = 2;
         break;

      case '3016'://***Gerardo Cedillo
      case '5698'://***Norma Nelly
         $tipo_de_reporte = 3;
         break;
      
      case '3119'://***Luis Esteban Vazquez (Jefe de Almacen)
         $tipo_de_reporte = 4;
         break;
      
      default:
         $tipo_de_reporte = 0;
         header('Location: 403.php');
         break;
   }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Reportes</title>
        <!--<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">-->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <script type="text/javascript" src="../js/rh.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <style>
        *
        {
        font-family: 'PT Sans Caption', sans-serif, 'arial', 'Times New Roman';
        }
        /* Error Page */
            .error .clip .shadow
            {
                height: 180px;  /*Contrall*/
            }
            .error .clip:nth-of-type(2) .shadow
            {
                width: 130px;   /*Contrall play with javascript*/ 
            }
            .error .clip:nth-of-type(1) .shadow, .error .clip:nth-of-type(3) .shadow
            {
                width: 250px; /*Contrall*/
            }
            .error .digit
            {
                width: 150px;   /*Contrall*/
                height: 150px;  /*Contrall*/
                line-height: 150px; /*Contrall*/
                font-size: 120px;
                font-weight: bold;
            }
            .error h2   /*Contrall*/
            {
                font-size: 32px;
            }
            .error .msg /*Contrall*/
            {
                top: -190px;
                left: 30%;
                width: 80px;
                height: 80px;
                line-height: 80px;
                font-size: 32px;
            }
            .error span.triangle    /*Contrall*/
            {
                top: 70%;
                right: 0%;
                border-left: 20px solid #535353;
                border-top: 15px solid transparent;
                border-bottom: 15px solid transparent;
            }


            .error .container-error-404
            {
            margin-top: 10%;
                position: relative;
                height: 250px;
                padding-top: 40px;
            }
            .error .container-error-404 .clip
            {
                display: inline-block;
                transform: skew(-45deg);
            }
            .error .clip .shadow
            {
                
                overflow: hidden;
            }
            .error .clip:nth-of-type(2) .shadow
            {
                overflow: hidden;
                position: relative;
                box-shadow: inset 20px 0px 20px -15px rgba(150, 150, 150,0.8), 20px 0px 20px -15px rgba(150, 150, 150,0.8);
            }
            
            .error .clip:nth-of-type(3) .shadow:after, .error .clip:nth-of-type(1) .shadow:after
            {
                content: "";
                position: absolute;
                right: -8px;
                bottom: 0px;
                z-index: 9999;
                height: 100%;
                width: 10px;
                background: linear-gradient(90deg, transparent, rgba(173,173,173, 0.8), transparent);
                border-radius: 50%;
            }
            .error .clip:nth-of-type(3) .shadow:after
            {
                left: -8px;
            }
            .error .digit
            {
                position: relative;
                top: 8%;
                color: white;
                background: #07B3F9;
                border-radius: 50%;
                display: inline-block;
                transform: skew(45deg);
            }
            .error .clip:nth-of-type(2) .digit
            {
                left: -10%;
            }
            .error .clip:nth-of-type(1) .digit
            {
                right: -20%;
            }.error .clip:nth-of-type(3) .digit
            {
                left: -20%;
            }    
            .error h2
            {
                color: #A2A2A2;
                font-weight: bold;
                padding-bottom: 20px;
            }
            .error .msg
            {
                position: relative;
                z-index: 9999;
                display: block;
                background: #535353;
                color: #A2A2A2;
                border-radius: 50%;
                font-style: italic;
            }
            .error .triangle
            {
                position: absolute;
                z-index: 999;
                transform: rotate(45deg);
                content: "";
                width: 0; 
                height: 0; 
            }

        /* Error Page */
        @media(max-width: 767px)
        {
            /* Error Page */
                    .error .clip .shadow
                    {
                        height: 100px;  /*Contrall*/
                    }
                    .error .clip:nth-of-type(2) .shadow
                    {
                        width: 80px;   /*Contrall play with javascript*/ 
                    }
                    .error .clip:nth-of-type(1) .shadow, .error .clip:nth-of-type(3) .shadow
                    {
                        width: 100px; /*Contrall*/
                    }
                    .error .digit
                    {
                        width: 80px;   /*Contrall*/
                        height: 80px;  /*Contrall*/
                        line-height: 80px; /*Contrall*/
                        font-size: 52px;
                    }
                    .error h2   /*Contrall*/
                    {
                        font-size: 24px;
                    }
                    .error .msg /*Contrall*/
                    {
                        top: -110px;
                        left: 15%;
                        width: 40px;
                        height: 40px;
                        line-height: 40px;
                        font-size: 18px;
                    }
                    .error span.triangle    /*Contrall*/
                    {
                        top: 70%;
                        right: -3%;
                        border-left: 10px solid #535353;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent;
                    }
        .error .container-error-404
        {
            height: 150px;
        }
                /* Error Page */
        }

        /*--------------------------------------------Framework --------------------------------*/

        .overlay { position: relative; z-index: 20; } /*done*/
            .ground-color { background: white; }  /*done*/
            .item-bg-color { background: #EAEAEA } /*done*/
            
            /* Padding Section*/
                .padding-top { padding-top: 10px; } /*done*/
                .padding-bottom { padding-bottom: 10px; }   /*done*/
                .padding-vertical { padding-top: 10px; padding-bottom: 10px; }
                .padding-horizontal { padding-left: 10px; padding-right: 10px; }
                .padding-all { padding: 10px; }   /*done*/

                .no-padding-left { padding-left: 0px; }    /*done*/
                .no-padding-right { padding-right: 0px; }   /*done*/
                .no-vertical-padding { padding-top: 0px; padding-bottom: 0px; }
                .no-horizontal-padding { padding-left: 0px; padding-right: 0px; }
                .no-padding { padding: 0px; }   /*done*/
            /* Padding Section*/

            /* Margin section */
                .margin-top { margin-top: 10px; }   /*done*/
                .margin-bottom { margin-bottom: 10px; } /*done*/
                .margin-right { margin-right: 10px; } /*done*/
                .margin-left { margin-left: 10px; } /*done*/
                .margin-horizontal { margin-left: 10px; margin-right: 10px; } /*done*/
                .margin-vertical { margin-top: 10px; margin-bottom: 10px; } /*done*/
                .margin-all { margin: 10px; }   /*done*/
                .no-margin { margin: 0px; }   /*done*/

                .no-vertical-margin { margin-top: 0px; margin-bottom: 0px; }
                .no-horizontal-margin { margin-left: 0px; margin-right: 0px; }

                .inside-col-shrink { margin: 0px 20px; }    /*done - For the inside sections that has also Title section*/ 
            /* Margin section */

            hr
            { margin: 0px; padding: 0px; border-top: 1px dashed #999; }
        /*--------------------------------------------FrameWork------------------------*/
    </style>
    <body>
        <?php
            include('navs/header_sys.php');
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                    $name_self = basename($_SERVER['PHP_SELF']);
                    include('navs/nav_menu.php');
                ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Reportes</h1>
                    </div>
                    <?php
                        if ($tipo_de_reporte == 1) {//***Mejia
                            ?>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <select class="form-select" aria-label="Default select example" id="slc_kardex">
                                        <option value="0" selected>Seleccionar un reporte de Kardex</option>
                                        <option value="1">KARDEX 01 MANTENIMIENTO OFICINAS</option>
                                        <option value="3">KARDEX 03 QUINCENA TLALPAN</option>
                                        <option value="4">KARDEX 04 ALMACEN TLALPAN</option>
                                        <option value="5">KARDEX 05 PRODUCCION TLALPAN</option>
                                        <option value="6">KARDEX 06 MANTENIMIENTO GENERAL</option>
                                        <option value="7">KARDEX 07 ALMACEN IZTAPALAPA</option>
                                        <option value="9">KARDEX 09 LABORATORIO ANALISIS</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <select class="form-select" id="slc_year">
                                        <option value="<?php echo($year_current); ?>" selected><?php echo($year_current); ?></option>
                                        <?php
                                            for ($i = $year_current-1; $i > 2019 ; $i--) { 
                                                echo '<option value='.$i.'>'.$i.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <input class="form-control" list="datalistOptions" id="list_num_empl_k" placeholder="Buscar empleado">
                                    <datalist id="datalistOptions">
                                        <?php
                                            $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                            $exe_1 = sqlsrv_query($cnx, $query1);
                                            while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                                $emp_code_1 = $fila_1['emp_code'];
                                                $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                                $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                                echo '
                                                    <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                                ';
                                            }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_kardex">Personal x Kardex</button>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="btn_busca_lsm()">Buscar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div id="response_kardex"></div>
                            </div>
                           <?php
                        }elseif ($tipo_de_reporte == 2) {//***DEPTO RH
                           ?>
                           <div class="row">
                              <div class="col-md-12 col-sm-12">
                                 <table width="100%">
                                    <tbody>
                                       <tr>
                                          <td>
                                             <label for="inp_num_empl_rh" class="form-label"># empleado</label>
                                             <input class="form-control form-control-sm" list="datalistOptions" id="inp_num_empl_rh" placeholder="Busqueda...">
                                             <datalist id="datalistOptions">
                                                <?php
                                                   $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                                   $exe_1 = sqlsrv_query($conn, $query1);
                                                   while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                                      $emp_code_1 = $fila_1['emp_code'];
                                                      $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                                      $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                                      echo '
                                                         <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                                      ';
                                                   }
                                                ?>
                                             </datalist>
                                          </td>
                                          <td>
                                             <label for="inp_f_busqueda" class="form-label">Fecha: </label>
                                             <input id="inp_f_busqueda" class="form-control form-control-sm" type="date">
                                          </td>
                                          <td rowspan="2">
                                             <div class="card text-bg-info mb-3" style="max-width: 18rem;">
                                                <div class="card-header">Tipos de busquedas</div>
                                                <div class="card-body">
                                                   <p class="card-text">
                                                      <ul>
                                                         <li>Por empleado y/&oacute; fecha</li>
                                                         <li>Por semana &oacute; quincena</li>
                                                         <!--<li>Por semana &oacute; quincena y empleado</li>-->
                                                      </ul>
                                                   </p>
                                                </div>
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <label class="form-label" for="slc_semana">Semanas:</label>
                                             <select class="form-select form-select-sm" id="slc_semana">
                                                   <option value="" selected>Semana</option>
                                                   <?php
                                                      for ($i=1; $i < 54; $i++) { 
                                                         $semanas = getFirstDayWeek($i, $year_current);
                                                         echo '<option value='.$i.'>Semana: '.$i.': Del '.$semanas[start].' al '.$semanas[end].'</option>';
                                                      }
                                                   ?>
                                             </select>
                                          </td>
                                          <td>
                                             <label for="slc_quincena" class="form-label">Quincena:</label>
                                             <select class="form-select form-select-sm" id="slc_quincena">
                                                <option value="" selected="">Quincena</option>
                                                <option value="1">Del 1ro al 15 de Enero</option>
                                                <option value="2">Del 16 al 31 de Enero</option>
                                                <option value="3">Del 1ro al 15 de Febrero</option>
                                                <option value="4">Del 16 al 28 de Febrero</option>
                                                <option value="5">Del 1ro al 15 de Marzo</option>
                                                <option value="6">Del 16 al 31 de Marzo</option>
                                                <option value="7">Del 1ro al 15 de Abril</option>
                                                <option value="8">Del 16 al 30 de Abril</option>
                                                <option value="9">Del 1ro al 15 de Mayo</option>
                                                <option value="10">Del 16 al 31 de Mayo</option>
                                                <option value="11">Del 1ro al 15 de Junio</option>
                                                <option value="12">Del 16 al 30 de Junio</option>
                                                <option value="13">Del 1ro al 15 de Julio</option>
                                                <option value="14">Del 16 al 31 de Julio</option>
                                                <option value="15">Del 1ro al 15 de Agosto</option>
                                                <option value="16">Del 16 al 31 de Agosto</option>
                                                <option value="17">Del 1ro al 15 de Septiembre</option>
                                                <option value="18">Del 16 al 30 de Septiembre</option>
                                                <option value="19">Del 1ro al 15 de Octubre</option>
                                                <option value="20">Del 16 al 31 de Octubre</option>
                                                <option value="21">Del 1ro al 15 de Noviembre</option>
                                                <option value="22">Del 16 al 30 de Noviembre</option>
                                                <option value="23">Del 1ro al 15 de Diciembre</option>
                                                <option value="24">Del 16 al 31 de Diciembre</option>
                                             </select>
                                          </td>
                                          <td></td>
                                       </tr>
                                       <tr>
                                          <td colspan="3">
                                             <div class="d-grid gap-2 col-6 mx-auto">
                                                <button class="btn btn-primary" type="button" onclick="busca_rep_rh()">Buscar</button>
                                             </div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12 col-sm-12">
                                 <div id="response_rep_rh"></div>
                              </div>
                           </div>
                           <?php
                        }elseif ($tipo_de_reporte == 3) {//***Norma Nelly y Cedillo
                           ?>
                           <div class="row">
                              <div class="col-md-3 col-sm-12">
                                 <div class="form-check">
                                    <label class="form-label" for="slc_areas_nn">Areas</label>
                                    <select class="form-select" id="slc_areas_nn">
                                       <option value="" selected="" disabled="">Seleccionar una area</option>
                                       <option value="Tabletas">Tabletas</option>
                                       <option value="Fabricacion">Fabricacion</option>
                                       <option value="Acondicionado">Acondicionado</option>
                                       <option value="Liquidos">Liquidos</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                 <label for="inp_fecha_nn" class="form-label">Fecha</label>
                                 <input type="date" class="form-control" id="inp_fecha_nn" value="<?php echo($today); ?>">
                              </div>
                              <div class="col-md-2 col-sm-12">
                                 <br>
                                 <button type="button" class="btn btn-success btn-sm" onclick="busca_reg_te()">Buscar</button>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                 <br>
                                 <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#mdl_listas_nn" id="btn_mdl_first">
                                    Personal x area
                                 </button>
                              </div>
                           </div>
                           <div id="response_te_nn"></div>
                           <?php
                        }elseif ($tipo_de_reporte == 4) {//***Luis Esteban Vazquez (Jefe de Almacen)
                           ?>
                           <div class="row">
                              <div class="col-md-2 col-sm-12">
                                 <div class="form-check">
                                    <label class="form-check-label" for="slc_year">A&ntilde;o:</label>
                                    <select class="form-select" id="slc_year" onchange="fun_year_semana_r(this.value)">
                                          <option value="<?php echo($year_current); ?>" selected><?php echo($year_current); ?></option>
                                          <?php
                                             for ($i = $year_current-1; $i > 2019 ; $i--) { 
                                                echo '<option value='.$i.'>'.$i.'</option>';
                                             }
                                          ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                 <div class="form-check">
                                    <div id="response_year_semana">
                                          <label class="form-check-label" for="slc_semana">Semanas:</label>
                                          <select class="form-select" id="slc_semana">
                                             <option value="" selected>Seleccionar una semana</option>
                                             <?php
                                                //print_r(getFirstDayWeek(1, 2023));
                                                for ($i=1; $i < 54; $i++) { 
                                                      $semanas = getFirstDayWeek($i, $year_current);
                                                      //echo "Semana: ".$i.": Del ".$semanas['start']." al ".$semanas['end'];
                                                      //echo "<br>";
                                                      echo '<option value='.$i.'>Semana: '.$i.': Del '.$semanas[start].' al '.$semanas[end].'</option>';
                                                }
                                             ?>
                                          </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-1" style="padding-right: 0px; padding-left: 0px;">
                                 <center>
                                    <label class="form-check-label">&Oacute;</label>
                                 </center>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                 <div class="form-check">
                                    <label for="slc_quincena" class="form-check-label">Quincena:</label>
                                    <select class="form-select" id="slc_quincena">
                                          <option value="" selected="">Selecciona una quincena</option>
                                          <option value="1">Del 1ro al 15 de Enero</option>
                                          <option value="2">Del 16 al 31 de Enero</option>
                                          <option value="3">Del 1ro al 15 de Febrero</option>
                                          <option value="4">Del 16 al 28 de Febrero</option>
                                          <option value="5">Del 1ro al 15 de Marzo</option>
                                          <option value="6">Del 16 al 31 de Marzo</option>
                                          <option value="7">Del 1ro al 15 de Abril</option>
                                          <option value="8">Del 16 al 30 de Abril</option>
                                          <option value="9">Del 1ro al 15 de Mayo</option>
                                          <option value="10">Del 16 al 31 de Mayo</option>
                                          <option value="11">Del 1ro al 15 de Junio</option>
                                          <option value="12">Del 16 al 30 de Junio</option>
                                          <option value="13">Del 1ro al 15 de Julio</option>
                                          <option value="14">Del 16 al 31 de Julio</option>
                                          <option value="15">Del 1ro al 15 de Agosto</option>
                                          <option value="16">Del 16 al 31 de Agosto</option>
                                          <option value="17">Del 1ro al 15 de Septiembre</option>
                                          <option value="18">Del 16 al 30 de Septiembre</option>
                                          <option value="19">Del 1ro al 15 de Octubre</option>
                                          <option value="20">Del 16 al 31 de Octubre</option>
                                          <option value="21">Del 1ro al 15 de Noviembre</option>
                                          <option value="22">Del 16 al 30 de Noviembre</option>
                                          <option value="23">Del 1ro al 15 de Diciembre</option>
                                          <option value="24">Del 16 al 31 de Diciembre</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-2 col-sm-12"></div>
                              <div class="col-md-1 col-sm-12">
                                 <br>
                                 <button type="button" class="btn btn-outline-info" onclick="reporte_te_r()">Buscar</button>
                              </div>
                           </div>
                           <br>
                           <hr class="my-4">
                           <div class="row">
                                 <div class="col-md-12 col-sm-12">
                                    <div id="response_reporte_te_r"></div>
                                 </div>
                           </div>
                           <?php
                        }
                    ?>
                </main>
            </div>
         </div>
         <!-- Modal -->
         <div class="modal fade" id="mdl_listas_nn" tabindex="-1" aria-labelledby="mdl_listas_nnLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
               <div class="modal-content">
                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="mdl_listas_nnLabel">Listado X &aacute;rea</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-2 col-sm-12">
                           <div class="form-check">
                              <label class="form-label" for="slc_areas_nn_list">&Aacute;reas</label>
                              <select class="form-select" id="slc_areas_nn_list" onchange="listas_tabla_nn(this.value)">
                                 <option value="" selected disabled>Seleccionar una &aacute;rea</option>
                                 <option value="Tabletas">Tabletas</option>
                                 <option value="Fabricacion">Fabricacion</option>
                                 <option value="Acondicionado">Acondicionado</option>
                                 <option value="Liquidos">Liquidos</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                        </div>
                        <div class="col-md-2 col-sm-12">
                           <br>
                           <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mdl_nuevo_nn">Agregar empleado</button>
                        </div>
                     </div>
                     <div id="response_listas_nn"></div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                     <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                  </div>
               </div>
            </div>
         </div>

         <!-- Modal reubicar-->
         <div class="modal fade" id="mdl_reubica_nn" tabindex="-1" aria-labelledby="mdl_reubica_nnLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="mdl_reubica_nnLabel">Reubicar empleado</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div id="response_reubicacion"></div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                     <button type="button" class="btn btn-primary" onclick="fun_areas(1, '0000')">Guardar</button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Modal nuevo-->
         <div class="modal fade" id="mdl_nuevo_nn" tabindex="-1" aria-labelledby="mdl_nuevo_nnLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <h1 class="modal-title fs-5" id="mdl_nuevo_nnLabel">Nuevo empleado</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-4 col-sm-12">
                           <label for="inp_num_empl_sup" class="form-label">Numero de empleado(Enter)</label>
                           <input class="form-control" list="datalistOptions" id="inp_num_empl_sup" placeholder="Busqueda..." onkeyup="busca_emp_sup(event)">
                           <datalist id="datalistOptions">
                              <?php
                                 $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                 $exe_1 = sqlsrv_query($conn, $query1);
                                 while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                    $emp_code_1 = $fila_1['emp_code'];
                                    $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                    $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                    echo '
                                       <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                    ';
                                 }
                              ?>
                           </datalist>
                        </div>
                        <div class="col-md-4 col-sm-12"></div>
                        <div class="col-md-4 col-sm-12">
                           <div class="form-check">
                              <label class="form-label" for="slc_areas_pal_new">&Aacute;reas</label>
                              <select class="form-select" id="slc_areas_pal_new">
                                 <option value="" selected disabled>Seleccionar una &aacute;rea</option>
                                 <option value="Tabletas">Tabletas</option>
                                 <option value="Fabricacion">Fabricacion</option>
                                 <option value="Acondicionado">Acondicionado</option>
                                 <option value="Liquidos">Liquidos</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div id="response_super_emp">
                        <input type="hidden" name="inp_fname_sup" id="inp_fname_sup" value="">
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                     <button type="button" class="btn btn-primary" onclick="fun_areas(4, '0000')">Guardar</button>
                  </div>
               </div>
            </div>
         </div>

         <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
         <!--DataTable-->
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
         <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
         <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

         <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
         <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
         <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    </body>
</html>
<!-- Modal -->
<div class="modal fade" id="mdl_kardex" tabindex="-1" aria-labelledby="mdl_kardexLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="mdl_kardexLabel">Ver personal X Kardex</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <select class="form-select" aria-label="Diferentes Kardex" id="slc_kardex_v">
                            <option value="0" selected>Seleccionar un reporte de Kardex</option>
                            <option value="1">KARDEX 01 MANTENIMIENTO OFICINAS</option>
                            <option value="3">KARDEX 03 QUINCENA TLALPAN</option>
                            <option value="4">KARDEX 04 ALMACEN TLALPAN</option>
                            <option value="5">KARDEX 05 PRODUCCION TLALPAN</option>
                            <option value="6">KARDEX 06 MANTENIMIENTO GENERAL</option>
                            <option value="7">KARDEX 07 ALMACEN IZTAPALAPA</option>
                            <option value="9">KARDEX 09 LABORATORIO ANALISIS</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="verkardex()">Buscar</button>
                    </div>
                </div>
                <center>
                    <div id="response_verkardex">
                    </div>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
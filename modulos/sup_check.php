<?php
    include ('../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $year_current = date('Y');
    session_start();
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."<br>";
	}*/
    $num_empleado_session = $_SESSION['num_empleado_a'];

    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Supervisor de producci&oacute;n</title>
        <!--<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">-->
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
        </style>
        <!-- Custom styles for this template -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <script type="text/javascript" src="../js/rh_nomina.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
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
                        <h1 class="h2">Nomina</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-check">
                                <label class="form-check-label" for="slc_year">A&ntilde;o:</label>
                                <select class="form-select" id="slc_year" onchange="fun_year_semana(this.value)">
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
                        <div class="col-md-2 col-sm-12">
                            <?php
                                if (($num_empleado_session == '5698') || ($num_empleado_session == '3016')) {//***NORMA NELLY PEREZ PEREZ Y GERARDO CEDILLO
                                    ?>
                            <div class="form-check">
                                <label class="form-check-label" for="slc_areas_p">Areas*:</label>
                                <select class="form-select" id="slc_areas_p">
                                    <option value="" selected disabled>Seleccionar una area</option>
                                    <option value="Tabletas">Tabletas</option>
                                    <option value="Fabricacion">Fabricacion</option>
                                    <option value="Acondicionado">Acondicionado</option>
                                    <option value="Liquidos">Liquidos</option>
                                </select>
                            </div>
                                    <?php
                                }else{//***NO APLICAN AREAS
                                    ?>
                                    <input type="hidden" name="slc_areas_p" id="slc_areas_p" value="NA">
                                    <?php
                                }
                            ?>
                        </div>
                        <div class="col-md-1 col-sm-12">
                            <br>
                            <button type="button" class="btn btn-outline-info" onclick="obtener_super_check()">Buscar</button>
                        </div>
                    </div>
                    <br>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div id="response_super_check"></div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!--DataTable-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

        <!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>-->
    </body>
</html>
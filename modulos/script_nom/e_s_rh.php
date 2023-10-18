<?php
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
    $year_today = date('Y');
    $num_empl = $_POST['num_empl'];
    $tipo_nomina = $_POST['tipo_nomina'];

    if ($tipo_nomina == 'SEMANAL') {//***MENU SEMANA
        ?>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-check">
                    <label class="form-check-label" for="slc_year">A&ntilde;o:</label>
                    <select class="form-select" id="slc_year" onchange="fun_year_semana(this.value)">
                        <option value="<?php echo($year_today); ?>" selected><?php echo($year_today); ?></option>
                        <?php
                            for ($i = $year_today-1; $i > 2018 ; $i--) { 
                                echo '<option value='.$i.'>'.$i.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-check">
                    <div id="response_year_semana">
                        <label class="form-check-label" for="slc_semana">Semanas:</label>
                        <select class="form-select" id="slc_semana">
                            <option value="" selected disabled>Seleccionar una semana</option>
                            <?php
                                for ($i=1; $i < 54; $i++) { 
                                    $semanas = getFirstDayWeek($i, $year_today);
                                    echo '<option value='.$i.'>Semana: '.$i.': Del '.$semanas[start].' al '.$semanas[end].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <br>
                <button type="button" class="btn btn-info position-relative btn-sm" onclick="buscar_check_semana(<?php echo($num_empl); ?>)">Buscar</button>
            </div>
        </div>
        <?php
    } else {//***MENU QUINCENA
        ?>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <label for="slc_quincena" class="form-label">Quincena</label>
                <select class="form-select" id="slc_quincena">
                    <option value='0' selected>Selecciona una quincena</option>
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
            <div class="col-md-3 col-sm-12">
                <label for="slc_year" class="form-label">A&ntilde;o</label>
                <select class="form-select" id="slc_year">
                    <option value="<?php echo($year_today); ?>"><?php echo($year_today); ?></option>
                    <?php
                        for ($i=$year_today; $i > 2018 ; $i--) { 
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_dia_unico" class="form-label">Fecha especifica</label>
                <input id="inp_dia_unico" class="form-control" type="date">
            </div>
            <div class="col-md-2 col-sm-12">
                <br>
                <button type="button" class="btn btn-info position-relative btn-sm" onclick="buscar_check_quincena(<?php echo($num_empl); ?>)">
                    Buscar
                </button>
            </div>
        </div>
        <?php
    }
?>
<?php
    date_default_timezone_set("America/Mazatlan");
    /*$fecha = '2023-01-15';	//Fecha de la cual obtendremos la semana
	$fechaSegundos = strtotime($fecha);	// parseamos la fecha a una marca de tiempo Unix 

	$semana = date('W', $fechaSegundos);	// Obtenemos el número de semana con el parametro W y la fecha en Unix
	echo "El número de semana es: " . $semana;	// Imprimimos el número de semana*/
    
    /*$fecha = new DateTime("01/02/2023");
    $semana = $fecha->format('W');
    echo "Semana: $semana";*/

    /*$fechaI = new DateTime('2023-01-02');
    $fechaF = new DateTime('2023-12-31');
    $semanainicio = $fechaI->format("W");
    $semanafin = $fechaF->format("W");

    for ($i = $semanainicio; $i <=  $semanafin; $i++){
        echo "<br>".$i;
    }*/

    /*$ano = 2020;
    //6 AL 12
    $numerosemana = 2;
    if ($numerosemana > 0 and $numerosemana < 54) {
        $numerosemana = $numerosemana;
        $primerdia = $numerosemana * 7 - 7;
        $ultimodia = $numerosemana * 7 - 1;
        $principioano = "$ano-01-01";
        $fecha1 = date('Y-m-d', strtotime("$principioano + $primerdia DAY"));
        $fecha2 = date('Y-m-d', strtotime ("$principioano + $ultimodia DAY"));
        if ($fecha2 <= date('Y-m-d', strtotime("$ano-12-31"))) {
            $fecha2 = $fecha2;
        } else {
            $fecha2 = date('Y-m-d',strtotime("$ano-12-31"));
        }
        echo '<br>la semana nº '.$numerosemana.' del año '.$ano.', va desde '.$fecha1.' hasta '.$fecha2.'</br>';
    } else {
        echo "este número de semana no es válido";
    }*/

    /*for ($numerosemana=1; $numerosemana < 54; $numerosemana++) { 
        if ($numerosemana > 0 and $numerosemana < 54) {
            $numerosemana = $numerosemana;
            $primerdia = $numerosemana * 7 - 7;
            $ultimodia = $numerosemana * 7 - 1;
            $principioano = "$ano-01-01";
            $fecha1 = date('Y-m-d', strtotime("$principioano + $primerdia DAY"));
            $fecha2 = date('Y-m-d', strtotime ("$principioano + $ultimodia DAY"));
            if ($fecha2 <= date('Y-m-d', strtotime("$ano-12-31"))) {
                $fecha2 = $fecha2;
            } else {
                $fecha2 = date('Y-m-d',strtotime("$ano-12-31"));
            }
            echo '<br>la semana nº '.$numerosemana.' del año '.$ano.', va desde '.$fecha1.' hasta '.$fecha2.'</br>';
        } else {
            echo "este número de semana no es válido";
        }
    }*/

    $year_current = date('Y');

    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
?>
    <div class="row">
        <div class="col-md-3 col-sm-12">
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
                        <option selected="" disabled>Seleccionar una semana</option>
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
        <div class="col-md-3 col-sm-12">
            <div class="form-check">
                <label class="form-check-label" for="slc_pla">Planta:</label>
                <select class="form-select" id="slc_pla">
                    <option selected disabled>Seleccionar la planta</option>
                    <option value="SEMANA TLALPAN">Tlalpan</option>
                    <option value="SEMANA TIZAYUCA">Tizayuca</option>
                    <option value="tln" disabled>Tlalnepantla</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <br>
            <button type="button" class="btn btn-outline-info" onclick="obtener_semana()">Buscar</button>
        </div>
    </div>
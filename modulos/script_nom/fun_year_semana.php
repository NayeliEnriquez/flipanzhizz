<?php
    include ('../../php/conn.php');
    $year = $_POST['year'];

    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
?>
<label class="form-check-label" for="slc_semana">Semanas:</label>
<select class="form-select" id="slc_semana" onchange="obtener_semana()">
    <option selected="" disabled>Seleccionar una semana</option>
<?php
    for ($i=1; $i < 54; $i++) { 
        $semanas = getFirstDayWeek($i, $year);
        echo '<option value='.$i.'>Semana: '.$i.': Del '.$semanas[start].' al '.$semanas[end].'</option>';
    }
?>
</select>
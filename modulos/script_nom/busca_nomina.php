<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $inp_num_empl = $_POST['inp_num_empl'];

    $sql_1 = "SELECT * FROM rh_employee_gen WHERE CLAVE = '$inp_num_empl'";
    $exe_1 = sqlsrv_query($cnx, $sql_1);
    $fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC);
    $full_name = $fila_1['NOMBRE COMPLETO'];
    $puesto = $fila_1['PUESTO'];
    $departamento = $fila_1['DEPARTAMENTO'];
    $type_nomina = $fila_1['NOMINA'];
    $verifica_type = strpos($type_nomina, 'SEMANA');
    $verifica_type = ($verifica_type !== false) ? 'S' : 'Q' ;
    
    echo '
        <input type="hidden" id="inp_id_empleado" value="'.$inp_num_empl.'">
        <input type="hidden" id="inp_id_type" value="'.$verifica_type.'">
        <br>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="inp_fname" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="inp_fname" value="'.$full_name.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_tnom" class="form-label">Tipo nomina</label>
                <input type="text" class="form-control" id="inp_tnom" value="'.$type_nomina.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_depto" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="inp_depto" value="'.$departamento.'" readonly>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="inp_pos" class="form-label">Posici&oacute;n</label>
                <input type="text" class="form-control" id="inp_pos" value="'.$puesto.'" readonly>
            </div>
        </div>
    ';

    
    switch ($verifica_type) {
        case 'S':
            $html_a = '
            <select class="form-select" id="slc_dias" onchange="busca_info_nomina()">
                <option value="" selected="" disabled="">Seleccionar una semana</option>
                <option value="1">Semana: 1: Del 2023-01-02 al 2023-01-08</option><option value="2">Semana: 2: Del 2023-01-09 al 2023-01-15</option><option value="3">Semana: 3: Del 2023-01-16 al 2023-01-22</option><option value="4">Semana: 4: Del 2023-01-23 al 2023-01-29</option><option value="5">Semana: 5: Del 2023-01-30 al 2023-02-05</option><option value="6">Semana: 6: Del 2023-02-06 al 2023-02-12</option><option value="7">Semana: 7: Del 2023-02-13 al 2023-02-19</option><option value="8">Semana: 8: Del 2023-02-20 al 2023-02-26</option><option value="9">Semana: 9: Del 2023-02-27 al 2023-03-05</option><option value="10">Semana: 10: Del 2023-03-06 al 2023-03-12</option><option value="11">Semana: 11: Del 2023-03-13 al 2023-03-19</option><option value="12">Semana: 12: Del 2023-03-20 al 2023-03-26</option><option value="13">Semana: 13: Del 2023-03-27 al 2023-04-02</option><option value="14">Semana: 14: Del 2023-04-03 al 2023-04-09</option><option value="15">Semana: 15: Del 2023-04-10 al 2023-04-16</option><option value="16">Semana: 16: Del 2023-04-17 al 2023-04-23</option><option value="17">Semana: 17: Del 2023-04-24 al 2023-04-30</option><option value="18">Semana: 18: Del 2023-05-01 al 2023-05-07</option><option value="19">Semana: 19: Del 2023-05-08 al 2023-05-14</option><option value="20">Semana: 20: Del 2023-05-15 al 2023-05-21</option><option value="21">Semana: 21: Del 2023-05-22 al 2023-05-28</option><option value="22">Semana: 22: Del 2023-05-29 al 2023-06-04</option><option value="23">Semana: 23: Del 2023-06-05 al 2023-06-11</option><option value="24">Semana: 24: Del 2023-06-12 al 2023-06-18</option><option value="25">Semana: 25: Del 2023-06-19 al 2023-06-25</option><option value="26">Semana: 26: Del 2023-06-26 al 2023-07-02</option><option value="27">Semana: 27: Del 2023-07-03 al 2023-07-09</option><option value="28">Semana: 28: Del 2023-07-10 al 2023-07-16</option><option value="29">Semana: 29: Del 2023-07-17 al 2023-07-23</option><option value="30">Semana: 30: Del 2023-07-24 al 2023-07-30</option><option value="31">Semana: 31: Del 2023-07-31 al 2023-08-06</option><option value="32">Semana: 32: Del 2023-08-07 al 2023-08-13</option><option value="33">Semana: 33: Del 2023-08-14 al 2023-08-20</option><option value="34">Semana: 34: Del 2023-08-21 al 2023-08-27</option><option value="35">Semana: 35: Del 2023-08-28 al 2023-09-03</option><option value="36">Semana: 36: Del 2023-09-04 al 2023-09-10</option><option value="37">Semana: 37: Del 2023-09-11 al 2023-09-17</option><option value="38">Semana: 38: Del 2023-09-18 al 2023-09-24</option><option value="39">Semana: 39: Del 2023-09-25 al 2023-10-01</option><option value="40">Semana: 40: Del 2023-10-02 al 2023-10-08</option><option value="41">Semana: 41: Del 2023-10-09 al 2023-10-15</option><option value="42">Semana: 42: Del 2023-10-16 al 2023-10-22</option><option value="43">Semana: 43: Del 2023-10-23 al 2023-10-29</option><option value="44">Semana: 44: Del 2023-10-30 al 2023-11-05</option><option value="45">Semana: 45: Del 2023-11-06 al 2023-11-12</option><option value="46">Semana: 46: Del 2023-11-13 al 2023-11-19</option><option value="47">Semana: 47: Del 2023-11-20 al 2023-11-26</option><option value="48">Semana: 48: Del 2023-11-27 al 2023-12-03</option><option value="49">Semana: 49: Del 2023-12-04 al 2023-12-10</option><option value="50">Semana: 50: Del 2023-12-11 al 2023-12-17</option><option value="51">Semana: 51: Del 2023-12-18 al 2023-12-24</option><option value="52">Semana: 52: Del 2023-12-25 al 2023-12-31</option><option value="53">Semana: 53: Del 2024-01-01 al 2024-01-07</option>
            </select>';
            break;
        
        case 'Q':
            $html_a = '
                <select class="form-select" id="slc_dias" onchange="busca_info_nomina()">
                    <option value="" selected="" disabled="">Seleccionar una quincena</option>
                    <option value="1">Quincena 1: Del 2023-01-01 al 2023-01-15</option>
                    <option value="2">Quincena 2: Del 2023-01-16 al 2023-01-31</option>
                    <option value="3">Quincena 3: Del 2023-02-01 al 2023-02-15</option>
                    <option value="4">Quincena 4: Del 2023-02-16 al 2023-02-28</option>
                    <option value="5">Quincena 5: Del 2023-03-01 al 2023-03-15</option>
                    <option value="6">Quincena 6: Del 2023-03-16 al 2023-03-31</option>
                    <option value="7">Quincena 7: Del 2023-04-01 al 2023-04-15</option>
                    <option value="8">Quincena 8: Del 2023-04-16 al 2023-04-30</option>
                    <option value="9">Quincena 9: Del 2023-05-01 al 2023-05-15</option>
                    <option value="10">Quincena 10: Del 2023-05-16 al 2023-05-31</option>
                    <option value="11">Quincena 11: Del 2023-06-01 al 2023-06-15</option>
                    <option value="12">Quincena 12: Del 2023-06-16 al 2023-06-30</option>
                    <option value="13">Quincena 13: Del 2023-07-01 al 2023-07-15</option>
                    <option value="14">Quincena 14: Del 2023-07-16 al 2023-07-31</option>
                    <option value="15">Quincena 15: Del 2023-08-01 al 2023-08-15</option>
                    <option value="16">Quincena 16: Del 2023-08-16 al 2023-08-31</option>
                    <option value="17">Quincena 17: Del 2023-09-01 al 2023-09-15</option>
                    <option value="18">Quincena 18: Del 2023-09-16 al 2023-09-30</option>
                    <option value="19">Quincena 19: Del 2023-10-01 al 2023-10-15</option>
                    <option value="20">Quincena 20: Del 2023-10-16 al 2023-10-31</option>
                    <option value="21">Quincena 21: Del 2023-11-01 al 2023-11-15</option>
                    <option value="22">Quincena 22: Del 2023-11-16 al 2023-11-30</option>
                    <option value="23">Quincena 23: Del 2023-12-01 al 2023-12-15</option>
                    <option value="24">Quincena 24: Del 2023-12-16 al 2023-12-31</option>
                </select>';
            break;

        
        default:
            # code...
            break;
    }
?>
<div class="col-md-12 col-lg-12">
    <h4 class="mb-3">Resumen del empleado</h4>
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <?php echo($html_a); ?>
        </div>
    </div>
</div>

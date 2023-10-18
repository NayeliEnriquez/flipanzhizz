<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Comentario</th>
            <th scope="col">Oficial</th>
            <th scope="col">Fecha comentario</th>
        </tr>
    </thead>
    <tbody>
<?php
    include ('../php/conn.php');
    date_default_timezone_set("America/Mexico_City");

    $id_bd = $_POST['id_bd'];
    $cont = 0;

    $query_check = "SELECT * FROM rh_vigilancia WHERE id_salida = '$id_bd'";
    $exe_check = sqlsrv_query($cnx, $query_check);
    $fila_check = sqlsrv_fetch_array($exe_check, SQLSRV_FETCH_ASSOC);
    if ($fila_check == null) {
        echo '
            <tr>
                <th scope="row" colspan="4"><center>Sin registros</center></th>
            </tr>
            <tr>
                <td scope="row" colspan="2">
                    <div class="mb-3">
                        <label for="inp_texto" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="inp_texto" rows="2"></textarea>
                    </div>
                </td>
                <td scope="row" colspan="2">
                    <div class="mb-3">
                        <label for="inp_name_v" class="form-label">Nombre de vigilante</label>
                        <input type="text" class="form-control" id="inp_name_v" autocomplete="on">
                    </div>
                </td>
            </tr>
        ';
    } else {
        $query_datos = "SELECT * FROM rh_vigilancia WHERE id_salida = '$id_bd'";
        $exe_datos = sqlsrv_query($cnx, $query_datos);
        while ($fila_datos = sqlsrv_fetch_array($exe_datos, SQLSRV_FETCH_ASSOC)) {
            $cont++;
            $id_vigilancia = $fila_datos['id'];
            $id_salida = $fila_datos['id_salida'];
            $texto_vigilancia = $fila_datos['texto_vigilancia'];
            $name_oficial = $fila_datos['name_oficial'];
            $fecha_insert = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_datos['fecha_insert']))));
            $fecha_insert = substr($fecha_insert, 5, 16);
            echo '
                <tr>
                    <th scope="row">'.$cont.'</th>
                    <td>'.$texto_vigilancia.'</td>
                    <td>'.$name_oficial.'</td>
                    <td>'.$fecha_insert.' horas</td>
                </tr>
            ';
        }
        echo '
            <tr>
                <td scope="row" colspan="2">
                    <div class="mb-3">
                        <label for="inp_texto" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="inp_texto" rows="2"></textarea>
                    </div>
                </td>
                <td scope="row" colspan="2">
                    <div class="mb-3">
                        <label for="inp_name_v" class="form-label">Nombre de vigilante</label>
                        <input type="text" class="form-control" id="inp_name_v" autocomplete="on">
                    </div>
                </td>
            </tr>
        ';
    }
?>
    </tbody>
</table>
<input type="hidden" name="inp_id_bd_s" id="inp_id_bd_s" value="<?php echo($id_bd); ?>">
<table class="table table-dark table-hover" id="tb_directivos">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Departamento</th>
            <th scope="col"># Empleado</th>
            <th scope="col">Nombre completo</th>
            <th scope="col">Posici&oacute;n</th>
        </tr>
    </thead>
    <tbody>
        <?php
            include ('../../php/conn.php');
            $sql_A = "SELECT * FROM rh_directivos";
            $exe_A = sqlsrv_query($cnx, $sql_A);
            while ($fila_A = sqlsrv_fetch_array($exe_A, SQLSRV_FETCH_ASSOC)) {
                $emp_code_a = $fila_A['CLAVE'];
                $full_name_a = utf8_encode($fila_A['NOMBRE COMPLETO']);
                $position_name_a = $fila_A['PUESTO'];
                $dept_name_a = $fila_A['DEPARTAMENTO'];
                $contador++;
                echo '
                    <tr>
                        <th scope="row">'.$contador.'</th>
                        <td><center>'.$dept_name_a.'</center></td>
                        <td><center>'.$emp_code_a.'</center></td>
                        <td><center>'.$full_name_a.'</center></td>
                        <td><center>'.$position_name_a.'</center></td>
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
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_directivos').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
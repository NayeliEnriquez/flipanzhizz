<table class="table table-dark table-hover" id="tb_users">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col"># Empleado</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
            include ('../../php/conn.php');
            $contador = 0;
            $query_a = "SELECT * FROM rh_user_sys";
            $exe_a = sqlsrv_query($cnx, $query_a);
            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                $id_a = $fila_a['id'];
                $name_a = $fila_a['name'];
                $email_a = $fila_a['email'];
                $num_empleado_a = $fila_a['num_empleado'];
                $contador++;
                echo '
                    <tr>
                        <th scope="row">'.$contador.'</th>
                        <td>'.$name_a.'</td>
                        <td>'.$email_a.'</td>
                        <td>'.$num_empleado_a.'</td>
                        <td><center><button type="button" class="btn btn-outline-info btn-sm" onclick="ver_usuario('.$id_a.')" data-bs-toggle="modal" data-bs-target="#Mdl_ver_user">Ver info</button></center></td>
                    </tr>
                ';
            }
        ?>
    </tbody>
</table>

<div class="modal fade" id="Mdl_ver_user" tabindex="-1" aria-labelledby="Mdl_ver_userLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Mdl_ver_userLabel">Ver usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response_ver_usuario"></div>
            </div>
            <div class="modal-footer">
                <div id="response_nuser"></div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm" id="btn_act3" onclick="actions_usuario(3)">Eliminar</button>
                <button type="button" class="btn btn-warning btn-sm" id="btn_act2" onclick="actions_usuario(2)">Reestablecer contrase&ntilde;a</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_act1" onclick="actions_usuario(1)">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_users').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
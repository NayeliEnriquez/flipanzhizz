<table class="table table-dark table-hover align-middle" id="tb_deptos">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Departamento</th>
            <!--<th scope="col">Departamento principal</th>-->
            <th scope="col">Encargado</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
            include ('../../php/conn.php');
            $contador = 0;
            $query_a = "SELECT * FROM personnel_department";
            $exe_a = sqlsrv_query($cnx, $query_a);
            while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
                $id_a = $fila_a['id'];
                $dept_code_a = $fila_a['dept_code'];
                $dept_name_a = $fila_a['dept_name'];
                $parent_dept_id_a = $fila_a['parent_dept_id'];
                if (empty($parent_dept_id_a)) {
                    $v_pdi_desc = 'No aplica';
                }else{
                    $query_b = "SELECT dept_name FROM personnel_department WHERE id = '$parent_dept_id_a'";
                    $exe_b = sqlsrv_query($cnx, $query_b);
                    $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
                    $v_pdi_desc = $fila_b['dept_name'];
                }
                $emp_code_charge_a = $fila_a['emp_code_charge'];
                if (empty($emp_code_charge_a)) {
                    $v_ecc_desc = "No info";
                }else{
                    $query_c = "SELECT first_name, last_name, emp_code FROM personnel_employee WHERE emp_code LIKE '$emp_code_charge_a'";
                    $exe_c = sqlsrv_query($cnx, $query_c);
                    $fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC);
                    $first_name_c = $fila_c['first_name'];
                    $last_name_c = $fila_c['last_name'];
                    $v_ecc_desc = $first_name_c." ".$last_name_c;
                }
                
                $sub_emp_code_charge_a = $fila_a['sub_emp_code_charge'];
                $contador++;
                echo '
                    <tr>
                        <th scope="row">'.$contador.'</th>
                        <td><center>'.utf8_encode($dept_name_a).'</center></td>
                        <!--<td>'.utf8_encode($v_pdi_desc).'</td>-->
                        <td><center>'.$v_ecc_desc.'</center></td>
                        <td><center><button type="button" class="btn btn-outline-info btn-sm" onclick="ver_depto('.$id_a.')" data-bs-toggle="modal" data-bs-target="#Mdl_ver_depto">Ver info</button></center></td>
                    </tr>
                ';
            }
        ?>
    </tbody>
</table>

<div class="modal fade" id="Mdl_ver_depto" tabindex="-1" aria-labelledby="Mdl_ver_deptoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="Mdl_ver_deptoLabel">Detalles</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response_ver_depto"></div>
            </div>
            <div class="modal-footer">
                <div id="response_nuser"></div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_act1" onclick="actions_depto(1)">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_deptos').DataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
    });
</script>
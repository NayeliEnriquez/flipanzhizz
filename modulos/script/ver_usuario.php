<?php
    include ('../../php/conn.php');
    $id_user = $_POST['id_user'];
    $query_a = "SELECT * FROM rh_user_sys WHERE id = '$id_user'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $name_a = $fila_a['name'];
    $email_a = $fila_a['email'];
    $num_empleado_a = $fila_a['num_empleado'];
    $permisos_a = $fila_a['permisos'];
    $p1 = (strpos($permisos_a, "1") !== false) ? "checked" : "" ;
    $p2 = (strpos($permisos_a, "2") !== false) ? "checked" : "" ;
    $p3 = (strpos($permisos_a, "3") !== false) ? "checked" : "" ;
    $p4 = (strpos($permisos_a, "4") !== false) ? "checked" : "" ;
    $p5 = (strpos($permisos_a, "5") !== false) ? "checked" : "" ;
    $p6 = (strpos($permisos_a, "6") !== false) ? "checked" : "" ;
    $p7 = (strpos($permisos_a, "7") !== false) ? "checked" : "" ;
?>

<div class="mb-3">
    <input type="hidden" id="id_user" value="<?php echo $id_user; ?>">
    <label for="inp_namefull" class="form-label">Empleado</label>
    <input class="form-control" type="text" id="inp_namefull" value="<?php echo $num_empleado_a." - ".$name_a; ?>" readonly>
</div>
<div class="mb-3">
    <label for="inp_email" class="form-label">Correo electr&oacute;nico</label>
    <input type="email" class="form-control" id="inp_email" value="<?php echo $email_a; ?>" readonly>
</div>
<div class="row">
    <center><label class="form-label">Permisos</label></center>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p1_u" <?php echo $p1; ?>>
            <label class="form-check-label" for="chk_p1_u">Sistema</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p2_u" <?php echo $p2; ?>>
            <label class="form-check-label" for="chk_p2_u">Personal</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p3_u" <?php echo $p3; ?>>
            <label class="form-check-label" for="chk_p3_u">Solicitudes</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p4_u" <?php echo $p4; ?>>
            <label class="form-check-label" for="chk_p4_u">Reportes</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p5_u" <?php echo $p5; ?>>
            <label class="form-check-label" for="chk_p5_u">Nomina</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p6_u" <?php echo $p6; ?>>
            <label class="form-check-label" for="chk_p6_u">Supervisor de producci&oacute;n</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="chk_p7_u" <?php echo $p7; ?>>
            <label class="form-check-label" for="chk_p7_u">Incapacidades</label>
        </div>
    </div>
</div>
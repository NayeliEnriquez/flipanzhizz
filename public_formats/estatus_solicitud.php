<?php
    include ('../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $yearr = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Formatos</title>
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../js/formatos.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <body class="text-center">
        <?php
            $name_self = basename($_SERVER['PHP_SELF']);
            include('../php/navs/nav_forms.php');
        ?>
        <div class="container">
            <br><br><br>
            <div class="col-md-12 col-lg-12">
                <h4 class="mb-3">Estatus de solicitudes</h4>
                <!--<form class="needs-validation" novalidate onsubmit="frm_solicitud_sts()" action="javascript:void(0);">-->
                <form class="needs-validation" novalidate onsubmit="revisa_pin_sub(document.getElementById('inp_num_empl_h').value, 'frm_solicitud_sts')" action="javascript:void(0);">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="slc_formato" class="form-label">Formato de solicitud</label>
                            <select class="form-select" id="slc_formato" required>
                                <option value="" selected disabled>Seleccione una opci&oacute;n...</option>
                                <option value="rh_solicitudes">Formato ausencia</option>
                                <option value="rh_salida">Formato Entrada/Salida</option>
                                <option value="rh_vacaciones">Formato vacaciones</option>
                            </select>
                            <div class="invalid-feedback">
                                Seleccione el formato de busqueda.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inp_folio" class="form-label">Folio</label>
                            <input type="number" class="form-control" id="inp_folio" placeholder="Folio" min="1" autocomplete="off">
                            <div class="invalid-feedback">
                                Coloque su folio.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inp_num_empl_h" class="form-label">Numero de empleado</label>
                            <input class="form-control" list="datalistOptions" id="inp_num_empl_h" placeholder="Busqueda..." required>
                            <datalist id="datalistOptions">
                                <?php
                                    $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                    $exe_1 = sqlsrv_query($cnx, $query1);
                                    while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                        $emp_code_1 = $fila_1['emp_code'];
                                        $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                        $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                        echo '
                                            <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                        ';
                                    }
                                ?>
                            </datalist>
                            <div class="invalid-feedback">
                                Coloque su numero de empleado.
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <button class="w-100 btn btn-primary btn-lg" type="submit">Buscar</button>
                </form>
                <hr class="my-4">
                <div id="response_frm_sol_sts"></div>
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/form-validation.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </body>
</html>
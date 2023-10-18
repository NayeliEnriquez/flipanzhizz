<?php
    $permisos_session = $_SESSION['permisos_a'];
    
    $p1_session = strpos($permisos_session, "1");
    if ($p1_session !== false) {
        $p1_html = 'href="sistema.php"';
    }else{
        $p1_html = 'href="#" onclick="no_access(); return false;"';
    }

    $p2_session = strpos($permisos_session, "2");
    if ($p2_session !== false) {
        $p2_html = 'href="personal.php"';
    }else{
        $p2_html = 'href="#" onclick="no_access(); return false;"';
    }

    $p3_session = strpos($permisos_session, "3");
    if ($p3_session !== false) {
        $p3_html = 'href="solicitudes.php"';
    }else{
        $p3_html = 'href="#" onclick="no_access(); return false;"';
    }

    $p4_session = strpos($permisos_session, "4");
    if ($p4_session !== false) {
        $p4_html = 'href="reportes.php"';
    }else{
        $p4_html = 'href="#" onclick="no_access(); return false;"';
    }

    $p5_session = strpos($permisos_session, "5");
    if ($p5_session !== false) {
        $p5_html = 'href="nomina.php"';
    }else{
        $p5_html = 'href="#" onclick="no_access(); return false;"';
    }

    $p6_session = strpos($permisos_session, "6");
    if ($p6_session !== false) {
        $p6_html = 'href="sup_check.php"';
    }else{
        $p6_html = 'href="#" onclick="no_access(); return false;"';
    }
    
    $p7_session = strpos($permisos_session, "7");
    if ($p7_session !== false) {
        $p7_html = 'href="incapacidades.php"';
    }else{
        $p7_html = 'href="#" onclick="no_access(); return false;"';
    }

    $nav_a = "nav-link";
    $nav_b = "nav-link";
    $nav_c = "nav-link";
    $nav_d = "nav-link";
    $nav_e = "nav-link";
    $nav_f = "nav-link";
    $nav_g = "nav-link";
    $nav_h = "nav-link";

    switch ($name_self) {
        case 'principal.php':
            $nav_a = "nav-link active";
            break;
        
        case 'sistema.php':
            $nav_b = "nav-link active";
            break;

        case 'personal.php':
            $nav_c = "nav-link active";
            break;

        case 'solicitudes.php':
            $nav_d = "nav-link active";
            break;

        case 'reportes.php':
            $nav_e = "nav-link active";
            break;

        case 'nomina.php':
            $nav_f = "nav-link active";
            break;

        case 'sup_check.php':
            $nav_g = "nav-link active";
            break;

        case 'incapacidades.php':
            $nav_h = "nav-link active";
            break;
    }
?>

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="<?php echo($nav_a); ?>" aria-current="page" href="principal.php">
                    <span data-feather="home" class="align-text-bottom"></span>
                    Principal
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_b); ?>" <?php echo($p1_html); ?>>
                    <span data-feather="file" class="align-text-bottom"></span>
                    Sistema
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_c); ?>" <?php echo($p2_html); ?>>
                    <span data-feather="shopping-cart" class="align-text-bottom"></span>
                    Personal
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_d); ?>" <?php echo($p3_html); ?>>
                    <span data-feather="users" class="align-text-bottom"></span>
                    Solicitudes
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_e); ?>" <?php echo($p4_html); ?>>
                    <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                    Reportes
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_f); ?>" <?php echo($p5_html); ?>>
                    <span data-feather="layers" class="align-text-bottom"></span>
                    Nomina
                </a>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_g); ?>" <?php echo($p6_html); ?>>
                    <span data-feather="layers" class="align-text-bottom"></span>
                    Supervisor de producci&oacute;n
                </a>
            </li>
            <li class="nav-item">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Formatos
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../public_formats/form_ausencia.php" target="_blank">Formato de ausencia</a></li>
                    <li><a class="dropdown-item" href="../public_formats/form_vacaciones.php" target="_blank">Formato de vacaciones</a></li>
                    <li><a class="dropdown-item" href="../public_formats/salida.php" target="_blank">Formato de Entrada/Salida</a></li>
                    <li><a class="dropdown-item" href="../public_formats/estatus_solicitud.php" target="_blank">Estatus de formatos</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="<?php echo($nav_h); ?>" <?php echo($p7_html); ?>>
                    <span data-feather="layers" class="align-text-bottom"></span>
                    Incapacidades
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../images/video/desarrollo_de_RH.mp4" target="_blank">
                    <span data-feather="layers" class="align-text-bottom"></span>
                    Ayuda
                </a>
            </li>
        </ul>
        <!--<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
            <span>Saved reports</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
                <span data-feather="plus-circle" class="align-text-bottom"></span>
            </a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Current month
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Last quarter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Social engagement
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text" class="align-text-bottom"></span>
                    Year-end sale
                </a>
            </li>
        </ul>-->
    </div>
</nav>
<script>
    function no_access() {
        alertify.error('No tiene permisos para esta parte del sistema, contacte a su administrador de sistema.')
    }
</script>
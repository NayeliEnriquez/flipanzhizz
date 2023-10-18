<?php
    $nav_a = 'class="nav-link"';
    $nav_b = 'class="nav-link"';
    $nav_c = 'class="nav-link"';
    $nav_d = 'class="nav-link"';
    $nav_e = 'class="nav-link"';
    $nav_f = 'class="nav-link"';
    switch ($name_self) {
        case 'index.php':
            $nav_a = 'class="nav-link active" aria-current="page"';
            break;
        
        case 'form_ausencia.php':
            $nav_b = 'class="nav-link active" aria-current="page"';
            break;

        case 'form_vacaciones.php':
            $nav_c = 'class="nav-link active" aria-current="page"';
            break;

        case 'salida.php':
            $nav_d = 'class="nav-link active" aria-current="page"';
            break;

        case 't_extra.php':
            $nav_e = 'class="nav-link active" aria-current="page"';
            break;
    }
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #757575;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="../images/novag_logo_small.png" width="65" height="35"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a <?php echo($nav_a); ?> href="../">Login</a>
                </li>
                <li class="nav-item">
                    <a <?php echo($nav_b); ?> href="form_ausencia.php">Formato Ausencia</a>
                </li>
                <li class="nav-item">
                    <a <?php echo($nav_c); ?> href="form_vacaciones.php">Formato Vacaciones</a>
                </li>
                <li class="nav-item">
                    <a <?php echo($nav_d); ?> href="salida.php">Formato Entrada/Salida</a>
                </li>
                <li class="nav-item">
                    <a <?php echo($nav_e); ?> href="estatus_solicitud.php">Estatus solicitud</a>
                </li>
            </ul>
            <!--<form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>-->
        </div>
    </div>
</nav>
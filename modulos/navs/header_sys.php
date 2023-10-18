<?php
    session_start();
    $name_session = $_SESSION['name_a'];

    if (empty($name_session)) {
        session_destroy();
        header('Location: ../../?mensaje=2');
    }
?>
<header class="navbar sticky-top flex-md-nowrap p-0 shadow" style="background-color: #7A7A7A;">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="https://novag.mx/" target="_blank"><img src="../images/novag_logo_small.png" width="100" height="35"></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search" value="<?php echo($name_session); ?>" readonly>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="navs/exit.php">Salir <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></a>
        </div>
    </div>
</header>
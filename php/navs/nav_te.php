<?php
    $nav_a = 'class="nav-link"';
    switch ($name_self) {
        case 'index.php':
            $nav_a = 'class="nav-link active" aria-current="page"';
            break;
    }
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #757575;">
    <div class="container-fluid">
        <a class="navbar-brand" href="../"><img src="../images/novag_logo_small.png" width="85" height="40"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a <?php echo($nav_a); ?> href="../te_produccion">Registro TE</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
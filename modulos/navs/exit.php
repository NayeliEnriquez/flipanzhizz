<?php
    session_start();
    while($session = each($_SESSION)){
        unset($session[0]);
	}
    session_destroy();
    header("Location: ../../");
    exit();
?>
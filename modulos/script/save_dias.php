<?php
    /*while($post = each($_POST)){
		echo $post[0]." = ".$post[1]."||";
	}*/
    date_default_timezone_set("America/Mazatlan");
    session_start();
    include ('../../php/conn.php');
    $name_a_session = $_SESSION['name_a'];
    $num_empleado_a_session = $_SESSION['num_empleado_a'];

    $inp_id_empleado_rh = $_POST['inp_id_empleado_rh'];
    $year_ciclo_a = $_POST['year_ciclo_a'];
    $ciclo_a = $_POST['ciclo_a'];
    $year_ciclo_b = $_POST['year_ciclo_b'];
    $ciclo_b = $_POST['ciclo_b'];

    $sql_update = "UPDATE rh_employee_gen SET y_$year_ciclo_a = '$ciclo_a', y_$year_ciclo_b = '$ciclo_b' WHERE CLAVE = '$inp_id_empleado_rh'";

    if (sqlsrv_query($cnx, $sql_update)) {
        echo 1;
    } else {
        echo 2;
    }
?>
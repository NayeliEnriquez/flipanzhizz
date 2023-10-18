<?php
    /*while($post = each($_POST)){
		echo $post[0]."=".$post[1]."||";
	}*/
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    include ('conn.php');
    $id_login = $_POST['id_login'];
    $pass_new = $_POST['pass_new'];

    $query_a = "UPDATE rh_user_sys SET rh_user_sys.password = '$pass_new', updated_at = '$fecha_hora_now' WHERE id = '$id_login'";
    if (sqlsrv_query($cnx, $query_a)) {
        echo 1;
    }else{
        echo 2;
    }
?>
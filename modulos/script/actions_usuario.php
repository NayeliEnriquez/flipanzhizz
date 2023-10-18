<?php
    include ('../../php/conn.php');
    $tipo_mov = $_POST['tipo_mov'];
    $id_user = $_POST['id_user'];
    $str_permisos = '';
    switch ($tipo_mov) {
        case '1'://***Actualizar
            $chk_p1 = $_POST['chk_p1_u'];
            $chk_p2 = $_POST['chk_p2_u'];
            $chk_p3 = $_POST['chk_p3_u'];
            $chk_p4 = $_POST['chk_p4_u'];
            $chk_p5 = $_POST['chk_p5_u'];
            $chk_p6 = $_POST['chk_p6_u'];
            $chk_p7 = $_POST['chk_p7_u'];
            
            $str_permisos .= ($chk_p1 == 'true') ? "1|" : "";
            $str_permisos .= ($chk_p2 == 'true') ? "2|" : "";
            $str_permisos .= ($chk_p3 == 'true') ? "3|" : "";
            $str_permisos .= ($chk_p4 == 'true') ? "4|" : "";
            $str_permisos .= ($chk_p5 == 'true') ? "5|" : "";
            $str_permisos .= ($chk_p6 == 'true') ? "6|" : "";
            $str_permisos .= ($chk_p7 == 'true') ? "7|" : "";

            $query_a = "UPDATE rh_user_sys SET permisos = '$str_permisos' WHERE id = '$id_user'";
            if (sqlsrv_query($cnx, $query_a)) {
                echo "1";
            }else{
                echo "0";
            }
            break;
        
        case '2':
            $query_a = "UPDATE rh_user_sys SET rh_user_sys.password = 'abc123', updated_at = NULL WHERE id = '$id_user'";
            if (sqlsrv_query($cnx, $query_a)) {
                echo "1";
            }else{
                echo "0";
            }
            break;

        case '3':
            $query_a = "DELETE FROM rh_user_sys WHERE id = '$id_user'";
            if (sqlsrv_query($cnx, $query_a)) {
                echo "1";
            }else{
                echo "0";
            }
            break;
    }
?>
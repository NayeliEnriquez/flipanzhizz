<?php
    date_default_timezone_set("America/Mazatlan");
    include ('../../php/conn.php');
    $inp_id_type = $_POST['inp_id_type'];
    $slc_dias = $_POST['slc_dias'];
    $inp_id_empleado = $_POST['inp_id_empleado'];


    //***
    function saber_dia($nombredia) {
        $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    switch ($inp_id_type) {
        case 'S':
            # code...
            break;
        
        case 'Q':
            switch ($slc_dias) {
                case '1':
                    $f_recorre = "2023-01";
                    for ($i=1; $i < 16; $i++) { 
                        $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$inp_id_empleado' AND fecha LIKE '".$f_recorre."-".$i."' ORDER BY fecha DESC";
                        $exe_revisa = sqlsrv_query($conn, $query_revisa);
                        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
                        echo "<br>".var_dump($fila_c);
                        /*if ($fila_revisa != NULL) {

                        }*/
                    }
                    break;
                
                case '2':
                    # code...
                    break;

                case '3':
                    # code...
                    break;

                case '4':
                    # code...
                    break;

                case '5':
                    # code...
                    break;
            }
            break;

        default:
            # code...
            break;
    }
?>
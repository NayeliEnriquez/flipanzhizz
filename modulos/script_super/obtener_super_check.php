<?php
    error_reporting(E_ALL);

    session_start();
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."<br>";
	}*/
    $num_empleado_session = $_SESSION['num_empleado_a'];

    ini_set('max_execution_time', 0);
    date_default_timezone_set("America/Mazatlan");

    include ('../../php/conn.php');
    
    function getFirstDayWeek($week, $year){
        $dt = new DateTime();
        $return['start'] = $dt->setISODate($year, $week)->format('Y-m-d');
        $return['end'] = $dt->modify('+6 days')->format('Y-m-d');
        return $return;
    }
    //***
    function saber_dia($nombredia) {
        $dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $tipo_nomina = '';
    $slc_year = $_POST['slc_year'];
    $slc_semana = $_POST['slc_semana'];
    $slc_quincena = $_POST['slc_quincena'];
    $slc_areas_p_x = $_POST['slc_areas_p'];
    switch ($slc_areas_p_x) {
        case 'Tabletas':
            //$slc_areas_p = "'7474', '7597', '6242', '6874', '7563', '7464', '6656', '6862', '7416', '7150', '7443', '7457', '6455', '6114', '6744', '6948', '7352', '7483', '6572', '5768', '7419', '6785', '6616', '7472', '7400', '7320', '7243', '7358', '7323', '7403', '6477', '6795', '7547', '7431', '7609', '6960', '7621', '7622', '7623', '7631', '7635'";
            $slc_areas_p = "";
            $query_xx = "SELECT [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_areas_p_x'";
            $exe_xx = sqlsrv_query($cnx, $query_xx);
            while ($fila_xx = sqlsrv_fetch_array($exe_xx, SQLSRV_FETCH_ASSOC)) {
               $num_emp_xx = $fila_xx['num_emp'];
               $slc_areas_p .= "'".$num_emp_xx."', ";
            }

            $slc_areas_p = substr($slc_areas_p, 0, -2);
            break;
        
        case 'Fabricacion':
            //$slc_areas_p = "'7275', '7268', '6829', '7298', '7366', '5505', '6221', '7485', '7269', '7450', '6116', '7220', '6825', '7321', '7223', '6783', '7261', '7282', '7238', '6341', '6614', '7351', '6642', '7517', '7489', '7437', '7254', '6757', '6172', '7277', '5275', '6468', '7479', '7520', '7121', '7523', '7448', '6900', '7371', '5974', '7515', '7373', '7411', '7521', '6297', '7562', '7591', '7579', '7515', '6962', '6696', '6961', '7583', '7469', '7318', '7231', '7555', '6919', '6731', '7510'";
            $slc_areas_p = "";
            $query_xx = "SELECT [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_areas_p_x'";
            $exe_xx = sqlsrv_query($cnx, $query_xx);
            while ($fila_xx = sqlsrv_fetch_array($exe_xx, SQLSRV_FETCH_ASSOC)) {
               $num_emp_xx = $fila_xx['num_emp'];
               $slc_areas_p .= "'".$num_emp_xx."', ";
            }

            $slc_areas_p = substr($slc_areas_p, 0, -2);
            break;

        case 'Acondicionado':
            //$slc_areas_p = "'5818', '7484', '7363', '7461', '6734', '6560', '7136', '6852', '7513', '7266', '6201', '5834', '6520', '7365', '6021', '6355', '7303', '6751', '6974', '7152', '7399', '6633', '6990', '4947', '7300', '1238', '7308', '7146', '7426', '7380', '7427', '7293', '7211', '7343', '7142', '7183', '6964', '6231', '5663', '5020', '6882', '5009', '7182', '5822', '7118', '7524', '5356', '5625', '7441', '7480', '7491', '6022', '5787', '5841', '5709', '6071', '7290', '5614', '6764', '7496', '5946', '6996', '7349', '6323', '7227', '5036', '5026', '7487', '6649', '6227', '6980', '7511', '7347', '7315', '5772', '6959', '6491', '7237', '7333', '6701', '6236', '6431', '7309', '7316', '5859', '7404', '7453', '7388', '7224', '6512', '7156', '7468', '6057', '7304', '6817', '6077', '7504', '5055', '6526', '6384', '7518', '7414', '6198', '7311', '4770', '6963', '4017', '7122', '7355', '7492', '7470', '7143', '6894', '5110', '6294', '6389', '5494', '6968', '7381', '6466', '5529', '7376', '7109', '6539', '7325', '7499', '7302', '4945', '6653', '6306', '6903', '7172', '6792', '7447', '6255', '7284', '7288', '6925', '6317', '7459', '7370', '6190', '5986', '7134', '6506', '5342', '7460', '7604', '5291', '6397', '7336', '7327', '6672', '7500', '7402', '6590', '7467', '7244', '7222', '7589', '6362', '6815', '7602', '7531', '7149', '5917', '5994', '7208', '7590', '7556', '7408', '5939', '7592', '6791', '7429', '4897', '6228', '5334', '6626', '7557', '7495', '4670', '7242', '5060', '7114', '6239', '5536', '7558', '6835', '6398', '7108', '6951', '5981', '4244', '6692', '7446', '7169', '7561', '5470', '7534', '5436', '6271', '7294', '6183', '6166', '7161', '4019', '7334', '7285', '6451', '6494', '7542', '7536', '7601', '6763', '5865', '7599', '7532', '7455', '7477', '6543', '6790', '6553', '6419', '7494', '7574', '6892', '7543', '7501', '7525', '6299', '6139', '7575', '7545', '7507', '7538', '7559', '7560', '7603', '6709', '7516', '6875', '7503', '7350', '7607', '7549', '6879', '5602', '5631', '7386', '6940', '7600', '6920', '7608', '6967', '7535', '7539', '7125', '7576', '5746', '7565', '7632', '7636', '7628', '7546', '7629', '7630', '7634', '7606'";
            $slc_areas_p = "";
            $query_xx = "SELECT [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_areas_p_x'";
            $exe_xx = sqlsrv_query($cnx, $query_xx);
            while ($fila_xx = sqlsrv_fetch_array($exe_xx, SQLSRV_FETCH_ASSOC)) {
               $num_emp_xx = $fila_xx['num_emp'];
               $slc_areas_p .= "'".$num_emp_xx."', ";
            }

            $slc_areas_p = substr($slc_areas_p, 0, -2);
            break;
        
        case 'Liquidos':
            //$slc_areas_p = "'7142', '7281', '6279', '5778', '7454', '7517', '7382', '6226', '5686', '7271', '5063', '5523', '7406', '6926', '7475', '7137', '7465', '7466', '5763', '6652'";
            //$slc_areas_p = "'5974', '6172', '6116'";
            //$slc_areas_p = "'6172'";
            $slc_areas_p = "";
            $query_xx = "SELECT [num_emp] FROM [dbo].[rh_area_prod] WHERE area_prod LIKE '$slc_areas_p_x'";
            $exe_xx = sqlsrv_query($cnx, $query_xx);
            while ($fila_xx = sqlsrv_fetch_array($exe_xx, SQLSRV_FETCH_ASSOC)) {
               $num_emp_xx = $fila_xx['num_emp'];
               $slc_areas_p .= "'".$num_emp_xx."', ";
            }

            $slc_areas_p = substr($slc_areas_p, 0, -2);
            break;

        default:
            //$query_a = "SELECT dept_code, dept_name FROM personnel_department WHERE emp_code_charge = '$num_empleado_session'";
            if ($num_empleado_session == '6995') {//***Uso exclusivo de Norma Tellez
                $query_a = "SELECT id AS id_depto, dept_code, dept_name FROM personnel_department WHERE personnel_department.id = '1057'";
            } else {
                $sql_super = "SELECT COUNT(id) AS super_conf FROM rh_supervisores WHERE num_emp = '$num_empleado_session'";
                $exe_super = sqlsrv_query($cnx, $sql_super);
                $fila_super = sqlsrv_fetch_array($exe_super, SQLSRV_FETCH_ASSOC);
                $super_conf = $fila_super['super_conf'];
                if ($super_conf > 0) {
                    $sql_super_b = "SELECT [department_id] FROM [rh_novag_system].[dbo].[personnel_employee] WHERE emp_code = '$num_empleado_session' AND enable_att = '1'";
                    $exe_super_b = sqlsrv_query($conn, $sql_super_b);
                    $fila_super_b = sqlsrv_fetch_array($exe_super_b, SQLSRV_FETCH_ASSOC);
                    $department_id_super_b = $fila_super_b['department_id'];
                    $query_a = "SELECT id AS id_depto, dept_code, dept_name FROM personnel_department WHERE personnel_department.id = '$department_id_super_b'";
                }else{
                    $query_a = "SELECT id AS id_depto, dept_code, dept_name FROM personnel_department WHERE emp_code_charge = '$num_empleado_session' OR sub_emp_code_charge = '$num_empleado_session'";
                }
            }

            $exe_a = sqlsrv_query($cnx, $query_a);
            $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
            if (empty($num_empleado_session)) {
                //echo "<br>Sin session";
                echo "0||A";
                exit();
            }elseif ($fila_a == NULL) {
                //echo "<br>Sin depto asignado";
                //echo "0||B";
                //exit();
            }
            $dept_code_a = $fila_a['dept_code'];
            $dept_name_a = $fila_a['dept_name'];
            $id_depto_a = $fila_a['id_depto'];
            break;
    }
    $td_body = "";

    if (empty($slc_semana)) {
        switch ($slc_quincena) {
            case '1'://***<option value="1">Del 1ro al 15 de Enero</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 1;
                break;
            
            case '2'://***<option value="2">Del 16 al 31 de Enero</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 1;
                break;

            case '3'://***<option value="3">Del 1ro al 15 de Febrero</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 2;
                break;

            case '4'://***<option value="4">Del 16 al 28 de Febrero</option>
                $d_ini = 16;
                $d_fin = 28;
                $v_mes = 2;
                break;

            case '5'://***<option value="5">Del 1ro al 15 de Marzo</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 3;
                break;

            case '6'://***<option value="6">Del 16 al 31 de Marzo</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 3;
                break;

            case '7'://***<option value="7">Del 1ro al 15 de Abril</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 4;
                break;

            case '8'://***<option value="8">Del 16 al 30 de Abril</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 4;
                break;

            case '9'://***<option value="9">Del 1ro al 15 de Mayo</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 5;
                break;

            case '10'://***<option value="10">Del 16 al 31 de Mayo</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 5;
                break;

            case '11'://***<option value="11">Del 1ro al 15 de Junio</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 6;
                break;

            case '12'://***<option value="12">Del 16 al 30 de Junio</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 6;
                break;

            case '13'://***<option value="13">Del 1ro al 15 de Julio</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 7;
                break;

            case '14'://***<option value="14">Del 16 al 31 de Julio</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 7;
                break;

            case '15'://***<option value="15">Del 1ro al 15 de Agosto</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 8;
                break;

            case '16'://***<option value="16">Del 16 al 31 de Agosto</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 8;
                break;

            case '17'://***<option value="17">Del 1ro al 15 de Septiembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 9;
                break;

            case '18'://***<option value="18">Del 16 al 30 de Septiembre</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 9;
                break;

            case '19'://***<option value="19">Del 1ro al 15 de Octubre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 10;
                break;

            case '20'://***<option value="20">Del 16 al 31 de Octubre</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 10;
                break;

            case '21'://***<option value="21">Del 1ro al 15 de Noviembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 11;
                break;

            case '22'://***<option value="22">Del 16 al 30 de Noviembre</option>
                $d_ini = 16;
                $d_fin = 30;
                $v_mes = 11;
                break;

            case '23'://***<option value="23">Del 1ro al 15 de Diciembre</option>
                $d_ini = 1;
                $d_fin = 15;
                $v_mes = 12;
                break;

            case '24'://***<option value="24">Del 16 al 31 de Diciembre</option>
                $d_ini = 16;
                $d_fin = 31;
                $v_mes = 12;
                break;
        }

        $v_mes = ($v_mes < 10) ? '0'.$v_mes : $v_mes;

        //echo "d_ini: ".$d_ini." d_fin:".$d_fin;
        $f_start = strtotime($slc_year."-".$v_mes."-".$d_ini);
        $f_end = strtotime($slc_year."-".$v_mes."-".$d_fin);
        $tipo_nomina = '15';
        $haystack_nom = "QUINCE";
    }else{
        $semanas = getFirstDayWeek($slc_semana, $slc_year);
        $f_start = strtotime($semanas[start]);
        $f_end = strtotime($semanas[end]);
        $tipo_nomina = '7';
        $haystack_nom = "SEMA";
    }
    /*echo "<br>f_start: ".$f_start;
    echo "<br>f_end: ".$f_end;*/
    //exit();

    $str_emps = "";

    //***TIPO DE BUSQUEDA DE TIEMPOS EXTRA
    switch ($num_empleado_session) {
        case '3016'://***Uso especifico de Gerardo Cedillo
            $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ($slc_areas_p) AND enable_att = '1'";
            break;
        case '5698'://***Uso especifico para Norma Nelly
            $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ($slc_areas_p) AND enable_att = '1'";
            break;

        case '2127'://***Uso exclusivo de VIOLETA ROSALEN GOMEZ CASTILLA de MANTENIMIENTO QNA Y SEM TIZAYUCA
            $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE department_id IN ('33', '43')  AND enable_att = '1' ORDER BY emp_code ASC";
            break;

        case '3045':
        case '5731'://***Uso exclusivo de Damian y Nallely
            $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE department_id IN ('1057', '21')  AND enable_att = '1' ORDER BY emp_code ASC";
            break;
        
        default://***Busqueda general por departamento
            if ($super_conf > 0) {
                $emps_super = "";
                $query_b_super = "SELECT num_emp_asign FROM rh_supers_emps WHERE id_super = '$num_empleado_session'";
                $exe_b_super = sqlsrv_query($cnx, $query_b_super);
                while ($fila_b_super = sqlsrv_fetch_array($exe_b_super, SQLSRV_FETCH_ASSOC)) {
                    $emps_super .= "'".$fila_b_super[num_emp_asign]."', ";
                }
                $emps_super = substr($emps_super, 0, -2);
                $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ($emps_super)  AND enable_att = '1' ORDER BY emp_code ASC";
            }else{
                //$query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code != '$num_empleado_session' AND department_id = '$id_depto_a' ORDER BY emp_code ASC";
                $query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE department_id = '$id_depto_a'  AND enable_att = '1' ORDER BY emp_code ASC";
            }
            break;
    }
    
    //echo "<br>".$query_b;
    $exe_b = sqlsrv_query($conn, $query_b);
    while ($fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
        //echo "<br>****************************************";
        $v_lun = "";
        $v_mar = "";
        $v_mie = "";
        $v_jue = "";
        $v_vie = "";
        $v_sab = "";
        $v_dom = "";
        $emp_code_b = $fila_b['emp_code'];
        $first_name_b = utf8_encode($fila_b['first_name']);
        $last_name_b = utf8_encode($fila_b['last_name']);
        $z = 0;
        $hrs_norm_tot = 0;
        $min_norm_tot = 0;
        $hrs_ext_tot = 0;
        $min_ext_tot = 0;
        $hrs_new_tot = 0;
        $min_new_tot = 0;
        $aprobar_todo = 0;
        //echo "<br>i: ".$i." -> ".$emp_code_b;

        $revisa_nomina = "SELECT NOMINA FROM rh_employee_gen WHERE rh_employee_gen.CLAVE = '$emp_code_b'";
        $exe_nomina = sqlsrv_query($cnx, $revisa_nomina);
        $fila_nomina = sqlsrv_fetch_array($exe_nomina, SQLSRV_FETCH_ASSOC);
        $emp_nomina = $fila_nomina['NOMINA'];
        //echo "**Nomina**".$emp_nomina;
        
        $valida_emp_nomina = strpos($emp_nomina, $haystack_nom);
        if ($valida_emp_nomina !== false) {
            //echo "<strong>**APLICA**</strong>";
        }else{
            //echo "<strong>**NO APLICA**</strong>";
            continue;
        }
        
        if ($tipo_nomina == '15') {
            $td_body .= "
                <tr>
                    <td>".$emp_code_b."</td>
                    <td>".$last_name_b." ".$first_name_b."</td>
            ";
        }

        for ($i=$f_start; $i <= $f_end ; $i+=86400) { 
            $z++;
            //echo "<br>i: ".$i." -> ".$emp_code_b." --> ".$z;
            unset($punch_time_c);
            unset($fecha_c);
            unset($Hora_c);
            unset($terminal_alias_c);
            unset($terminal_alias_ext);
            unset($Hora_extra);
            unset($Hora_normal);
            $punch_time_c = array();
            $fecha_c = array();
            $Hora_c = array();
            $terminal_alias_c = array();
            $terminal_alias_ext = array();
            $terminal_alias_nor = array();
            $Hora_extra = array();
            $Hora_normal = array();
            //$Hora_normal[] = null;
            //$punch_time_c = null;
            //$fecha_c = null;
            //$Hora_c = null;
            //$terminal_alias_c = null;
            //$terminal_alias_ext[] = null;
            //$terminal_alias_nor[] = null;
            //$Hora_extra[] = null;

            //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***
            $query_shift_a = "SELECT id FROM [zkbiotime].[dbo].[personnel_employee] WHERE emp_code = '$emp_code_b' AND enable_att = '1'";
            $exe_shift_a = sqlsrv_query($conn, $query_shift_a);
            $fila_shift_a = sqlsrv_fetch_array($exe_shift_a, SQLSRV_FETCH_ASSOC);
            $id_bd_employee = $fila_shift_a['id'];
            $query_shift_b = "SELECT shift_id FROM [zkbiotime].[dbo].[att_attschedule] WHERE employee_id = '$id_bd_employee'";
            $exe_shift_b = sqlsrv_query($conn, $query_shift_b);
            $fila_shift_b = sqlsrv_fetch_array($exe_shift_b, SQLSRV_FETCH_ASSOC);
            $shift_id = $fila_shift_b['shift_id'];
            /*La funcion CAST solo obtiene el tipo de dato que necesites y se declara indicando TIME(HORAS), DATEADD se utiliza para agregar minutos horas o dias segun la funcion(MINUTE, DAY, HOUR)
            Ejemplos
            -- Restar 30 minutos a la fecha y hora actual 
            SELECT DATEADD(MINUTE, -30, GETDATE()) AS '30 minutos antes'
            GO
            -- Sumar 1 hora a la fecha y hora actual
            SELECT DATEADD(HOUR, 1, GETDATE()) AS '1 hora después'
            GO
            -- Restamos 1 día a la fecha actual
            SELECT DATEADD(DAY, -1, GETDATE()) AS '1 día antes'
            GO
            */

            $he_lun = '';   $hs_lun = '';   $tolerancia_lun = '';
            $he_mar = '';   $hs_mar = '';   $tolerancia_mar = '';
            $he_mie = '';   $hs_mie = '';   $tolerancia_mie = '';
            $he_jue = '';   $hs_jue = '';   $tolerancia_jue = '';
            $he_vie = '';   $hs_vie = '';   $tolerancia_vie = '';
            $he_sab = '';   $hs_sab = '';   $tolerancia_sab = '';
            $he_dom = '';   $hs_dom = '';   $tolerancia_dom = '';
            $query_shift_c = "SELECT
                    CAST(att_s.in_time AS time)[hora_entrada], 
                    att_s.day_index, att_t.in_above_margin, att_t.id, att_t.duration, att_t.alias,
                    CAST(DATEADD(MINUTE, +att_t.duration, CAST(att_s.in_time AS time)) AS time) AS [hora_salida]
                FROM
                    [zkbiotime].[dbo].[att_shiftdetail] AS att_s
                INNER JOIN
                    [zkbiotime].[dbo].[att_timeinterval] AS att_t
                ON
                    att_s.time_interval_id = att_t.id
                WHERE
                    att_s.shift_id = '$shift_id'
                ORDER BY
                    att_s.day_index ASC";
            $exe_shift_c = sqlsrv_query($conn, $query_shift_c);
            while ($fila_shift_c = sqlsrv_fetch_array($exe_shift_c, SQLSRV_FETCH_ASSOC)) {
                $day_index = $fila_shift_c['day_index'];
                $id_horario = $fila_shift_c['id'];
                $alias_hr = $fila_shift_c['alias'];
                $hora_entrada = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_shift_c['hora_entrada']))));
                $hora_entrada = substr($hora_entrada, 16, 5);
                $hora_salida = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_shift_c['hora_salida']))));
                $hora_salida = substr($hora_salida, 16, 5);
                $tolerancia_min = $fila_shift_c['in_above_margin'];
                switch ($day_index) {
                    case '0':
                        if ($id_horario == '2') {
                            $he_dom = 'Descanso';   $hs_dom = 'Descanso';   $tolerancia_dom = 'Descanso';
                        } else {
                            $he_dom = $hora_entrada;   $hs_dom = $hora_salida;   $tolerancia_dom = $tolerancia_min;
                        }
                        break;
                    
                    case '1':
                        if ($id_horario == '2') {
                            $he_lun = 'Descanso';   $hs_lun = 'Descanso';   $tolerancia_lun = 'Descanso';
                        } else {
                            $he_lun = $hora_entrada;   $hs_lun = $hora_salida;   $tolerancia_lun = $tolerancia_min;
                        }
                        break;

                    case '2':
                        if ($id_horario == '2') {
                            $he_mar = 'Descanso';   $hs_mar = 'Descanso';   $tolerancia_mar = 'Descanso';
                        } else {
                            $he_mar = $hora_entrada;   $hs_mar = $hora_salida;   $tolerancia_mar = $tolerancia_min;
                        }
                        break;

                    case '3':
                        if ($id_horario == '2') {
                            $he_mie = 'Descanso';   $hs_mie = 'Descanso';   $tolerancia_mie = 'Descanso';
                        } else {
                            $he_mie = $hora_entrada;   $hs_mie = $hora_salida;   $tolerancia_mie = $tolerancia_min;
                        }
                        break;

                    case '4':
                        if ($id_horario == '2') {
                            $he_jue = 'Descanso';   $hs_jue = 'Descanso';   $tolerancia_jue = 'Descanso';
                        } else {
                            $he_jue = $hora_entrada;   $hs_jue = $hora_salida;   $tolerancia_jue = $tolerancia_min;
                        }
                        break;

                    case '5':
                        if ($id_horario == '2') {
                            $he_vie = 'Descanso';   $hs_vie = 'Descanso';   $tolerancia_vie = 'Descanso';
                        } else {
                            $he_vie = $hora_entrada;   $hs_vie = $hora_salida;   $tolerancia_vie = $tolerancia_min;
                        }
                        break;

                    case '6':
                        if ($id_horario == '2') {
                            $he_sab = 'Descanso';   $hs_sab = 'Descanso';   $tolerancia_sab = 'Descanso';
                        } else {
                            $he_sab = $hora_entrada;   $hs_sab = $hora_salida;   $tolerancia_sab = $tolerancia_min;
                        }
                        break;
                    
                    default:
                        $he_sab = 'ERROR';   $hs_sab = 'ERROR';   $tolerancia_sab = 'ERROR';
                        break;
                }
            }
            //***OBTENER EL HORARIO EN CURSO DEL EMPLEADO***

            //$f_recorre = ($z == 7) ? date("Y-m-d", $f_end) : date("Y-m-d", $i) ;
            $f_recorre = date("Y-m-d", $i);
            $dia_dia = saber_dia($f_recorre);
            /*echo "<br>f_recorre: ".$f_recorre;
            echo "<br>dia_dia: ".$dia_dia;
            continue;*/
            $query_revisa = "SELECT emp_code FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code_b' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
            $exe_revisa = sqlsrv_query($conn, $query_revisa);
            $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
            //echo "<br>".var_dump($fila_c);
            if ($fila_revisa != NULL) {
                if ((strtotime($he_lun)) > (strtotime($hs_lun))) {//***TURNO NOCTURNO
                    $turno = "noche";
                    //echo "entra en la noche";
                    $menos_un_dia = date("Y-m-d",strtotime($f_recorre."- 1 days")); 
                    //$dia_dia = saber_dia($menos_un_dia);
                    //echo "<br>Menos un dia: ".$menos_un_dia;
                    //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                    //$query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code_b' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
                    $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '".$emp_code_b."' AND punch_time > '".$menos_un_dia." 12:00' AND punch_time < '".$f_recorre." 12:00' ORDER BY fecha ASC";
                    $exe_c = sqlsrv_query($conn, $query_c);
                    while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                        $punch_time_c[] = $fila_c['punch_time'];
                        $fecha_c[] = $fila_c['fecha'];
                        $Hora_c[] = $fila_c['Hora'];
                        $terminal_alias_c[] = $fila_c['terminal_alias'];
                        $val_term = strpos($fila_c['terminal_alias'], "TE ");//Buscamos la palabra TE que se refiere a una terminal de Tiempo Extra
                        if ($val_term !== false) {
                            $terminal_alias_ext[] = $fila_c['terminal_alias'];
                            $Hora_extra[] = $fila_c['Hora'];
                        } else {
                            $val_term = strpos($fila_c['terminal_alias'], "TIEMPO EXTRA");
                            if ($val_term !== false) {
                                $terminal_alias_ext[] = $fila_c['terminal_alias'];
                                $Hora_extra[] = $fila_c['Hora'];
                            } else {
                                $terminal_alias_nor[] = $fila_c['terminal_alias'];
                                $Hora_normal[] = $fila_c['Hora'];
                            }
                        }
                    }
                    //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                }else{//***TURNO DIA
                    $turno = "dia";
                    //echo "entra en la mañana";
                    //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                    $query_c = "SELECT * FROM [zkbiotime].[dbo].[Push_z] WHERE emp_code = '$emp_code_b' AND fecha LIKE '$f_recorre' ORDER BY fecha DESC";
                    $exe_c = sqlsrv_query($conn, $query_c);
                    while ($fila_c = sqlsrv_fetch_array($exe_c, SQLSRV_FETCH_ASSOC)) {
                        $punch_time_c[] = $fila_c['punch_time'];
                        $fecha_c[] = $fila_c['fecha'];
                        $Hora_c[] = $fila_c['Hora'];
                        $terminal_alias_c[] = $fila_c['terminal_alias'];
                        $val_term = strpos($fila_c['terminal_alias'], "TE ");//Buscamos la palabra TE que se refiere a una terminal de Tiempo Extra
                        if ($val_term !== false) {
                            $terminal_alias_ext[] = $fila_c['terminal_alias'];
                            $Hora_extra[] = $fila_c['Hora'];
                        } else {
                            $val_term = strpos($fila_c['terminal_alias'], "TIEMPO EXTRA");
                            if ($val_term !== false) {
                                $terminal_alias_ext[] = $fila_c['terminal_alias'];
                                $Hora_extra[] = $fila_c['Hora'];
                            } else {
                                $terminal_alias_nor[] = $fila_c['terminal_alias'];
                                $Hora_normal[] = $fila_c['Hora'];
                            }
                        }
                    }
                    //***OBTENER HORAS LABORALES DENTRO DE LA SEMANA***
                }

                /*echo "<br>***";
                print_r($Hora_normal);
                continue;*/
                /*print_r($Hora_c);*/
                /*echo "<br>";
                print_r($Hora_normal[0]);
                if ($z == 3) {
                    exit();
                }
                continue;
                exit();*/
                /*echo "<br>";
                print_r($Hora_extra);
                echo "<br>****************************************";
                echo "<br>Entrada Normal: ".$Hora_normal[0]." Salida Normal: ".end($Hora_normal);
                echo "<br>Entrada Extra: ".$Hora_extra[0]." Salida Extra: ".end($Hora_extra);
                echo "<br>****************************************";*/

                $f_recorre_insert = $f_recorre;
                $h_norm_A = new DateTime($Hora_normal[0]);
                $h_norm_B = new DateTime(end($Hora_normal));
                if ($turno == "noche") {
                    $h_norm_A->modify('-1 day');
                    //$f_recorre_insert = $menos_un_dia;
                }
                $diferencia_norm = $h_norm_A->diff($h_norm_B);
                //echo "<br>".$diferencia_norm->format('NORMAL: %H horas %i minutos %s segundos');
                $h_extra_A = new DateTime($Hora_extra[0]);
                $h_extra_B = new DateTime(end($Hora_extra));
                if ($turno == "noche") {
                    $h_extra_A->modify('-1 day');
                }
                $diferencia_ext = $h_extra_A->diff($h_extra_B);

                //echo "<br>".$diferencia_ext->format('EXTRA: %H horas %i minutos %s segundos');
                //$punch_time_c = array_unique($punch_time_c);
                //$hrs_norm_tot += $diferencia_norm->format('%H');
                //$diferencia_norm = json_encode($diferencia_norm);
                //print_r($diferencia_norm);
                foreach ($diferencia_norm as $key => $value) {
                    //echo "<br>".$key." -> ".$value;
                    if ($key == 'h') {
                        $hrs_norm_tot += $value;
                    }
                    if ($key == 'i') {
                        $min_norm_tot += $value;
                    }
                }
                foreach ($diferencia_ext as $key => $value) {
                    if ($key == 'h') {
                        $hrs_ext_tot += $value;
                    }
                    if ($key == 'i') {
                        $min_ext_tot += $value;
                    }
                }

                switch ($dia_dia) {
                    case 'Lunes':
                        /*foreach ($punch_time_c as $key => $value) {
                            $v_lun .= "<br>".$value."->".$terminal_alias_c[$key];
                        }
                        //$v_lun = "";*/

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Obtenemos el total de horas de la jornada laboral

                        if ($he_lun == 'Descanso') {
                            $he_lun = $Hora_normal[0];
                            $hs_lun = end($Hora_normal);
                        }

                        $h_turno_A = new DateTime($he_lun);
                        $h_turno_B = new DateTime($hs_lun);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                        }

                        if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                            $h_extra_A = new DateTime($Hora_extra[0]);
                            $h_turno_A = new DateTime($he_lun);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                            $h_turno_B = new DateTime($hs_lun);
                            $h_extra_B = new DateTime(end($Hora_extra));
                            $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/


                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }
                        
                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <!--<tr>".$str_permisos."</tr>-->
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>
                                            Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_l = (empty($hs_lun)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_l = '0';
                            }

                            $txt_te_l = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_l = $tot_hrs_2;
                                $txt_te_l = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_l."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_l."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_lun .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_lun." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_lun." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_lun." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_lun." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Martes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_mar == 'Descanso') {
                            $he_mar = $Hora_normal[0];
                            $hs_mar = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_mar);
                        $h_turno_B = new DateTime($hs_mar);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                        }

                        if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                            $h_extra_A = new DateTime($Hora_extra[0]);
                            $h_turno_A = new DateTime($he_mar);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                            $h_turno_B = new DateTime($hs_mar);
                            $h_extra_B = new DateTime(end($Hora_extra));
                            $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_ma = (empty($hs_mar)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_ma = '0';
                            }

                            $txt_te_ma = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_ma = $tot_hrs_2;
                                $txt_te_ma = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_ma."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_ma."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_mar .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_mar." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_mar." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_mar." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_mar." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Miercoles':

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_mie == 'Descanso') {
                            $he_mie = $Hora_normal[0];
                            $hs_mie = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_mie);
                        $h_turno_B = new DateTime($hs_mie);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                        }

                        if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                            $h_extra_A = new DateTime($Hora_extra[0]);
                            $h_turno_A = new DateTime($he_mie);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                            $h_turno_B = new DateTime($hs_mie);
                            $h_extra_B = new DateTime(end($Hora_extra));
                            $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_mi = (empty($hs_mie)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_mi = '0';
                            }

                            $txt_te_mi = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_mi = $tot_hrs_2;
                                $txt_te_mi = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_mi."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_mi."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_mie .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_mie." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_mie." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_mie." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_mie." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Jueves':

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_jue == 'Descanso') {
                            $he_jue = $Hora_normal[0];
                            $hs_jue = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_jue);
                        $h_turno_B = new DateTime($hs_jue);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                        }

                        if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                            $h_extra_A = new DateTime($Hora_extra[0]);
                            $h_turno_A = new DateTime($he_jue);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                            $h_turno_B = new DateTime($hs_jue);
                            $h_extra_B = new DateTime(end($Hora_extra));
                            $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <!--<tr>".$str_permisos."</tr>-->
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>
                                            Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_j = (empty($hs_jue)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_j = '0';
                            }

                            $txt_te_j = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_j = $tot_hrs_2;
                                $txt_te_j = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            
                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_j."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_j."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_jue .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_jue." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_jue." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_jue." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_jue." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Viernes':

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_vie == 'Descanso') {
                            $he_vie = $Hora_normal[0];
                            $hs_vie = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_vie);
                        $h_turno_B = new DateTime($hs_vie);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                        }

                        if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                            $h_extra_A = new DateTime($Hora_extra[0]);
                            $h_turno_A = new DateTime($he_vie);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                            $h_turno_B = new DateTime($hs_vie);
                            $h_extra_B = new DateTime(end($Hora_extra));
                            $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <!--<tr>".$str_permisos."</tr>-->
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>
                                            Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_v = (empty($hs_vie)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_v = '0';
                            }

                            $txt_te_v = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_v = $tot_hrs_2;
                                $txt_te_v = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_v."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_v."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_vie .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_vie." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_vie." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";
                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_vie." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_vie." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Sabado':

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_sab == 'Descanso') {
                            $he_sab = $Hora_normal[0];
                            $hs_sab = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_sab);
                        $h_turno_B = new DateTime($hs_sab);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{
                            if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                                $h_extra_A = new DateTime($Hora_extra[0]);
                                $h_turno_A = new DateTime($he_sab);
                                $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                            }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                                $h_turno_B = new DateTime($hs_sab);
                                $h_extra_B = new DateTime(end($Hora_extra));
                                $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                            }
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";
                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <!--<tr>".$str_permisos."</tr>-->
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>
                                            Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_s = (empty($hs_sab)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_s = '0';
                            }

                            $txt_te_s = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_s = $tot_hrs_2;
                                $txt_te_s = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_s."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";

                            if ($tipo_nomina == '15') {
                                $slc_semana = date("W", strtotime($f_recorre_insert));
                            }

                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_s."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_sab .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_sab." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_sab." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_sab." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_sab." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;

                    case 'Domingo':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        if ($he_dom == 'Descanso') {
                            $he_dom = $Hora_normal[0];
                            $hs_dom = end($Hora_normal);
                        }

                        //***Obtenemos el total de horas de la jornada laboral
                        $h_turno_A = new DateTime($he_dom);
                        $h_turno_B = new DateTime($hs_dom);
                        if ($turno == "noche") {
                            $h_turno_A->modify('-1 day');
                        }
                        $diferencia_turno = $h_turno_A->diff($h_turno_B);
                        if ($diferencia_turno->format('%H') == 0) {//***Si la diferencia es cero, tomamos la diferencia del checador
                            $diferencia_turno = $h_norm_A->diff($h_norm_B);
                            $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                        }else{
                            if ($turno == "noche") {//Si es de noche tomamos la primer checada de tiempo extra contra la entrada de la jornada laboral
                                $h_extra_A = new DateTime($Hora_extra[0]);
                                $h_turno_A = new DateTime($he_dom);
                                $diferencia_ext_b = $h_extra_A->diff($h_turno_A);
                            }else{//Si es de dia tomamos la salida de la jornada laboral contra la ultima checada de tiempo extra
                                $h_turno_B = new DateTime($hs_dom);
                                $h_extra_B = new DateTime(end($Hora_extra));
                                $diferencia_ext_b = $h_turno_B->diff($h_extra_B);
                            }
                        }

                        //***Obtener los minutos de retardo comparando la entrada de la jornada laboral vs la primer checada normal
                        $diferencia_minutos_retardo = 0;
                        /*$diferencia_minutos_retardo = 5;
                        if ($turno == "noche") {
                            $diferencia_minutos_retardo = 4;
                        }else{
                            if ((strtotime($Hora_normal[0]) > strtotime($he_lun)) && (!empty(strtotime($he_lun)))) {
                                $time_A = new DateTime($Hora_normal[0]);
                                $time_B = new DateTime($he_lun);
                                $diferencia_minutos_retardo = $time_A->diff($time_B);
                                //$diferencia_minutos_retardo = 3;
                            } else {
                                $diferencia_minutos_retardo = 2;
                            }
                        }*/

                        //$query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert' AND hora_nin = '".$Hora_normal[0].".0000000' AND hora_nout = '".end($Hora_normal).".0000000'";
                        $query_master = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND f_recorre = '$f_recorre_insert'";

                        $exe_master = sqlsrv_query($cnx, $query_master);
                        $fila_master = sqlsrv_fetch_array($exe_master, SQLSRV_FETCH_ASSOC);
                        $tot_hrs_n_new = $fila_master['tot_hrs_n_new'];
                        $tot_min_n_new = $fila_master['tot_min_n_new'];
                        $tot_hrs_e_new = $fila_master['tot_hrs_e_new'];
                        $tot_min_e_new = $fila_master['tot_min_e_new'];
                        if ($fila_master['estatus'] == 1) {
                            $aprobar_todo = 1;
                        } else {
                            $aprobar_todo = 2;
                        }

                        if ($tipo_nomina == '15') {
                            $slc_semana = date("W", strtotime($f_recorre_insert));
                        }

                        if(($tot_hrs_n_new > 0) || ($tot_hrs_e_new > 0)){
                            $tr_master = "
                            <!--<tr>".$str_permisos."</tr>-->
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_hrs_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='number' min='0' max='59' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_n_new."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$tot_hrs_e_new."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='number' min='0' max='59' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$tot_min_e_new."' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."' style='display:none;'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center>".$query_master."<button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center>
                                        <div class='alert alert-success' role='alert' style='font-size: small;'>
                                            Aprobado!";
                                            if ($fila_master['estatus'] == 1) {
                                                $tr_buttons .= "
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$f_recorre_insert."`, `".$emp_code_b."`)'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                                </button>";
                                            }
                                            $tr_buttons .= "
                                        </div>
                                        <div id='btn_mod_super_".$emp_code_b."' hidden>
                                            <button type='button' class='btn btn-success btn-sm'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                                            </button>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                            ";
                        }else{
                            $estimado_d = (empty($hs_dom)) ? $diferencia_ext->format('%H') : $diferencia_ext_b->format('%H') ;//***Revisamos si tiene horario de salida, si esta vacio coloca el calculo aproximado, de lo contrario coloca el calculo entre la salida y la checada final de tiempo extra
                            if ($diferencia_ext->format('%H') == 0) {
                                $estimado_d = '0';
                            }

                            $txt_te_d = "";
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos
                            $registro_te_1 = "SELECT COUNT([id]) AS tot_reg_1 FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                            $exe_registro_te_1 = sqlsrv_query($cnx, $registro_te_1);
                            $fila_registro_te_1 = sqlsrv_fetch_array($exe_registro_te_1, SQLSRV_FETCH_ASSOC);
                            $tot_reg_1 = $fila_registro_te_1['tot_reg_1'];
                            if ($tot_reg_1 > 0) {
                                $registro_te_2 = "SELECT * FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$emp_code_b' AND [rh_te_hrs].[fecha_te] = '$f_recorre_insert'";
                                $exe_registro_te_2 = sqlsrv_query($cnx, $registro_te_2);
                                $fila_registro_te_2 = sqlsrv_fetch_array($exe_registro_te_2, SQLSRV_FETCH_ASSOC);
                                $num_orden_2 = $fila_registro_te_2['num_orden'];
                                $parte_2 = $fila_registro_te_2['parte'];
                                $lote_2 = $fila_registro_te_2['lote'];
                                $desc_parte_2 = $fila_registro_te_2['desc_parte'];
                                $tot_hrs_2 = $fila_registro_te_2['tot_hrs'];

                                $estimado_d = $tot_hrs_2;
                                $txt_te_d = $desc_parte_2.",".$lote_2;
                            }
                            //***Buscamos registros por el empleado que haya realizado en la tablet para respetar sus horas trabajadas y los datos tecnicos

                            $tr_master = "
                            <tr>".$str_permisos."</tr>
                            <tr id='tr_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;'>
                                    Hrs: <input type='number' min='0' max='12' id='inphin_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white;' value='".$diferencia_turno->format('%H')."' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min: <input type='text' id='inphout_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                                <td style='border: 1px solid;'>
                                    Hrs TE: <input type='number' min='0' max='12' id='inphinext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #50A009; color: white;' value='".$estimado_d."'>
                                </td>
                                <td style='border: 1px solid;'>
                                    Min TE: <input type='text' id='inphoutext_".$emp_code_b."_".$f_recorre_insert."' style='background-color: #ECB43B; color: white; width: 66px;' value='00' readonly>
                                </td>
                            </tr>
                            ";
                            $tr_buttons = "
                            <tr id='response_fun_aprobar_".$emp_code_b."_".$f_recorre_insert."'>
                                <td style='border: 1px solid;' colspan='2' id='btn_".$emp_code_b."_".$f_recorre_insert."'>
                                    <center><button style='display:none;' type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`".$emp_code_b."`, `".$f_recorre_insert."`, `a`)'>Modificar</button></center>
                                </td>
                                <td style='border: 1px solid;' colspan='2'><center><button type='button' class='btn btn-success btn-sm' onclick='fun_aprobar(`".$dia_dia."`, `".$slc_year."`, `".$slc_semana."`, `".$emp_code_b."`, `".$f_recorre_insert."`, `".$Hora_normal[0]."`, `".end($Hora_normal)."`, `".$Hora_extra[0]."`, `".end($Hora_extra)."`, `".$diferencia_norm->format('%H')."`, `".$diferencia_norm->format('%i')."`, `".$diferencia_ext->format('%H')."`, `".$diferencia_ext->format('%i')."`, `".$shift_id."`, `".$diferencia_minutos_retardo."`, `".$txt_te_d."`)'>Aprobar</button></center></td>
                            </tr>
                            <tr id='response_fun_aprobar_B_".$emp_code_b."_".$f_recorre_insert."'>
                            </tr>
                            ";
                        }

                        $v_dom .= "
                            <table>
                                <tr>
                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_dom." hrs</strong></td>
                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_dom." hrs</strong></td>
                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid;'>
                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                    </td>
                                    <td style='border: 1px solid;'>
                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                    </td>
                                </tr>
                                ".$tr_master."
                                ".$tr_buttons."
                            </table>
                        ";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>
                                            <table>
                                                <tr>
                                                    <td style='border: 1px solid;'>Entrada: <strong>".$he_dom." hrs</strong></td>
                                                    <td style='border: 1px solid;'>Salida: <strong>".$hs_dom." hrs</strong></td>
                                                    <td style='border: 1px solid; font-size: 8px;'>".$last_name_b." ".$first_name_b."</td>
                                                    <td style='border: 1px solid;'>".$str_permisos."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>In: <strong>".$Hora_normal[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out: <strong>".end($Hora_normal)."</strong></td>
                                                    <td style='border: 1px solid;'>In TE: <strong>".$Hora_extra[0]."</strong></td>
                                                    <td style='border: 1px solid;'>Out TE: <strong>".end($Hora_extra)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'><p style='font-size'></p><strong>".wordwrap($terminal_alias_nor[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_nor), 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap($terminal_alias_ext[0], 13, "<br>", 1)."</strong></td>
                                                    <td style='border: 1px solid;'><strong>".wordwrap(end($terminal_alias_ext), 13, "<br>", 1)."</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_norm->format('%H horas %i minutos')."</td>
                                                    <td style='border: 1px solid;' colspan='2'>".$diferencia_ext->format('%H horas %i minutos')."</td>
                                                </tr>
                                                <tr>
                                                    <td style='border: 1px solid;'>
                                                        Hrs: <input type='number' min='0' max='12' id='inphin_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min: <input type='number' min='0' max='59' id='inphout_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_norm->format('%i')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Hrs TE: <input type='number' min='0' max='12' id='inphinext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%H')."' readonly>
                                                    </td>
                                                    <td style='border: 1px solid;'>
                                                        Min TE: <input type='number' min='0' max='59' id='inphoutext_read_".$emp_code_b."_".$f_recorre_insert."' value='".$diferencia_ext->format('%i')."' readonly>
                                                    </td>
                                                </tr>
                                                ".$tr_master."
                                                ".$tr_buttons."
                                            </table>
                                        </td>";
                        }
                        break;
                        
                }
            }else{
                switch ($dia_dia) {
                    case 'Lunes':
                        $slc_year = $_POST['slc_year'];
                        $slc_semana = $_POST['slc_semana'];

                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***

                        $v_lun = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_lun."</b></label>
                                    <label>Salida: <b>".$hs_lun."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario' onclick='new_horario(`".$slc_semana."`, `".$slc_year."`, `".$emp_code_b."`, `".$dia_dia."`, `".$f_recorre."`)'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_lun."</td>";
                        }
                        break;

                    case 'Martes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_mar = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_mar."</b></label>
                                    <label>Salida: <b>".$hs_mar."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_mar."</td>";
                        }
                        break;

                    case 'Miercoles':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_mie = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_mie."</b></label>
                                    <label>Salida: <b>".$hs_mie."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_mie."</td>";
                        }
                        break;

                    case 'Jueves':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_jue = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_jue."</b></label>
                                    <label>Salida: <b>".$hs_jue."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_jue."</td>";
                        }
                        break;

                    case 'Viernes':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_vie = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_vie."</b></label>
                                    <label>Salida: <b>".$hs_vie."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";
                        
                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_vie."</td>";
                        }
                        break;

                    case 'Sabado':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_sab = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_sab."</b></label>
                                    <label>Salida: <b>".$hs_sab."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_sab."</td>";
                        }
                        break;

                    case 'Domingo':
                        //***Codigo para obtener los permisos del dia corriente***
                        $str_permisos = '';
                        include ('permisos_dia.php');
                        //***Codigo para obtener los permisos del dia corriente***
                        $v_dom = "
                            <div id='div_empty_".$emp_code_b."_".$dia_dia."'>
                                <center>
                                    <label>Entrada: <b>".$he_dom."</b></label>
                                    <label>Salida: <b>".$hs_dom."</b></label><br>
                                    ".$str_permisos."<br>
                                    <!--<button type='button' class='btn btn-warning' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;' id='btn_new_".$emp_code_b."_".$dia_dia."' title='Agregar horario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus-square'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><line x1='12' y1='8' x2='12' y2='16'></line><line x1='8' y1='12' x2='16' y2='12'></line></svg>
                                    </button>-->
                                </center>
                            </div>";

                        if ($tipo_nomina == '15') {
                            $td_body .= "<td>".$v_dom."</td>";
                        }
                        break;
                }
            }
        }

        //***TIEMPO NORMAL***
        $hr_min_tot_arr = explode(".", bcdiv($min_norm_tot/60, '1',2));
        $hr_min_tot = $hr_min_tot_arr[0];
        $hrs_norm_tot += $hr_min_tot;
        $min_min_tot = $hr_min_tot_arr[1];
        $min_min_tot_arr = explode(".", bcdiv(".".$min_min_tot*60, '1',2));
        $min_min_tot = $min_min_tot_arr[1];
        //***TIEMPO EXTRA***
        $hr_min_tot_ext_arr = explode(".", bcdiv($min_ext_tot/60, '1',2));
        $hr_min_ext_tot = $hr_min_tot_ext_arr[0];
        $hrs_ext_tot += $hr_min_ext_tot;
        $min_min_ext_tot = $hr_min_tot_ext_arr[1];
        $min_min_tot_ext_arr = explode(".", bcdiv(".".$min_min_ext_tot*60, '1',2));
        $min_min_ext_tot = $min_min_tot_ext_arr[1];

        if($aprobar_todo == 1){
            $btn_apr_all = "<button type='button' class='btn btn-success btn-sm' onclick='corregir_super(".$emp_code_b.")'>Corregir</button>";
        }else{
            //$btn_apr_all = "<button type='button' class='btn btn-success btn-sm' onclick='fun_aprob_all(`".$emp_code_b."`, `".$f_start."`, `".$f_end."`, `".$slc_semana."`, `".$slc_year."`)'>Aprobar todo</button>";
            //$btn_apr_all = "<button type='button' class='btn btn-success btn-sm' disabled>Aprobar todo ".$aprobar_todo."</button>";
            $btn_apr_all = "<button type='button' class='btn btn-success btn-sm' disabled>Corregir</button>";
        }

        $totales_horas_semana = 0;
        $totales_minutos_semana = 0;
        $totales_horas_extra_semana = 0;
        $totales_minutos_extra_semana = 0;
        if ($tipo_nomina == '7') {
            $query_htotales = "SELECT * FROM [dbo].[rh_master_emptime] WHERE year = '$slc_year' AND emp_code = '$emp_code_b' AND n_semana = '$slc_semana'";
            $exe_htotales = sqlsrv_query($cnx, $query_htotales);
            while ($fila_htotales = sqlsrv_fetch_array($exe_htotales, SQLSRV_FETCH_ASSOC)) {
                $totales_horas_semana += $fila_htotales['tot_hrs_n_new'];
                $totales_minutos_semana += $fila_htotales['tot_min_n_new'];
                $totales_horas_extra_semana += $fila_htotales['tot_hrs_e_new'];
                $totales_minutos_extra_semana += $fila_htotales['tot_min_e_new'];
            }
        }else{
            for ($k=$d_ini; $k <= $d_fin ; $k++) {
                $k = ($k < 10) ? '0'.$k : $k ;
                $fechita = date($slc_year."-".$v_mes."-".$k);
                $query_htotales = "SELECT * FROM [dbo].[rh_master_emptime] WHERE emp_code = '$emp_code_b' AND f_recorre = '$fechita'";
                $exe_htotales = sqlsrv_query($cnx, $query_htotales);
                $fila_htotales = sqlsrv_fetch_array($exe_htotales, SQLSRV_FETCH_ASSOC);
                $totales_horas_semana += $fila_htotales['tot_hrs_n_new'];
                $totales_minutos_semana += $fila_htotales['tot_min_n_new'];
                $totales_horas_extra_semana += $fila_htotales['tot_hrs_e_new'];
                $totales_minutos_extra_semana += $fila_htotales['tot_min_e_new'];
                
            }
        }

        if ($tipo_nomina == '7') {
            $td_body .= "
                <tr>
                    <td>".$emp_code_b."</td>
                    <td>".$last_name_b." ".$first_name_b."</td>
                    <td>".$v_lun."</td>
                    <td>".$v_mar."</td>
                    <td>".$v_mie."</td>
                    <td>".$v_jue."</td>
                    <td>".$v_vie."</td>
                    <td>".$v_sab."</td>
                    <td>".$v_dom."</td>
                    <td>
                        <table>
                            <tr>
                                <td style='border: 1px solid;' colspan='4'><center>".$last_name_b." ".$first_name_b."</center></td>
                            </tr>
                            <tr>
                                <td style='border: 1px solid;' colspan='2'>Totales Normal</td>
                                <td style='border: 1px solid;' colspan='2'>Totales Extra</td>
                            </tr>
                            <tr>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' value='$hrs_norm_tot' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' value='$min_min_tot' readonly></td>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' value='$hrs_ext_tot' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' value='$min_min_ext_tot' readonly></td>
                            </tr>
                            <tr id='response_htotales_".$emp_code_b."_".$slc_year."_".$slc_semana."'>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #1700ff; color: white;' value='$totales_horas_semana' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #1700ff; color: white;' value='$totales_minutos_semana' readonly></td>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #41009f; color: white;' value='$totales_horas_extra_semana' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #41009f; color: white;' value='$totales_minutos_extra_semana' readonly></td>
                            </tr>
                        </table>
                    </td>
                    <td>".$btn_apr_all."</td>
                    <td>No revisado</td>
                </tr>
            ";
        }

        if ($tipo_nomina == '15') {
            $td_body .= "
                    <td>
                        <table>
                            <tr>
                                <td style='border: 1px solid;' colspan='4'><center>".$last_name_b." ".$first_name_b."</center></td>
                            </tr>
                            <tr>
                                <td style='border: 1px solid;' colspan='2'>Totales Normal</td>
                                <td style='border: 1px solid;' colspan='2'>Totales Extra</td>
                            </tr>
                            <tr>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' value='$hrs_norm_tot' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' value='$min_min_tot' readonly></td>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' value='$hrs_ext_tot' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' value='$min_min_ext_tot' readonly></td>
                            </tr>
                            <tr id='response_htotales_".$emp_code_b."_".$slc_year."_".$slc_quincena."'>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #1700ff; color: white;' value='$totales_horas_semana' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #1700ff; color: white;' value='$totales_minutos_semana' readonly></td>
                                <td style='border: 1px solid;'>Hrs: <input min='0' max='12' type='number' style='background-color: #41009f; color: white;' value='$totales_horas_extra_semana' readonly></td>
                                <td style='border: 1px solid;'>Min: <input min='0' max='59' type='number' style='background-color: #41009f; color: white;' value='$totales_minutos_extra_semana' readonly></td>
                            </tr>
                        </table>
                    </td>
                    <td>".$btn_apr_all."</td>
                    <td>No revisado</td>
                </tr>
            ";
        }
        
        $str_emps .= "'".trim($emp_code_b)."',";
    }
    $str_emps = substr($str_emps, 0 , -1);

    $th_table = '';
    for ($k=$f_start; $k <= $f_end ; $k+=86400) {
        $fechita = date("Y-m-d", $k);
        $dia_diita = saber_dia($fechita);
        $th_table .= '<th style="width: 600px;">'.$dia_diita.' '.$fechita.'</th>';
    }
?>
<table class="table table-dark align-middle" id="tb_super_check" style="display: block; white-space: nowrap;">
    <thead>
        <tr>
            <th># Empleado</th>
            <th>Nombre</th>
            <?php echo ($th_table); ?>
            <th>Total semana</th>
            <th>Acciones</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        <?php
            echo($td_body);
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th># Empleado</th>
            <th>Nombre</th>
            <?php echo ($th_table); ?>
            <th>Total semana</th>
            <th>Acciones</th>
            <th>Estatus</th>
        </tr>
    </tfoot>
</table>
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn me-md-2 btn-outline-danger" type="button" onclick="pdf_mochilas(`<?php echo($str_emps); ?>`)" style="display: none;">¡Si lo tocas, te mueres!</button>
    <button class="btn me-md-2 btn-outline-info" type="button" onclick="descarga_excel_te(`<?php echo($str_emps); ?>`)">Generar Excel</button>
    <button class="btn me-md-2 btn-outline-primary" type="button" onclick="descarga_pdf_te(`<?php echo($str_emps); ?>`)">Generar PDF</button>
    <button class="btn me-md-2 btn-outline-success" type="button" onclick="aprovar_super_check(`<?php echo($str_emps); ?>`)">Aprobar y enviar</button>
</div>
<br>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_super_check').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            scrollY:        "400px",
            scrollX:        true,
            scrollCollapse: true,
        });
    });
</script>
<?php
    //$query_b = "SELECT TOP(15) emp_code, first_name, last_name FROM personnel_employee WHERE department_id = '22' ORDER BY emp_code ASC";
    //$query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ('6242', '6560')";//***Turno matutino 6783| Turno nocturno 5818
    //$query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE department_id = '$dept_code_a' ORDER BY emp_code ASC";//***Original
    //$query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ('7474', '6242', '6874', '7464', '6862', '7416', '7150', '7443', '7457', '6455', '6114', '6744', '6948', '7352', '7483', '6572', '5768', '7419', '6785', '6616', '7472', '7400', '7320', '7243', '7358', '7323', '7403', '7275', '6656', '7268', '6829', '6795', '7547', '7298', '7366', '7408', '5505', '6221', '7485', '7269', '7450', '6116', '7220', '6825', '7321', '7223', '6783', '7261', '7282', '7238', '6341', '6614', '7351', '6642', '7517', '7489', '7437', '7254', '6271', '6757', '6172', '7277', '5275', '6468', '7479', '7520', '7121', '7523', '7285', '6960', '7448', '6900', '7371', '5974', '5818', '7484', '7363', '7461', '6734', '6560', '7136', '6852', '7513', '7266', '6201', '5834', '6520', '7365', '6021', '6355', '7303', '6751', '6974', '7152', '7399', '6633', '6990', '4947', '7300', '1238', '7308', '7146', '7426', '7510', '7380', '7427', '7293', '7211', '7343', '7142', '7183', '6964', '6231', '5663', '5020', '6882', '5009', '7182', '5822', '7118', '7524', '5356', '5625', '7441', '7480', '7491', '6022', '5787', '5841', '5709', '6071', '7290', '5614', '6764', '7496', '7431', '5946', '6996', '6962', '7349', '6323', '7227', '5036', '5026', '7487', '6649', '6227', '7606', '6980', '7511', '7347', '7315', '5772', '6959', '6491', '7237', '7333', '6701', '6236', '6431', '7309', '7316', '5859', '7404', '7453', '7388', '7224', '6512', '7156', '7468', '6057', '7304', '6817', '6077', '7504', '5055', '6526', '6384', '7518', '7414', '6198', '7311', '4770', '7469', '6963', '4017', '7122', '7355', '7492', '7470', '7143', '6894', '5110', '6294', '6389', '5494', '6968', '7381', '6466', '5529', '7376', '7109', '6539', '7325', '7499', '7302', '4945', '6653', '6306', '6903', '7172', '6792', '7447', '6255', '7284', '7288', '6925', '6317', '7459', '7370', '6190', '5986', '7134', '6506', '5342', '7460', '7604', '5291', '6397', '7336', '7327', '7222', '6362', '6815', '6763', '5865', '7149', '5917', '5994', '7208', '7477', '7455', '6543', '6790', '7142', '5939', '6791', '6553', '6419', '7494', '6892', '7429', '4897', '6228', '7501', '5334', '6626', '7525', '6299', '6139', '4670', '7495', '7242', '5060', '7114', '6239', '5536', '7281', '7507', '6835', '6279', '6398', '6709', '7108', '5778', '6951', '5981', '4244', '7516', '6875', '7446', '7503', '7350', '7169', '7454', '5470', '6879', '5602', '5631', '7517', '7382', '6226', '7386', '6940', '5686', '7271', '5063', '5523', '5436', '7294', '6920', '6183', '6166', '7406', '7161', '4019', '6967', '7334', '7125', '6451', '6494', '5746', '6919', '6926')";//***Uso especifico para Norma Nelly
    //$query_b = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE emp_code IN ('7474', '6242', '6874', '7464', '6862', '7416')";//***Uso especifico para Norma Nelly
    
    /*
    ***TABLETAS***
    '7474', '6242', '6874', '7464', '6862', '7416', '7150', '7443', '7457', '6455', '6114', '6744', '6948', '7352', '7483', '6572', '5768', '7419', '6785', '6616', '7472', '7400', '7320', '7243', '7358', '7323', '7403', '6477'
    **************
    ***FABRICACION***
    '7275', '6656', '7268', '6829', '6795', '7547', '7298', '7366', '7408', '5505', '6221', '7485', '7269', '7450', '6116', '7220', '6825', '7321', '7223', '6783', '7261', '7282', '7238', '6341', '6614', '7351', '6642', '7517', '7489', '7437', '7254', '6271', '6757', '6172', '7277', '5275', '6468', '7479', '7520', '7121', '7523', '7285', '6960', '7448', '6900', '7371', '5974'
    *****************
    ***ACONDICIONADO***
    '5818', '7484', '7363', '7461', '6734', '6560', '7136', '6852', '7513', '7266', '6201', '5834', '6520', '7365', '6021', '6355', '7303', '6751', '6974', '7152', '7399', '6633', '6990', '4947', '7300', '1238', '7308', '7146', '7426', '7510', '7380', '7427', '7293', '7211', '7343', '7142', '7183', '6964', '6231', '5663', '5020', '6882', '5009', '7182', '5822', '7118', '7524', '5356', '5625', '7441', '7480', '7491', '6022', '5787', '5841', '5709', '6071', '7290', '5614', '6764', '7496', '7431', '5946', '6996', '6962', '7349', '6323', '7227', '5036', '5026', '7487', '6649', '6227', '7606', '6980', '7511', '7347', '7315', '5772', '6959', '6491', '7237', '7333', '6701', '6236', '6431', '7309', '7316', '5859', '7404', '7453', '7388', '7224', '6512', '7156', '7468', '6057', '7304', '6817', '6077', '7504', '5055', '6526', '6384', '7518', '7414', '6198', '7311', '4770', '7469', '6963', '4017', '7122', '7355', '7492', '7470', '7143', '6894', '5110', '6294', '6389', '5494', '6968', '7381', '6466', '5529', '7376', '7109', '6539', '7325', '7499', '7302', '4945', '6653', '6306', '6903', '7172', '6792', '7447', '6255', '7284', '7288', '6925', '6317', '7459', '7370', '6190', '5986', '7134', '6506', '5342', '7460', '7604', '5291', '6397', '7336', '7327', '6672', '7500', '7402', '6590', '7467', '7244'
    *******************
    ***LIQUIDOS***
    '7222', '6362', '6815', '6763', '5865', '7149', '5917', '5994', '7208', '7477', '7455', '6543', '6790', '7142', '5939', '6791', '6553', '6419', '7494', '6892', '7429', '4897', '6228', '7501', '5334', '6626', '7525', '6299', '6139', '4670', '7495', '7242', '5060', '7114', '6239', '5536', '7281', '7507', '6835', '6279', '6398', '6709', '7108', '5778', '6951', '5981', '4244', '7516', '6875', '7446', '7503', '7350', '7169', '7454', '5470', '6879', '5602', '5631', '7517', '7382', '6226', '7386', '6940', '5686', '7271', '5063', '5523', '5436', '7294', '6920', '6183', '6166', '7406', '7161', '4019', '6967', '7334', '7125', '6451', '6494', '5746', '6919', '6926', '7475', '7137', '7465', '7466', '5763', '6652'
    **************
    ***
    */
?>
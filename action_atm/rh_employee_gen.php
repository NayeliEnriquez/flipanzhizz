<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loading...</title>
    </head>
    <body>
    </body>
</html>
<?php
    include ('../php/conn.php');

    $query_a = "SELECT * FROM [NOMINAS].[dbo].[QUINCENATIZAYUCA]";
    $exe_a = sqlsrv_query($conx, $query_a);
    while ($fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC)) {
        $CLAVE = trim($fila_a['CLAVE']);
        $NOMBRE = trim($fila_a['NOMBRE COMPLETO']);
        $PUESTO = trim($fila_a['PUESTO']);
        $DEPARTAMENTO = trim($fila_a['DEPARTAMENTO']);
        $NOMINA = trim($fila_a['NOMINA']);

        $revisa = "SELECT COUNT(CLAVE) AS count_revisa FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
        $exe_revisa = sqlsrv_query($cnx, $revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        $count_revisa = $fila_revisa['count_revisa'];

        if ($count_revisa == 0) {
            echo "<br>".$inserta_empg = "INSERT INTO [dbo].[rh_employee_gen] (
                [CLAVE], [NOMBRE COMPLETO], [PUESTO], 
                [DEPARTAMENTO], [NOMINA]
            VALUES
                ('$CLAVE', '$NOMBRE', '$PUESTO',
                '$DEPARTAMENTO', 'QUINCENA TIZAYUCA')";
            sqlsrv_query($cnx, $inserta_empg);
        }else{
            $revisa2 = "SELECT [NOMINA] FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
            $exe_revisa2 = sqlsrv_query($cnx, $revisa2);
            $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
            $NOMINA_2 = $fila_revisa2['NOMINA'];

            if ($NOMINA_2 != 'QUINCENA TIZAYUCA') {
               echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'QUINCENA TIZAYUCA' WHERE CLAVE = '$CLAVE'";
               sqlsrv_query($cnx, $update_empg);
            }
        }
    }

    $query_b = "SELECT * FROM [NOMINAS].[dbo].[QUINCENATLALPAN]";
    $exe_b = sqlsrv_query($conx, $query_b);
    while ($fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
        $CLAVE = trim($fila_b['CLAVE']);
        $NOMBRE = trim($fila_b['NOMBRE COMPLETO']);
        $PUESTO = trim($fila_b['PUESTO']);
        $DEPARTAMENTO = trim($fila_b['DEPARTAMENTO']);
        $NOMINA = trim($fila_b['NOMINA']);

        $revisa = "SELECT COUNT(CLAVE) AS count_revisa FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
        $exe_revisa = sqlsrv_query($cnx, $revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        $count_revisa = $fila_revisa['count_revisa'];

        if ($count_revisa == 0) {
            echo "<br>".$inserta_empg = "INSERT INTO [dbo].[rh_employee_gen] (
                [CLAVE], [NOMBRE COMPLETO], [PUESTO], 
                [DEPARTAMENTO], [NOMINA]
            VALUES
                ('$CLAVE', '$NOMBRE', '$PUESTO',
                '$DEPARTAMENTO', 'QUINCENA TLALPAN')";
            sqlsrv_query($cnx, $revisa);
        }else{
            $revisa2 = "SELECT [NOMINA] FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
            $exe_revisa2 = sqlsrv_query($cnx, $revisa2);
            $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
            $NOMINA_2 = $fila_revisa2['NOMINA'];

            if ($NOMINA_2 != 'QUINCENA TLALPAN') {
               echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'QUINCENA TLALPAN' WHERE CLAVE = '$CLAVE'";
               sqlsrv_query($cnx, $update_empg);
            }
        }
    }

    $query_c = "SELECT * FROM [NOMINAS].[dbo].[SEMANATIZAYUCA]";
    $exe_c = sqlsrv_query($conx, $query_c);
    while ($fila_c = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
        $CLAVE = trim($fila_c['CLAVE']);
        $NOMBRE = trim($fila_c['NOMBRE COMPLETO']);
        $PUESTO = trim($fila_c['PUESTO']);
        $DEPARTAMENTO = trim($fila_c['DEPARTAMENTO']);
        $NOMINA = trim($fila_c['NOMINA']);

        $revisa = "SELECT COUNT(CLAVE) AS count_revisa FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
        $exe_revisa = sqlsrv_query($cnx, $revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        $count_revisa = $fila_revisa['count_revisa'];

        if ($count_revisa == 0) {
            echo "<br>".$inserta_empg = "INSERT INTO [dbo].[rh_employee_gen] (
                [CLAVE], [NOMBRE COMPLETO], [PUESTO], 
                [DEPARTAMENTO], [NOMINA]
            VALUES
                ('$CLAVE', '$NOMBRE', '$PUESTO',
                '$DEPARTAMENTO', 'SEMANA TIZAYUCA')";
            sqlsrv_query($cnx, $revisa);
        }else{
            $revisa2 = "SELECT [NOMINA] FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
            $exe_revisa2 = sqlsrv_query($cnx, $revisa2);
            $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
            $NOMINA_2 = $fila_revisa2['NOMINA'];

            if ($NOMINA_2 != 'SEMANA TIZAYUCA') {
               echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'SEMANA TIZAYUCA' WHERE CLAVE = '$CLAVE'";
               sqlsrv_query($cnx, $update_empg);
            }
        }
    }

    $query_d = "SELECT * FROM [NOMINAS].[dbo].[SEMANATLALPAN]";
    $exe_d = sqlsrv_query($conx, $query_d);
    while ($fila_d = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC)) {
        $CLAVE = trim($fila_d['CLAVE']);
        $NOMBRE = trim($fila_d['NOMBRE COMPLETO']);
        $PUESTO = trim($fila_d['PUESTO']);
        $DEPARTAMENTO = trim($fila_d['DEPARTAMENTO']);
        $NOMINA = trim($fila_d['NOMINA']);

        $revisa = "SELECT COUNT(CLAVE) AS count_revisa FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
        $exe_revisa = sqlsrv_query($cnx, $revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        $count_revisa = $fila_revisa['count_revisa'];

        if ($count_revisa == 0) {
            echo "<br>".$inserta_empg = "INSERT INTO [dbo].[rh_employee_gen] (
                [CLAVE], [NOMBRE COMPLETO], [PUESTO], 
                [DEPARTAMENTO], [NOMINA]
            VALUES
                ('$CLAVE', '$NOMBRE', '$PUESTO',
                '$DEPARTAMENTO', 'SEMANA TLALPAN')";
            sqlsrv_query($cnx, $revisa);
        }else{
            echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'SEMANA TLALPAN' WHERE CLAVE = '$CLAVE'";
            sqlsrv_query($cnx, $update_empg);
            /*$revisa2 = "SELECT [NOMINA] FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
            $exe_revisa2 = sqlsrv_query($cnx, $revisa2);
            $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
            $NOMINA_2 = $fila_revisa2['NOMINA'];

            if ($NOMINA_2 != 'SEMANA TLALPAN') {
               echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'SEMANA TLALPAN' WHERE CLAVE = '$CLAVE'";
               sqlsrv_query($cnx, $update_empg);
            }*/
        }
    }

    $query_e = "SELECT * FROM [NOMINAS].[dbo].[SEMANATLANE]";
    $exe_e = sqlsrv_query($conx, $query_e);
    while ($fila_e = sqlsrv_fetch_array($exe_e, SQLSRV_FETCH_ASSOC)) {
        $CLAVE = trim($fila_e['CLAVE']);
        $NOMBRE = trim($fila_e['NOMBRE COMPLETO']);

        $revisa = "SELECT COUNT(CLAVE) AS count_revisa FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
        $exe_revisa = sqlsrv_query($cnx, $revisa);
        $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
        $count_revisa = $fila_revisa['count_revisa'];

        if ($count_revisa == 0) {
            echo "<br>".$inserta_empg = "INSERT INTO [dbo].[rh_employee_gen] (
                [CLAVE], [NOMBRE COMPLETO], [NOMINA]
            VALUES
                ('$CLAVE', '$NOMBRE', 'SEMANA TLALNEPANTLA')";
            sqlsrv_query($cnx, $revisa);
        }else{
            $revisa2 = "SELECT [NOMINA] FROM [rh_novag_system].[dbo].[rh_employee_gen] WHERE CLAVE = '$CLAVE'";
            $exe_revisa2 = sqlsrv_query($cnx, $revisa2);
            $fila_revisa2 = sqlsrv_fetch_array($exe_revisa2, SQLSRV_FETCH_ASSOC);
            $NOMINA_2 = $fila_revisa2['NOMINA'];

            if ($NOMINA_2 != 'SEMANA TLALNEPANTLA') {
               echo "<br>".$update_empg = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET NOMINA = 'SEMANA TLALNEPANTLA' WHERE CLAVE = '$CLAVE'";
               sqlsrv_query($cnx, $update_empg);
            }
        }
    }

    $query_f = "SELECT * FROM personnel_position";
    $exe_f = sqlsrv_query($conn, $query_f);
    while ($fila_f = sqlsrv_fetch_array($exe_f, SQLSRV_FETCH_ASSOC)) {
        $id_f = $fila_f['id'];
        $position_code_f = $fila_f['position_code'];
        $position_name_f = $fila_f['position_name'];
        $is_default_f = $fila_f['is_default'];
        $company_id_f = $fila_f['company_id'];
        $parent_position_id_f = $fila_f['parent_position_id'];

        $query_g = "SELECT * FROM personnel_position WHERE position_code = '$position_code_f'";
        $exe_g = sqlsrv_query($cnx, $query_g);
        $fila_g = sqlsrv_fetch_array($exe_g, SQLSRV_FETCH_ASSOC);
        $id_g = $fila_g['id'];
        if ($id_g == null) {
            echo "<br>".$query_h = "INSERT INTO [dbo].[personnel_position]
                            ([position_code], [position_name], [is_default]
                            ,[company_id], [parent_position_id])
                        VALUES
                            ('$position_code_f', '$position_name_f', '$is_default_f'
                            ,'$company_id_f', '$parent_position_id_f')";
            sqlsrv_query($cnx, $query_h);
        }
    }

    $query_i = "SELECT * FROM personnel_employee";
    $exe_i = sqlsrv_query($conn, $query_i);
    $z = 0;
    while ($fila_i = sqlsrv_fetch_array($exe_i, SQLSRV_FETCH_ASSOC)) {
        $z++;
        $id_i = $fila_i['id'];
        $create_time_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['create_time'])))), 5, 10);
        $create_user_i = $fila_i['create_user'];
        $change_time_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['change_time'])))), 5, 10);
        $change_user_i = $fila_i['change_user'];    $status_i = $fila_i['status'];
        $emp_code_i = $fila_i['emp_code'];          $first_name_i = utf8_encode($fila_i['first_name']);                                             $last_name_i = utf8_encode($fila_i['last_name']);
        $nickname_i = $fila_i['nickname'];          $passport_i = $fila_i['passport'];          $driver_license_automobile_i = $fila_i['driver_license_automobile'];
        $driver_license_motorcycle_i = $fila_i['driver_license_motorcycle'];                    $photo_i = $fila_i['photo'];                        $self_password_i = $fila_i['self_password'];
        $device_password_i = $fila_i['device_password'];                                        $dev_privilege_i = $fila_i['dev_privilege'];        $card_no_i = $fila_i['card_no'];
        $acc_group_i = $fila_i['acc_group'];        $acc_timezone_i = $fila_i['acc_timezone'];  $gender_i = $fila_i['gender'];
        $birthday_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['birthday'])))), 5, 10);
        $address_i = $fila_i['address'];            $postcode_i = $fila_i['postcode'];
        $office_tel_i = $fila_i['office_tel'];      $contact_tel_i = $fila_i['contact_tel'];    $mobile_i = $fila_i['mobile'];
        $national_num_i = $fila_i['national_num'];  $payroll_num_i = $fila_i['payroll_num'];    $internal_emp_num_i = $fila_i['internal_emp_num'];
        $national_i = $fila_i['national'];          $religion_i = $fila_i['religion'];          $title_i = $fila_i['title'];
        $enroll_sn_i = $fila_i['enroll_sn'];        $ssn_i = $fila_i['ssn'];                    
        $update_time_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['update_time'])))), 5, 10);
        $hire_date_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['hire_date'])))), 5, 10);
        $verify_mode_i = $fila_i['verify_mode'];    $city_i = $fila_i['city'];
        $is_admin_i = $fila_i['is_admin'];          $emp_type_i = $fila_i['emp_type'];          $enable_att_i = $fila_i['enable_att'];
        $enable_payroll_i = $fila_i['enable_payroll'];                                          $enable_overtime_i = $fila_i['enable_overtime'];    $enable_holiday_i = $fila_i['enable_holiday'];
        $deleted_i = $fila_i['deleted'];            $reserved_i = $fila_i['reserved'];          $del_tag_i = $fila_i['del_tag'];
        $app_status_i = $fila_i['app_status'];      $app_role_i = $fila_i['app_role'];          $email_i = $fila_i['email'];
        $last_login_i = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_i['last_login'])))), 5, 10);
        $is_active_i = $fila_i['is_active'];        $vacation_rule_i = $fila_i['vacation_rule'];
        $company_id_i = $fila_i['company_id'];      $department_id_i = $fila_i['department_id'];$position_id_i = $fila_i['position_id'];

        $query_j = "SELECT emp_code FROM personnel_employee WHERE emp_code = '$emp_code_i'";
        $exe_j = sqlsrv_query($cnx, $query_j);
        $fila_j = sqlsrv_fetch_array($exe_j, SQLSRV_FETCH_ASSOC);
        $emp_code_j = $fila_j['emp_code'];

        if ($emp_code_j == null) {
            echo "<br>".$query_k = "INSERT INTO [dbo].[personnel_employee]
                            ([create_time],[create_user],[change_time],[change_user],[status],[emp_code]
                            ,[first_name],[last_name],[nickname],[passport],[driver_license_automobile],[driver_license_motorcycle]
                            ,[photo],[self_password],[device_password],[dev_privilege],[card_no],[acc_group]
                            ,[acc_timezone],[gender],[birthday],[address],[postcode],[office_tel]
                            ,[contact_tel],[mobile],[national_num],[payroll_num],[internal_emp_num],[national]
                            ,[religion],[title],[enroll_sn],[ssn],[update_time],[hire_date]
                            ,[verify_mode],[city],[is_admin],[emp_type],[enable_att],[enable_payroll]
                            ,[enable_overtime],[enable_holiday],[deleted],[reserved],[del_tag],[app_status]
                            ,[app_role],[email],[last_login],[is_active],[vacation_rule],[company_id]
                            ,[department_id],[position_id])
                        VALUES
                            ('$create_time_i','$create_user_i','$change_time_i','$change_user_i','$status_i','$emp_code_i'
                            ,'$first_name_i','$last_name_i','$nickname_i','$passport_i','$driver_license_automobile_i','$driver_license_motorcycle_i'
                            ,'$photo_i','$self_password_i','$device_password_i','$dev_privilege_i','$card_no_i','$acc_group_i'
                            ,'$acc_timezone_i','$gender_i','$birthday_i','$address_i','$postcode_i','$office_tel_i'
                            ,'$contact_tel_i','$mobile_i','$national_num_i','$payroll_num_i','$internal_emp_num_i','$national_i'
                            ,'$religion_i','$title_i','$enroll_sn_i','$ssn_i','$update_time_i','$hire_date_i'
                            ,'$verify_mode_i','$city_i','$is_admin_i','$emp_type_i','$enable_att_i','$enable_payroll_i'
                            ,'$enable_overtime_i','$enable_holiday_i','$deleted_i','$reserved_i','$del_tag_i','$app_status_i'
                            ,'$app_role_i','$email_i','$last_login_i','$is_active_i','$vacation_rule_i','$company_id_i'
                            ,'$department_id_i','$position_id_i')";
        }else{
            echo "<br>".$query_k = "UPDATE [dbo].[personnel_employee] 
                        SET 
                            [create_time] = '$create_time_i',[create_user] = '$create_user_i',[change_time] = '$change_time_i'
                            ,[change_user] = '$change_user_i',[status] = '$status_i'
                            ,[first_name] = '$first_name_i',[last_name] = '$last_name_i',[nickname] = '$nickname_i'
                            ,[passport] = '$passport_i',[driver_license_automobile] = '$driver_license_automobile_i',[driver_license_motorcycle] = '$driver_license_motorcycle_i'
                            ,[photo] = '$photo_i',[self_password] = '$self_password_i',[device_password] = '$device_password_i'
                            ,[dev_privilege] = '$dev_privilege_i',[card_no] = '$card_no_i',[acc_group] = '$acc_group_i'
                            ,[acc_timezone] = '$acc_timezone_i',[gender] = '$gender_i',[birthday] = '$birthday_i'
                            ,[address] = '$address_i',[postcode] = '$postcode_i',[office_tel] = '$office_tel_i'
                            ,[contact_tel] = '$contact_tel_i',[mobile] = '$mobile_i',[national_num] = '$national_num_i'
                            ,[payroll_num] = '$payroll_num_i',[internal_emp_num] = '$internal_emp_num_i',[national] = '$national_i'
                            ,[religion] = '$religion_i',[title] = '$title_i',[enroll_sn] = '$enroll_sn_i'
                            ,[ssn] = '$ssn_i',[update_time] = '$update_time_i',[hire_date] = '$hire_date_i'
                            ,[verify_mode] = '$verify_mode_i',[city] = '$city_i',[is_admin] = '$is_admin_i'
                            ,[emp_type] = '$emp_type_i',[enable_att] = '$enable_att_i',[enable_payroll] = '$enable_payroll_i'
                            ,[enable_overtime] = '$enable_overtime_i',[enable_holiday] = '$enable_holiday_i',[deleted] = '$deleted_i'
                            ,[reserved] = '$reserved_i',[del_tag] = '$del_tag_i',[app_status] = '$app_status_i'
                            ,[app_role] = '$app_role_i',[email] = '$email_i',[last_login] = '$last_login_i'
                            ,[is_active] = '$is_active_i',[vacation_rule] = '$vacation_rule_i',[company_id] = '$company_id_i'
                            ,[department_id] = '$department_id_i',[position_id] = '$position_id_i'
                        WHERE 
                            [emp_code] = '$emp_code_i'";
        }
        sqlsrv_query($cnx, $query_k);
    }
?>
<script>
   window.open('','_self','rh_employee_gen.php'); window.close();
</script>
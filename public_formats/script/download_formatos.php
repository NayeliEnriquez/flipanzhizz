<?php
    //ini_set('max_execution_time', 0); //0=NOLIMIT
    ini_set('display_errors', 0);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    
    function saber_dia($nombredia) {
        $dias = array('', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }

    $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    $destino = "file/";
	//Eliminar archivos descargados
    $files1 = glob($destino.'*.pdf'); //obtenemos todos los PDF
    foreach($files1 as $file){
        if(is_file($file)){
            unlink($file); //elimino el fichero
        }
    }
    require_once '../../html2pdf_v4.03/html2pdf.class.php';

    $slc_formato = $_POST['slc_formato'];
    $inp_folio = $_POST['inp_folio'];
    $inp_num_empl_h = $_POST['inp_num_empl_h'];

    $query_b = "
        SELECT
            personnel_employee.id,
            personnel_employee.first_name, personnel_employee.last_name, personnel_employee.department_id,
            personnel_employee.position_id, personnel_department.dept_name, personnel_position.position_name,
            dbo.personnel_employee.hire_date
        FROM
            personnel_employee
        INNER JOIN
            personnel_department
        ON
            personnel_employee.department_id = personnel_department.id
        INNER JOIN
            personnel_position
        ON
            personnel_employee.position_id = personnel_position.id
        WHERE
            personnel_employee.emp_code = '$inp_num_empl_h'
    ";
    $exe_b = sqlsrv_query($cnx, $query_b);
    $fila_b = sqlsrv_fetch_array($exe_b, SQLSRV_FETCH_ASSOC);
    $first_name_b = $fila_b['first_name'];
    $last_name_b = $fila_b['last_name'];
    $department_id_b = $fila_b['department_id'];
    $dept_name_b = $fila_b['dept_name'];
    $position_id_b = $fila_b['position_id'];
    $position_name_b = $fila_b['position_name'];
    $hire_date_b = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_b['hire_date']))));
    $hire_date_b = substr($hire_date_b, 5, 10);

    $query_a = "SELECT * FROM $slc_formato WHERE id = '$inp_folio' AND id_empleado = '$inp_num_empl_h'";
    $exe_a = sqlsrv_query($cnx, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    switch ($slc_formato) {
        case 'rh_solicitudes':
            $tipo_ausencia_a = $fila_a['tipo_ausencia'];
            $f_ini_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini']))));
            $f_ini_a = substr($f_ini_a, 5, 10);
            $f_fin_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin']))));
            $f_fin_a = substr($f_fin_a, 5, 10);
            $fechas_i_f = ($f_ini_a == $f_fin_a) ? "El dia <strong>".saber_dia($f_ini_a)." ".date('j', strtotime($f_ini_a))." de ".$meses[date('n', strtotime($f_ini_a))]." del ".date('Y', strtotime($f_ini_a))."</strong>" : "Del <strong>".saber_dia($f_ini_a)." ".date('j', strtotime($f_ini_a))." de ".$meses[date('n', strtotime($f_ini_a))]." del ".date('Y', strtotime($f_ini_a))."</strong> al <strong>".saber_dia($f_fin_a)." ".date('j', strtotime($f_fin_a))." de ".$meses[date('n', strtotime($f_fin_a))]." del ".date('y', strtotime($f_fin_a))."</strong>";

            $tipo_goce_a = $fila_a['tipo_goce'];
            $observaciones_a = $fila_a['observaciones'];
            $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
            $f_solicitud_a = substr($f_solicitud_a, 5, 10);
            $id_solicitante_a = $fila_a['id_solicitante'];
            $id_depto_a = $fila_a['id_depto'];
            $estatus_a = $fila_a['estatus'];
            $historico_a = $fila_a['historico'];

            $txt_a = "Número empleado: <u><strong>".$inp_num_empl_h."</strong></u> Nombre: <u><strong>".$first_name_b." ".$last_name_b."</strong></u>";

            $strHTML = '
                <style>
                    td, th {
                        //width: auto;
                        //border: 1px solid;
                        text-align: center;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                </style>
                <page backleft="5mm">
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>CONTROL DE AUSENCIAS DE PERSONAL
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: left;">Ciudad de México, a <strong>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</strong></td>
                                <td style="text-align: right;">Area: <strong><u>'.$dept_name_b.'</u></strong></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" width="748" colspan="2"><br><br>'.$txt_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="3" width="748" style="font-size: 16px;"><strong>'.$tipo_ausencia_a.'</strong></td>
                            </tr>
                            <tr>
                                <td width="244">'.$fechas_i_f.'</td>
                                <td width="244"></td>
                                <td width="244">'.$tipo_goce_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">'.$observaciones_a.'<br><br><u>'.$historico_a.'</u></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            <tr>
                                <td width="244">Autorizó Gcia. o jefe de área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="244">Recibió Vigilancia<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="244">Recibió Recursos humanos<br><br><br><br>______________________<br>Nombre y firma</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--PARTE 2-->
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>CONTROL DE AUSENCIAS DE PERSONAL
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: left;">Ciudad de México, a <strong>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</strong></td>
                                <td style="text-align: right;">Area: <strong><u>'.$dept_name_b.'</u></strong></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" width="748" colspan="2"><br><br>'.$txt_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="3" width="748" style="font-size: 16px;"><strong>'.$tipo_ausencia_a.'</strong></td>
                            </tr>
                            <tr>
                                <td width="244">'.$fechas_i_f.'</td>
                                <td width="244"></td>
                                <td width="244">'.$tipo_goce_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">'.$observaciones_a.'<br><br><u>'.$historico_a.'</u></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            <tr>
                                <td width="244">Autorizó Gcia. o jefe de área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="244">Recibió Vigilancia<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="244">Recibió Recursos humanos<br><br><br><br>______________________<br>Nombre y firma</td>
                            </tr>
                        </tbody>
                    </table>
                </page>';
            break;
        
        case 'rh_salida':
            $tipo_ausencia_a = $fila_a['tipo_ausencia'];
            $in_out_a = $fila_a['in_out'];
            $asunto_a = $fila_a['asunto'];
            $asunto_a_txt = ($asunto_a == '1') ? "Asunto de trabajo" : "Asunto personal" ;
            $sueldo_a = $fila_a['sueldo'];
            $sueldo_a_txt = ($sueldo_a == '1') ? "Permiso con goce de sueldo" : "Permiso sin goce de sueldo" ;
            $f_permiso_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_permiso'])))), 5, 10);
            $h_permiso_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_permiso'])))), 16, 8);
            $observaciones_a = $fila_a['observaciones'];
            $reposicion_a = $fila_a['reposicion'];
            $repo_fecha = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_h_repo'])))), 5, 10);
            $repo_hora = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_h_repo'])))), 16, 5);
            $hrs_repo = $fila_a['hrs_repo'];
            $reposicion_a_txt = ($reposicion_a == '0') ? "" : "<br><table><tbody><tr><td>El <strong>$repo_fecha</strong> a las <strong>$repo_hora</strong> hrs, repondra un total de <strong>$hrs_repo</strong> horas.</td></tr></tbody></table>";
            $f_solicitud_a = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud'])))), 5, 10);
            $historico_a = $fila_a['historico'];

            switch ($tipo_ausencia_a) {
                case 'Salida':
                    $txt_a = "Permitir salir a Num Emp: <u><strong>".$inp_num_empl_h."</strong></u> Nombre: <u><strong>".$first_name_b." ".$last_name_b."</strong></u>";
                    //$txt_b = "Salió a las <strong><u>".$h_permiso_a." horas</u></strong>";
                    $txt_b = "Sale el <u>".saber_dia($f_permiso_a)." ".date('j', strtotime($f_permiso_a))." de ".$meses[date('n', strtotime($f_permiso_a))]." del ".date('y', strtotime($f_permiso_a))." a las ".$h_permiso_a." horas.</u>";
                    break;
                
                default:
                    $txt_a = "Permitir entrar a Num Emp: <u><strong>".$inp_num_empl_h."</strong></u> Nombre: <u><strong>".$first_name_b." ".$last_name_b."</strong></u>";
                    //$txt_b = "Entra a las <strong><u>".$h_permiso_a." horas</u></strong>";
                    $txt_b = "Entra el <u>".saber_dia($f_permiso_a)." ".date('j', strtotime($f_permiso_a))." de ".$meses[date('n', strtotime($f_permiso_a))]." del ".date('y', strtotime($f_permiso_a))." a las ".$h_permiso_a." horas.</u>";
                    break;
            }
            
            $strHTML = '
                <style>
                    td, th {
                        //width: auto;
                        //border: 1px solid;
                        text-align: center;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                </style>';
            $strHTML .= '
                <page backleft="5mm">
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>PERMISO DE SALIDA Y ENTRADA
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: left;">PERSONAL DE VIGILANCIA,<br>PRESENTE</td>
                                <td style="text-align: right;">FECHA: <strong><u>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</u></strong></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" width="748" colspan="2"><br><br>'.$txt_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="3" width="748" style="font-size: 16px;">Detalles</td>
                            </tr>
                            <tr>
                                <td width="244">'.$txt_b.'</td>
                                <td width="244">'.$asunto_a_txt.'</td>
                                <td width="244">'.$sueldo_a_txt.'</td>
                            </tr>
                        </tbody>
                    </table>
                    '.$reposicion_a_txt.'
                    <br><br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">'.$observaciones_a.'<br><br><u>'.$historico_a.'</u></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            <tr>
                                <td width="183">Elaboro Área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Autorizó Gcia. o jefe de área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Recibió Vigilancia<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Recibió Recursos humanos<br><br><br><br>______________________<br>Nombre y firma</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--PARTE 2-->
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>PERMISO DE SALIDA Y ENTRADA
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: left;">PERSONAL DE VIGILANCIA,<br>PRESENTE</td>
                                <td style="text-align: right;">FECHA: <strong><u>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</u></strong></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;" width="748" colspan="2"><br><br>'.$txt_a.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="3" width="748" style="font-size: 16px;">Detalles</td>
                            </tr>
                            <tr>
                                <td width="244">'.$txt_b.'</td>
                                <td width="244">'.$asunto_a_txt.'</td>
                                <td width="244">'.$sueldo_a_txt.'</td>
                            </tr>
                        </tbody>
                    </table>
                    '.$reposicion_a_txt.'
                    <br><br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">'.$observaciones_a.'<br><br><u>'.$historico_a.'</u></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            <tr>
                                <td width="183">Elaboro Área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Autorizó Gcia. o jefe de área<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Recibió Vigilancia<br><br><br><br>______________________<br>Nombre y firma</td>
                                <td width="183">Recibió Recursos humanos<br><br><br><br>______________________<br>Nombre y firma</td>
                            </tr>
                        </tbody>
                    </table>
                </page>';

            break;

        case 'rh_vacaciones':
            $f_ini_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ini']))));
            $f_ini_v = substr($f_ini_v, 5, 10);
            $f_fin_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_fin']))));
            $f_fin_v = substr($f_fin_v, 5, 10);
            $h_in_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_in']))));
            $h_in_v = substr($h_in_v, 16, 8);
            $h_out_v = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['h_out']))));
            $h_out_v = substr($h_out_v, 16, 8);
            $hire_date_1 = date("d-m-Y", strtotime(substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_ingreso'])))), 5, 10)));
            $observaciones_a = $fila_a['observaciones'];
            $f_solicitud_a = str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['f_solicitud']))));
            $f_solicitud_a = substr($f_solicitud_a, 5, 10);
            $year_solicitud_a = substr($f_solicitud_a, 0, 4);
            $dias_vac_a = $fila_a['dias_vac'];
            $historico_a = $fila_a['historico'];
            $fecha_array_v = explode("|", substr($fila_a['fecha_array'], 0, -1));
            $txt_dias = "";
            $txt_dias_a = "";
            $txt_dias_b = "";
            $txt_dias_c = "";
            $txt_dias_d = "";
            $tam_fecha_array = sizeof($fecha_array_v);
            if ($tam_fecha_array > 1) {
               $primer_fecha = $fecha_array_v[array_key_first($fecha_array_v)];
               $primer_fecha_arr = explode("-", $primer_fecha);
               $primer_mes = $primer_fecha_arr[1];
               $primer_year = $primer_fecha_arr[0];
               $ultima_fecha = end($fecha_array_v);
               $ultima_fecha_arr = explode("-", $ultima_fecha);
               $ultima_mes = $ultima_fecha_arr[1];
               $ultima_year = $ultima_fecha_arr[0];
               foreach ($fecha_array_v as $key => $value) {
                  $las_fechas = explode("-", $value);
                  if ($primer_mes == $las_fechas[1]) {
                     $txt_dias_a .= saber_dia($value)." ".$las_fechas[2].", ";
                  }else{
                     $txt_dias_b .= saber_dia($value)." ".$las_fechas[2].", ";
                  }
               }

               if (!empty($txt_dias_b)) {
                  $txt_dias_b = substr($txt_dias_b, 0, -2);
                  $txt_dias_b = ", ".$txt_dias_b." de ".$meses[date('n', strtotime($ultima_fecha))];
               }

               if ($primer_year != $ultima_year) {
                  $txt_dias_c = " del ".$ultima_year;
                  $txt_dias_d = " del ".$primer_year;
               }else{
                  $txt_dias_c = " del ".$primer_year;
               }

               $txt_dias_a = substr($txt_dias_a, 0, -2);
               $txt_dias = $txt_dias_a." de ".$meses[date('n', strtotime($primer_fecha))].$txt_dias_d.$txt_dias_b.$txt_dias_c;
            } else {
               foreach ($fecha_array_v as $key => $value) {
                  $txt_dias = saber_dia($value)." ".date('j', strtotime($value))." de ".$meses[date('n', strtotime($value))]." del ".date('Y', strtotime($value))."";
               }
            }

            $fecha_regreso_v = substr(str_replace('"', '', str_replace("}", "", str_replace("{", "", json_encode($fila_a['fecha_regreso'])))), 5, 10);

            $strHTML = '
                <style>
                    td, th {
                        //width: auto;
                        //border: 1px solid;
                        text-align: center;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                </style>
                <page backleft="5mm">
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>AUTORIZACIÓN DE VACACIONES
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: right;">Ciudad de México, a <strong>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 14px;">Por medio de este conducto le solicito se me autorice disfrutar de <strong>'.$dias_vac_a.'</strong> días de vacaciones correspondiente al año <strong>'.$year_solicitud_a.'</strong>, los dias <span style="background-color: #79D864;">'.$txt_dias.'</span>, reincorporandome a mis labores el día '.saber_dia($fecha_regreso_v)." ".date('j', strtotime($fecha_regreso_v))." de ".$meses[date('n', strtotime($fecha_regreso_v))]." del ".date('Y', strtotime($fecha_regreso_v)).'.</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table style="font-size: 12px;">
                        <tbody>
                            <tr>
                                <td width="auto" colspan="2">Datos</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Nombre</td>
                                <td style="text-align: left;">'.$last_name_b.' '.$first_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Clave de empleado</td>
                                <td style="text-align: left;">'.$inp_num_empl_h.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Fecha de ingreso</td>
                                <td style="text-align: left;">'.$hire_date_1.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Puesto</td>
                                <td style="text-align: left;">'.$position_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Área de adscripción</td>
                                <td style="text-align: left;">'.$dept_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Horario de trabajo</td>
                                <td style="text-align: left;">Entrada: '.$h_in_v.' Salida: '.$h_out_v.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">
                                    '.$observaciones_a.'
                                    <br><u>'.$historico_a.'</u>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            
                            <tr>
                                <td width="244">Atentamente<br><br><br><br>______________________<br>Firma y nombre del trabajador</td>
                                <td width="244">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe inmediato</td>
                                <td width="244">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma gerente o director</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--PARTE 2-->
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="158" style="text-align: center; font-size: 20px;">
                                    <img src="../../images/novag_logo_bn.png" style="width: 140px; height: 55px;"/>
                                </td>
                                <td width="535" style="text-align: center; font-size: 20px;">
                                    <strong>NOVAG INFANCIA, S.A. DE C.V.</strong><br>AUTORIZACIÓN DE VACACIONES
                                </td>
                                <td width="50" style="text-align: center; font-size: 12px;">
                                    <strong>Folio '.$inp_folio.'</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: right;">Ciudad de México, a <strong>'.saber_dia($f_solicitud_a)." ".date('j', strtotime($f_solicitud_a))." de ".$meses[date('n', strtotime($f_solicitud_a))]." del ".date('y', strtotime($f_solicitud_a)).'</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 14px;">Por medio de este conducto le solicito se me autorice disfrutar de <strong>'.$dias_vac_a.'</strong> días de vacaciones correspondiente al año <strong>'.$year_solicitud_a.'</strong>, los dias <span style="background-color: #79D864;">'.$txt_dias.'</span>, reincorporandome a mis labores el día '.saber_dia($fecha_regreso_v)." ".date('j', strtotime($fecha_regreso_v))." de ".$meses[date('n', strtotime($fecha_regreso_v))]." del ".date('Y', strtotime($fecha_regreso_v)).'.</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table style="font-size: 12px;">
                        <tbody>
                            <tr>
                                <td width="auto" colspan="2">Datos</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Nombre</td>
                                <td style="text-align: left;">'.$last_name_b.' '.$first_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Clave de empleado</td>
                                <td style="text-align: left;">'.$inp_num_empl_h.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Fecha de ingreso</td>
                                <td style="text-align: left;">'.$hire_date_1.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Puesto</td>
                                <td style="text-align: left;">'.$position_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Área de adscripción</td>
                                <td style="text-align: left;">'.$dept_name_b.'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Horario de trabajo</td>
                                <td style="text-align: left;">Entrada: '.$h_in_v.' Salida: '.$h_out_v.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td width="748" style="font-size: 16px;">Observaciones</td>
                            </tr>
                            <tr>
                                <td style="font-style: italic;">
                                    '.$observaciones_a.'
                                    <br><u>'.$historico_a.'</u>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tbody>
                            
                            <tr>
                                <td width="244">Atentamente<br><br><br><br>______________________<br>Firma y nombre del trabajador</td>
                                <td width="244">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma jefe inmediato</td>
                                <td width="244">Vo. Bo.<br><br><br><br>______________________<br>Nombre y firma gerente o director</td>
                            </tr>
                        </tbody>
                    </table>
                </page>';
            break;
    }

    $fichero_pdf = $destino.'formatoId_'.$inp_folio.'.pdf';
	$html2pdf = new HTML2PDF('P', 'Letter', 'es', 'true', 'UTF-8');
	//***L -> Horizontal | P -> Vertical || A5 -> Media carta | Letter -> Carta
    $html2pdf->writeHTML($strHTML);
    $html2pdf->Output($fichero_pdf,'F');
    echo ($fichero_pdf);
?>
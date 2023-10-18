<?php
	//date_default_timezone_set("America/Mazatlan");
    //*****************************************************************************************
	$serverName_a = "SRVFORMWEIGH\DESARROLLOS"; //serverName\instanceName
	$connectionInfo_a = array( "Database"=>"rh_novag_system", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb");
	//$connectionInfo_a = array( "Database"=>"rh_novag_system", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb2023");
	$cnx = sqlsrv_connect($serverName_a, $connectionInfo_a);

	//*****************************************************************************************
	$serverName_b = "PRINT-SERVER\SQLEXPRESS"; //serverName\instanceName
	//$connectionInfo_a = array( "Database"=>"zkbiotime", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb");
	$connectionInfo_b = array( "Database"=>"zkbiotime", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb2023");
	$conn = sqlsrv_connect($serverName_b, $connectionInfo_b);

	//*****************************************************************************************
	$serverName_c = "PRINT-SERVER\SQLEXPRESS"; //serverName\instanceName
	//$connectionInfo_a = array( "Database"=>"zkbiotime", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb");
	$connectionInfo_c = array( "Database"=>"NOMINAS", "UID"=>"rh_system_web", "PWD"=>"@@RH_sysWeb2023");
	$conx = sqlsrv_connect($serverName_c, $connectionInfo_c);

	//*****************************************************************************************
	$serverName = "NOVAGEPICOR02\NOVAGEPICOR02"; //serverName\instanceName
	//$connectionInfo = array( "Database"=>"LiveDB", "UID"=>"ramon_test", "PWD"=>"@@test_2022");
	$connectionInfo = array( "Database"=>"LiveDB", "UID"=>"ramon_test", "PWD"=>"@@Alm_2023");
	$cone = sqlsrv_connect($serverName, $connectionInfo);

	/*if($cone) {
		echo "Conexión establecida.<br />";
	}else{
		echo "Conexión no se pudo establecer.<br />";
		die(print_r(sqlsrv_errors(), true));
	}*/
?>
<?php
	include "../receive/physical_cal.php";
	include "database.php";
	// ensure the process would not stop by system
	set_time_limit(6000);

	// DataBase Config
			$dbtype_sql = 'mysql';
			$host_sql = 'localhost';
			$dbname_sql = 'fallDetect';
			$username_sql = 'fallDetect';
			$password_sql = 'EtXpphf2bQ78QGBJ';

	// Connection DataBase
	try{

	    $dbh = new PDO($dbtype_sql . ':host=' . $host_sql . ';dbname=' . $dbname_sql, $username_sql, $password_sql);
	    $dbh->query('SET NAMES UTF8');
	    echo 'Connect Successfully!<br/>';

	}catch(PDOException $e) {
	    echo 'Error!: ' . $e->getMessage() . '<br />';
	}

	// $gyroCols = array('`gyro_x`','`gyro_y`','`gyro_z`');
	// $gyros = getPrimitivesCols($dbh, $colsName);

	$AVCols = array('base_id','`gyro_x`','`gyro_y`','`gyro_z`','`acc_x`','`acc_y`','`acc_z`');
	$AVinputs = getPrimitivesCols($dbh, $AVCols);
	var_dump($AVinputs);
	// CAUTION: the time of sensor sample is 400 per 3 sec in configures
	// But in reality we do not know the real time eslispes
	// 1. It could generate some error in estimating time
	// 2. Initial angular displacement may not be 0

	// storeCalResult($dbh, '`AV`', cal_AV(), 1452);
	// print_r($AVinputs);
	// foreach($AVinputs as $k => $v){
	// 	storeCalResult($dbh, '`AV`', cal_AV(), 1452);
	// }
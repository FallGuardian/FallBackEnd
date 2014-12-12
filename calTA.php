<?php
	
	#primitive

	ini_set('memory_limit','1280M');

	include "database.php";
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
		echo 'Connect Successfully!';

	}catch(PDOException $e) {
		echo 'Error!: ' . $e->getMessage() . '<br />';
	}

	
	

	# Get fail_id (true fall, system detected)
	$fall_id = getFallIds($dbh);

	# Get stand_id (mis-judge to fall, not fall, but system detect)
	$stand_id = getStandIds($dbh);

	$sql2 = "INSERT INTO `lab_primitive2`
	(`base_id`, `id`, `acc_x`, `acc_y`, `acc_z`, 
		`gyro_x`, `gyro_y`, `gyro_z`, `time_acc`, `time_gyro`, 
		`SVM`, `AV`, `gravity_x`, `gravity_y`, `gravity_z`, 
		`TA`, `SMA`, `label`) VALUES
	 (?,?,?,?,?,
	 	?,?,?,?,?,
	 	?,?,?,?,?,
	 	?,?,?)";
	
	$datas = getPrimitiveAll($dbh);
	
	foreach ($datas as $key => $data) {
		
		#baseId generate
		$base_id = floor((intval($data['id'])-1)/400);
		
		#label generate
		$label = 0;
		if(array_search($base_id, $fall_id)){
			$label = 1;
		}
		elseif (array_search($base_id, $stand_id)) {
			$label = -1;	
		}

		$dbStatement = $dbh->prepare($sql2);
		$dbStatement->bindParam(1,$base_id);
		$dbStatement->bindParam(2,$data['id']);
		$dbStatement->bindParam(3,$data['acc_x']);
		$dbStatement->bindParam(4,$data['acc_y']);
		$dbStatement->bindParam(5,$data['acc_z']);
		$dbStatement->bindParam(6,$data['gyro_x']);
		$dbStatement->bindParam(7,$data['gyro_y']);
		$dbStatement->bindParam(8,$data['gyro_z']);
		$dbStatement->bindParam(9,$data['time_acc']);
		$dbStatement->bindParam(10,$data['time_gyro']);
		$dbStatement->bindParam(11,$data['SVM']);
		$dbStatement->bindParam(12,$data['AV']);
		$dbStatement->bindParam(13,$data['gravity_x']);
		$dbStatement->bindParam(14,$data['gravity_y']);
		$dbStatement->bindParam(15,$data['gravity_z']);
		$dbStatement->bindParam(16,$data['TA']);
		$dbStatement->bindParam(17,$data['SMA']);
		$dbStatement->bindParam(18,$label);

		try{
		  	$dbStatement->execute();
			
		} catch (PDOException $e) {
		    print "errorMsg: ".$e->getMessage()."<br />";
		    print "Line: ".$e->getLine()."<br />";
		    die();
		}
	}

	echo 'task finished!';
	
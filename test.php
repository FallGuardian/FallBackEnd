<?php 

	include 'databaseIdMaintain.php';
	include 'physical_cal.php';

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
	$date = date("Y-m-d H:i:s");
	// echo $date;

	define('DATA_PER_PERIOD','400');
	
	// Pre-process of $_POST variable
	// This section is array variable
	$acc_x = $_POST["x"];
	$acc_y = $_POST["y"];
	$acc_z = $_POST["z"];
	$acc_time = $_POST["time"];
	
	$gyro_x = $_POST["gyrox"];
	$gyro_y = $_POST["gyroy"];
	$gyro_z = $_POST["gyroz"];
	$gyro_time = $_POST["gyrotime"];

	$gravity_x=$_POST["grx"];
	$gravity_y=$_POST["gry"];
	$gravity_z=$_POST["grz"];
	// $grt=$_POST["grt"];


	// Profile of user
	// This section is single variable
	$age = $_POST["birth"];
	$weight = $_POST["weight"];
	$height = $_POST["height"];
	$sex = $_POST["sex"];

	// profile of phone
	$pos = $_POST["pos"];	// where user place
	$phonebrand = $_POST["brand"];

	$label = $_POST['fell'];

	// Get base_id From `final_primitive`
	autoIncreaseBaseId($dbh);
	$newBaseId = getBaseId($dbh);


	// Insert raw falldata into `final_primitive` Table 
	$insertSQL = "INSERT INTO `final_primitive_total`(
		`base_id`, 
		`id`, 
		`acc_x`, 
		`acc_y`, 
		`acc_z`, 
		`gyro_x`, 
		`gyro_y`, 
		`gyro_z`, 
		`time_acc`, 
		`time_gyro`, 
		`gravity_x`, 
		`gravity_y`, 
		`gravity_z`, 
		`label`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$statement = $dbh->prepare($insertSQL);
	for ($i=0; $i < DATA_PER_PERIOD; $i++) { 

		$newId = $newBaseId*400+1+$i;
		$statement->bindParam(1,$newBaseId);
		$statement->bindParam(2,$newId);
		$statement->bindParam(3,$acc_x[$i]);
		$statement->bindParam(4,$acc_y[$i]);
		$statement->bindParam(5,$acc_z[$i]);
		$statement->bindParam(6,$gyro_x[$i]);
		$statement->bindParam(7,$gyro_y[$i]);
		$statement->bindParam(8,$gyro_z[$i]);
		$statement->bindParam(9,$acc_time[$i]);
		$statement->bindParam(10,$gyro_time[$i]);
		$statement->bindParam(11,$gravity_x[$i]);
		$statement->bindParam(12,$gravity_y[$i]);
		$statement->bindParam(13,$gravity_z[$i]);
		$statement->bindParam(14,$label);
		$statement->execute();
			
	}
	

	// Physical calculation function and insert into `physic_cal` TABLE
	// Including 
	// (1)SVM:
	// (2)TA: degree
	// (3)SMA:
	// (4)AV:
	// (5)V,integral of acc(velocity):
	$insertSQL2 = 'INSERT INTO `final_physical_cal`(
		`base_id`, 
		`id`, 
		`SVM`, 
		`TA`, 
		`SMA`, 
		`AV`, 
		`V`, 
		`label`) VALUES (?,?,?,?,?,?,?,?)';

	$statement2 = $dbh->prepare($insertSQL2);
	$SMA = cal_SMA_by_array($acc_x, $acc_y, $acc_z);
	$V = cal_velocity_by_array($acc_x, $acc_y, $acc_z, DATA_PER_PERIOD, 3);
	$AV = 0;
	for ($i=0; $i < DATA_PER_PERIOD; $i++) { 
		
		$newId = $newBaseId*400+1+$i;
		$statement2->bindParam(1, $newBaseId);
		$statement2->bindParam(2, $newId);
		$statement2->bindParam(3, cal_SVM($acc_x[$i],$acc_y[$i],$acc_z[$i]));
		$statement2->bindParam(4, cal_TA($acc_x[$i],$acc_y[$i],$acc_z[$i]));
		$statement2->bindParam(5, $SMA);
		$statement2->bindParam(6, $AV);
		$statement2->bindParam(7, $V);
		$statement2->bindParam(8, $label);
		$statement2->execute();
		
	}

	// User and Phone profile classify and record
	// $sql3 = "INSERT INTO `final_profile` (`test`) VALUES (?)";
	// $test = $newBaseId."/".$age."/".$weight."/".$height."/"
	// 		.$sex."/".$pos."/".$phonebrand;
	// $statement3 = $dbh->prepare($sql3);
	// $statement3->bindParam(1,$test);
	
	
	// $sql3 = "INSERT INTO `final_profile`(
	// 	`base_id`, 
	// 	`age`, 
	// 	`weight`, 
	// 	`height`, 
	// 	`sex`, 
	// 	`position`, 
	// 	`phoneBrand`) VALUES (?,?,?,?,?,?,?)";
	// $statement3 = $dbh->prepare($sql3);
	// $statement3->bindParam(1,$newBaseId);
	// $statement3->bindParam(2,$age);
	// $statement3->bindParam(3,$weight);
	// $statement3->bindParam(4,$height);
	// $statement3->bindParam(5,$sex);
	// $statement3->bindParam(6,$pos);
	// $statement3->bindParam(7,$phonebrand);
	
	// $rst = $statement3->execute();

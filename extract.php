<?php 
	include 'database.php';
	function inArray($needle, $haystack)
	{
		return in_array($needle, $haystack)?1:-1;
	}
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
	// $adjust_id = getAdjustId($dbh);
	// $str="";
	// foreach ($adjust_id as $key => $id) {
	// 	$str.=$id.',';
	// }
	// echo $str;

	$fall_id = getFallIds($dbh);
	echo 'fall_id count: '.count($fall_id).'<br>';
	
	$stand_id = getStandIds($dbh);
	echo 'stand_id count: '.count($stand_id).'<br>';

	$base_ids = getBaseIds($dbh);
	echo 'base_id count: '.count($base_ids).'<br>';

	$formatedBase_ids = getFormatedBaseIds($dbh);
	echo 'formatedBase_ids count: '.count($formatedBase_ids).'<br>';

	$compareRst = array_diff($formatedBase_ids, $base_ids);
	// var_dump($compareRst);
	echo 'compareRst count: '.count($compareRst).'<br>';
	foreach ($compareRst as $key => $id) {
		# code...
		echo $id.',';
	}
	// foreach ($formatedBase_ids as $key => $id) {
		
	// 	if(in_array($id, $fall_id)){
	// 		$rst[$id] = updateLabel($dbh, 1, $id);
	// 	}
	// 	else if(in_array($id, $stand_id)){
	// 		$rst[$id] = updateLabel($dbh, -1, $id);
	// 	}else{
	// 		$str.=$id.',';
	// 	}
	// }
	// echo 'no lable data<br>';
	// echo $str;



	$dbh = null;
?>
<?php
	// var_dump(explode(',', $_POST['deleteIds']));
	$deleteIds = explode(',', $_POST['deleteIds']);
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
	$sql="DELETE FROM `lab_primitive` WHERE `id` >= 1+400*? AND `id` =< 400+400*?";

	foreach ($deleteIds as $key => $value) {
		$statement = $dbh->prepare($sql);
		$statement->bindValue(1,$value);
		$statement->bindValue(2,$value);
		$rst[$value] = $statement->execute();
	}
	var_dump($rst);

	$dbh = null;


?>
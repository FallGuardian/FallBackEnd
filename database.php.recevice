<?php
	function autoIncreaseBaseId($dbh){
		// AUTO_INCREATE id
		$sql = "INSERT INTO id_base() values();";
	    $statement = $dbh->prepare($sql);
	    $statement->execute();
	}
	function getBaseId($dbh){

	    $sql2 = "SELECT MAX(`id`) FROM `id_base`";
	    $rst = $dbh->query($sql2)->fetchAll(PDO::FETCH_NAMED);
	    var_dump($rst);
	    return $rst[0]['MAX(`id`)'];
	}
	function checkProfileExist($dbh){
		
	}

?>
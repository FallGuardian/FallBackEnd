<?php 
	function findOverThesholdInPrimitive( $dbh, $baseId, $TA, $SVM )
	{
		// transfer to the base_id to the range of detail data range
		$start = $baseId * 400 + 1;
		$end = $baseId * 400 + 400;

		//$sql="SELECT count(*) FROM `lab_primitive` WHERE `id`>? AND `id`<=? AND `TA`>=? AND `SMA`>=? AND `SVM`>=?";
		$sql="SELECT count(*) FROM `final_physical_cal` WHERE `id`>=? AND `id`<=? AND `TA`>=? AND `SVM`>=?";
		$dbStatement = $dbh->prepare($sql);
		$dbStatement->bindParam(1, $start, PDO::PARAM_INT);
		$dbStatement->bindParam(2, $end, PDO::PARAM_INT);
		$dbStatement->bindParam(3, $TA, PDO::PARAM_INT);
		//$dbStatement->bindParam(4, $SMA, PDO::PARAM_STR);
		$dbStatement->bindParam(4, $SVM, PDO::PARAM_INT);
		$dbStatement->execute();
		$data = $dbStatement->fetchAll();
		return $data[0]["count(*)"];
	}


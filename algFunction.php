<?php 
	function findOverThesholdInPrimitive( $dbh, $baseId, $TA, $SVM )
	{
		// transfer to the base_id to the range of detail data range
		$start = $baseId * 400 + 1;
		$end = $baseId * 400 + 400;

		//$sql="SELECT count(*) FROM `lab_primitive` WHERE `id`>? AND `id`<=? AND `TA`>=? AND `SMA`>=? AND `SVM`>=?";
		$sql="SELECT count(*) FROM `lab_primitive` WHERE `id`>=? AND `id`<=? AND `TA`>=? AND `SVM`>=?";
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


	function getTA( $acc_x, $acc_y, $acc_z )
	{
		return asin($acc_y/sqrt(pow($acc_x,2)+pow($acc_y,2)+pow($acc_z,2)));
		//(SELECT ASIN(`acc_y` /SQRT(POW(`acc_x`,2)+POW(`acc_y`,2)+POW(`acc_z`,2))) FROM `lab_primitive` )
	}

 	function getSMA($xAccArr, $yAccArr, $zAccArr)
	{	
		$xSum = 0;
		$ySum = 0;
		$zSum = 0;
		$size = sizeof($xAccArr);
		
		for ($i=0; $i < 400; $i++) { 
			# code...
			$xSum+=$xAccArr[$i];
			$ySum+=$yAccArr[$i];
			$zSum+=$zAccArr[$i];
		}
		return ($xSum+$ySum+$zSum)/400;
	}



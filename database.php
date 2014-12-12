<?php
	function getFallIds($dbh)
	{
		# Get fail_id (true fall, system detected)
		$sql2="SELECT `id` FROM `fall_id`";
		$t_f_data_raw = $dbh->query($sql2)->fetchAll(PDO::FETCH_NAMED);
		$t_f_data = array();
		foreach ($t_f_data_raw as $key => $value) {
			# code...
			array_push($t_f_data, $value['id']);
		}
		return $t_f_data;
	}
	function getStandIds($dbh)
	{
		# Get stand_id (mis-judge to fall, not fall, but system detect)
		$sql3="SELECT `id` FROM `stand_id`";
		$m_j_f_data_raw = $dbh->query($sql3)->fetchAll(PDO::FETCH_ASSOC);
		$m_j_f_data = array();
		foreach ($m_j_f_data_raw as $key => $value) {
			# code...
			array_push($m_j_f_data, $value['id']);
		}
		return $m_j_f_data;
	}
	function getAdjustId($dbh)
	{
		$sql="SELECT * FROM `adjust_id`";
		$allBaseId = $dbh->query($sql)->fetchAll();
		$data = array();
		foreach ($allBaseId as $key => $value) {
			# code...
			array_push($data, $value['id']);
		}
		return $data;
	}
	function getBaseIds($dbh)
	{
		# get base ID 
		$sql="SELECT DISTINCT * FROM `id_base`";
		$allBaseId = $dbh->query($sql)->fetchAll();
		$data = array();
		foreach ($allBaseId as $key => $value) {
			# code...
			array_push($data, $value['id']);
		}
		return $data;
	}
	function updateLabel($dbh, $label, $id)
	{
		$start = $id*400+1;
		$end = $id*400+400;
		$sql="UPDATE `formated_data` SET `label`= ? WHERE `id`>=? AND `id`<=?";
		$statement = $dbh->prepare($sql);
		$statement->bindParam(1, $label);
		$statement->bindParam(2, $start);
		$statement->bindParam(3, $end);
		return $statement->execute();
	}
	function getFormatedBaseIds($dbh)
	{
		$sql="SELECT DISTINCT `base_id` FROM `formated_data`";
		$formatedBaseIds = $dbh->query($sql)->fetchAll();
		$data = array();
		foreach ($formatedBaseIds as $key => $value) {
			# code...
			array_push($data, $value['base_id']);
		}
		return $data;
	}
	function getFormatedData($dbh)
	{
		$sql="SELECT  FROM `formated_data` ORDER BY `id` ";
		return $dbh->query($sql)->fetchAll();
	}
	function getFormatedCol($dbh, $colName)
	{
		$sql="SELECT `".$colName."` FROM `formated_data`";
		$queryData = $dbh->query($sql)->fetchAll();
		$data = array();
		foreach ($queryData as $key => $value) {
			# code...
			array_push($data, $value[$colName]);
		}
		return $data;
	}
	function getParaAVG($dbh)
	{
		$sql = "SELECT AVG(`TA`),AVG(`SMA`),AVG(`SVM`) FROM `lab_primitive` ";
		$dbStatement = $dbh->prepare($sql);
		$dbStatement->execute();
		return $dbStatement->fetchAll(PDO::FETCH_ASSOC);

	}
	function getSingleData($dbh, $id)
	{
		$sql="SELECT * FROM `id_base` WHERE `id`=?";
		$dbStatement = $dbh->prepare($sql);
		$dbStatement->bindParam(1,$id);
		$dbStatement->execute();
		$data = $dbStatement->fetchAll(PDO::FETCH_ASSOC);
		return $data[0]['id'];
	}
	function getPrimitiveAll($dbh)
	{
		$sql="SELECT * FROM `lab_primitive` ORDER BY `id`";
		$dbStatement = $dbh->prepare($sql);
		$dbStatement->execute();
		return $dbStatement->fetchAll(PDO::FETCH_ASSOC);
	}
?> 
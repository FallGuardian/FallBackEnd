<?php 

	function cal_SVM($acc_x, $acc_y, $acc_z){
		return sqrt(pow($acc_x,2)+pow($acc_y,2)+pow($acc_z,2));
	}
	function cal_TA($acc_x, $acc_y, $acc_z){
		return 180*asin($acc_y/sqrt(pow($acc_x,2)+pow($acc_y,2)+pow($acc_z,2)))/pi();
	}
	function cal_AV($acc_x, $acc_y, $acc_z, $gyro_x, $gyro_y, $gyro_z, $time){
		return abs($acc_x*sin($gyro_x*$time)+$acc_y*sin($gyro_y*$time)-$acc_z*cos($gyro_y*$time)*cos($gyro_z*$time));
	}
	function cal_SMA_by_array($xAccArr, $yAccArr, $zAccArr){	
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
	function cal_velocity_by_array($xAccArr, $yAccArr, $zAccArr, $dataPerPeriod, $period){
		$sum = 0;
		
		for ($i=0; $i < $dataPerPeriod; $i++) { 
			# code...
			$sum += (sqrt(pow($xAccArr[$i],2)+pow($yAccArr[$i],2)+pow($zAccArr[$i],2))-9.81)*$period/$dataPerPeriod;
		}
		return $sum;
	}

	function POSTURE(){

	}
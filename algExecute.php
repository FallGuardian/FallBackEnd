

<?php
	include "algFunction.php";

	// ensure the process would not stop by system
	set_time_limit(600);

	echo 'Alogrithm start to execute. <br>';

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
	
	// Collect the POST value
	// var_dump($_POST);
	$algType = $_POST["algType"];

	
	// Calculate the Value By User
	switch ($algType) {

		case '4':
			echo 'SMA, TA is executing .<br>';
			// $sql = "SELECT `id`, ASIN(`acc_y`/SQRT(POW(`acc_x`, 2)+POW(`acc_y`,2)+POW(`acc_z`,2))) FROM `lab_primitive`";
			// $alllab_primitive = $dbh->query($sql)->fetchAll();
			
			// foreach ($alllab_primitive as $key => $value) {
				
			// 	$sql2 = "UPDATE `lab_primitive` SET `TA`=".$value[1].
			// 			", WHERE `id`=".$value['id'];
			// 	$dbh->query($sql);
				
			// }
			// echo 'All TA is done';
			$sql="SELECT * FROM `id_base`";
			$allBaseId = $dbh->query($sql)->fetchAll();
			foreach ($allBaseId as $key => $value) {
				# code...
				$start = $value['id']*400;
				$end = $value['id']*400+400;
				
				// $sql2 = "SELECT AVG( x.`acc_x` ) +AVG( x.`acc_y` ) +AVG( x.`acc_z` ) FROM (
				// 		SELECT t.`acc_x` , t.`acc_y` , t.`acc_z` 
				// 		FROM  `lab_primitive` AS t WHERE
				// 		`id`>".$start." AND `id`<=".$end.") x";
				// $data = $dbh->query($sql2)->fetch();
				// echo 'range:'.$start.'~'.$end.'<br>';
				// echo 'value:'.$data[0];
				
				// $sql3 = "UPDATE `tmp` SET `SMA`=".$data[0]." WHERE( `tmp`.`id`>".$start." AND `tmp`.`id`<=".$end.")";
				// $dbh->query($sql3);
				// echo 'Update Done!'.'<br>-----------<br>';

				$sql2 = "SELECT (AVG( x.`acc_x` ) +AVG( x.`acc_y` ) +AVG( x.`acc_z` )) FROM (
						SELECT t.`acc_x` , t.`acc_y` , t.`acc_z` 
						FROM  `lab_primitive` AS t WHERE
						`id`>".$start." AND `id`<=".$end.") x";
				
				$sql3 = "UPDATE `lab_primitive` SET `SMA`=(".$sql2.") WHERE( `lab_primitive`.`id`>".$start." AND `lab_primitive`.`id`<=".$end.")";
				$dbh->query($sql3);
				echo 'range:'.$start.'~'.$end.'<br>';
				echo 'Update Done!'.'<br>-----------<br>';
				
			}
			break;
		case '3':

			$one_empty=false;
			foreach ($_POST as $key => $value) {
				# code...
				if($value=="")
				{
					$one_empty=true;
					break;
				}
			}
			if(!$one_empty){

				$SMAMin = round(floatval($_POST['SMAMin']),1);
				$SMAMax = round(floatval($_POST['SMAMax']),1);
				$SVMMin = round(floatval($_POST['SVMMin']),1);
				$SVMMax = round(floatval($_POST['SVMMax']),1);
				$TAMin = abs(intval($_POST['TAMin']));
				$TAMax = abs(intval($_POST['TAMax']));
				
				echo 'Alogrithm3 is executing .<br>';
					
					# Get Base_id
					$sql="SELECT * FROM `id_base`";
					$allBaseId = $dbh->query($sql)->fetchAll();

					# Get fail_id (true fall, system detected)
					$sql2="SELECT `id` FROM `fall_id`";
					$t_f_data_raw = $dbh->query($sql2)->fetchAll(PDO::FETCH_NAMED);
					$t_f_data = array();
					foreach ($t_f_data_raw as $key => $value) {
						# code...
						array_push($t_f_data, $value['id']);
					}

					# Get stand_id (mis-judge to fall, not fall, but system detect)
					$sql3="SELECT `id` FROM `stand_id`";
					$m_j_f_data_raw = $dbh->query($sql3)->fetchAll(PDO::FETCH_ASSOC);
					$m_j_f_data = array();
					foreach ($m_j_f_data_raw as $key => $value) {
						# code...
						array_push($m_j_f_data, $value['id']);
					}

					# Get adjust_id (true fail , system not detected)
					$sql4="SELECT `id` FROM `adjust_id`";
					$t_f_s_nd_data_raw = $dbh->query($sql4)->fetchAll(PDO::FETCH_ASSOC);
					$t_f_s_nd_data = array();
					foreach ($t_f_s_nd_data_raw as $key => $value) {
						# code...
						array_push($t_f_s_nd_data, $value['id']);
					
					}
					$t_f_cnt = 0;				
					$m_j_f_cnt = 0;
					$t_f_s_nd_cnt = 0;

					
					# SMA loop
					for ($i=$TAMin; $i <= $TAMax ; $i++) { 
						# SVM loop
						for ($j=$SVMMin*10; $j <= $SVMMax*10 ; $j++) { 
							# TA loop
							for ($k=$SMAMin*10; $k <= $SMAMax*10; $k++) { 

								// ob_start();

								foreach ($allBaseId as $key => $value) {
									# code...
									
									if(findOverThesholdInlab_primitive($dbh, $value['id'], ($i/10), ($j/10), ($k/10) )>0){
										// echo 'in lab_primitive<br>';
										// echo 'value:'.$value['id'].'<br>';

										if(array_search($value['id'], $t_f_data)){
											$t_f_cnt++;
										}
										if(array_search($value['id'], $m_j_f_data)){
											$m_j_f_cnt++;
										}
										if(array_search($value['id'], $t_f_s_nd_data)){
											$t_f_s_nd_cnt++;
										}
										
									}
								}
								echo 'TA: '.($i/10).' ,SVM: '.($j/10).' ,SMA: '.($k/10).'<br>';
								echo 'Sensitivity: '.($t_f_cnt+$m_j_f_cnt)/($t_f_cnt+$m_j_f_cnt+$t_f_s_nd_cnt).'<br>';
								echo 'Error Rate: '.($t_f_s_nd_cnt+$m_j_f_cnt)/($t_f_cnt+$m_j_f_cnt+$t_f_s_nd_cnt).'<br>';
								echo '-------------------------<br>';
								// ob_flush();
    				// 			flush();
								
								$t_f_cnt = 0;				
								$m_j_f_cnt = 0;
								$t_f_s_nd_cnt = 0;

								// ob_end_clean();
							}

						}
					
					}
					
					
			}else{
				echo 'at least one of input is empty';
			}
				
			break;
		case '2':
			echo 'Alogrithm2 is executing .<br>';
			break;
		case '1':
			echo 'Alogrithm1 is executing .<br>';
			break;
		default:
			
			break;
	}

	$dbh = null;
?>	
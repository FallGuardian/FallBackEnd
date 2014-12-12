<?php
	
	include "algFunction.php";
	include "database.php";
	// ensure the process would not stop by system
	set_time_limit(6000);

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
	    echo 'Connect Successfully!<br/>';

	}catch(PDOException $e) {
	    echo 'Error!: ' . $e->getMessage() . '<br />';
	}

	$one_empty=false;
	
	// foreach ($_POST as $key => $value) {
	// 	if($value=="")
	// 		$one_empty=true;

	// }
	
	if(!$one_empty){

		// $SMAMin = round(floatval($_POST['SMAMin']),1);
		// $SMAMax = round(floatval($_POST['SMAMax']),1);
		$SVMMin = round(floatval($_POST['SVMMin']),2);
		$SVMMax = round(floatval($_POST['SVMMax']),2);
		$TAMin = abs(round(floatval($_POST['TAMin']),1));
		$TAMax = abs(round(floatval($_POST['TAMax']),1));

		if($_POST['keyInIds']!=""){
			$keyInIds = explode(',', trim($_POST['keyInIds']));
		}
		// var_dump($keyInIds);
		
		echo 'Alogrithm3 is executing. <br>';
			
			# Get All Base_id
			if(!isset($keyInIds)){
				echo 'use all data <br>';
				$targetBaseId = getBaseIds($dbh);
				
			}else{
			# Get the keyin id Base_id	
				echo 'use keyin data <br>';
				$keyInIdDatas = array();
				foreach ($keyInIds as $key => $value) {

					$data = getSingleData($dbh, $value);
					$keyInIdDatas[$value]=(sizeof($data)!=0)?$data:'0';
				}
				$targetBaseId = $keyInIdDatas;
			}
			
			$total = count($targetBaseId);
			echo 'total:'.$total.'<br>';

			# Get fail_id (true fall, system detected)
			$fall_id = getFallIds($dbh);

			# Get stand_id (mis-judge to fall, not fall, but system detect)
			$stand_id = getStandIds($dbh);

			# Get adjust_id (true fail , system not detected)
			$adjust_id = getAdjustId($dbh);

			$t_f_cnt = 0;				
			$m_j_f_cnt = 0;
			$thesholdCnt = 0;
			$t_f_s_nd_cnt = 0;

			$rstErr2 = array();
			# TA loop
			for ($i=$TAMin; $i <= $TAMax; $i++) { 
				# SVM loop
				for ($j=$SVMMin*10; $j <= $SVMMax*10; $j++) { 
						
					$errorKeysNeg = "";
					$errorKeysPos = "";
					$thesholdCnt = 0;
					$maxCount = 0;
					# Find Max Count in fall Ids, as new theshold
					foreach ($targetBaseId as $key => $value){
						if(array_search($key, $fall_id))
							$maxCount = max($maxCount, findOverThesholdInPrimitive($dbh, $value, $i, ($j/10)));
					}

					# Run Alla Target Ids
					foreach ($targetBaseId as $key => $value) {
							
							#our prediction of fall
							$cnt = findOverThesholdInPrimitive($dbh, $value, $i, ($j/10) );
							if($cnt>0){
								# real result of fall
								if(array_search($key, $stand_id)){
									$m_j_f_cnt++;
									$errorKeysNeg.=$key.',';

									if($cnt > $maxCount)
										$thesholdCnt++;			
								}
							}	
							# our prediction of not fall
							else{

								# real result of fall
								if(array_search($key, $fall_id)){
									$t_f_cnt++;
									$errorKeysPos.=$key.',';
								}
							}
								
					}
					$key = 'TA: '.($i).' ,SVM: '.($j/10);
					
					// $rstSen[$key] = ($t_f_cnt+$m_j_f_cnt)/$total;

					$rstErr[$key] = ($t_f_cnt+$m_j_f_cnt)/$total;
					$errorKeys = $m_j_f_cnt.': '.$errorKeysNeg.' | '.$t_f_cnt.': '.$errorKeysPos;
					
					$tmp = array('error'=>($t_f_cnt+$m_j_f_cnt)/$total,
									'errorCntFilter'=>($t_f_cnt+$m_j_f_cnt-$thesholdCnt)/$total,
									'TA'=>$i,
									'SVM'=>($j/10),
									'errorIDs'=>$errorKeys, 
									'maxCount'=>$maxCount,
									'thesholdCnt'=>$thesholdCnt);

					array_push($rstErr2, $tmp); 
					$t_f_cnt = 0;				
					$m_j_f_cnt = 0;
					$t_f_s_nd_cnt = 0;
				}
			}

			// $minErr = min($rstErr);
			// arsort($rstErr);
			
			// foreach ($rstErr as $k => $v) {
			// 	echo ' [ '.$k.' ] '.'Min Error Rate: '.$v.'{'.$errorKeys[$k].'}<br>';
			// }
			
			
	}else{
		echo 'at least one of input is empty';
	}
	$dbh = null;
?>
<html>
<head>
	
	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.3/css/jquery.dataTables.css">
  
	<!-- jQuery -->
	<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  
	<!-- DataTables -->
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.js"></script>
	<script type="text/javascript">
	$(document).ready( function () {
	    $('#table_id').DataTable();
	} );
	</script>

</head>
<body>
	
<div  style="float:left;width:600px;">
	<table id="table_id" class="display">
	    <thead>
	        <tr>
	            <th>TA</th>
	            <th>SVM</th>
	            <th>error</th>
	            <th>error IDs</th>
	            <th>Cnts</th>
	            <th>error (with MaxCnts)</th>
	            <th>thesholdCnt</th>
	        </tr>
	    </thead>
	    <tbody>
	    	<?php foreach ($rstErr2 as $key => $data) {?>
	        <tr>
	            <td><?php echo $data['TA']?></td>
	            <td><?php echo $data['SVM']?></td>
	            <td><?php echo $data['error']?></td>
	            <td><?php echo $data['errorIDs']?></td>
	            <td><?php echo $data['maxCount']?></td>
	             <td><?php echo $data['errorCntFilter']?></td>
	             <td><?php echo $data['thesholdCnt']?></td>
	        </tr>
	    	<?php };?>
	    </tbody>
	</table>
</div>
</body>
</html>
	

				

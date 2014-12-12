
<html>
<head>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.21/angular.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
	<title>Fall-down Dectionion System Beta Version</title>
	<script type="text/javascript">
		$(function() {
			$( "#tabs" ).tabs({ active: 2 });
		});
	</script>
</head>
<body>
	<div id="top">
		
	</div>
	
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Algorithim 1</a></li>
			<li><a href=l"#tabs-2">Algorithim 2</a></li>
			<li><a href="#tabs-3">Algorithim 3</a></li>
			<li><a href="#tabs-4"  >delete </a></li>
			<!-- <li><a href="#tabs-4">Calculate TA, SMA</a></li> -->
		</ul>
		<div id="tabs-1">



		</div>
		<div id="tabs-2">
			<p></p>
		</div>
		<div id="tabs-3">
		
		<?php 
			## Your have to fix it, this code should not be here
			include "database.php";
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
			$data = getParaAVG($dbh);

			// var_dump($data);
			$dbh = null;
		?>
			<form action="Algorithm3.php" method="POST" id="algorithm3Form">
				<p></p>
				<p></p>
				<!-- <p>SMA Range min:<input type="text" name="SMAMin" class="shotNumText number" >
					max:<input type="text" name="SMAMax" class="shotNumText number" >
					(average:<?php echo $data[0]["AVG(`SMA`)"] ?>)</p> -->

				<p>SVM Range: min<input type="text" name="SVMMin" class="shotNumText number" > 
					max:<input type="text" name="SVMMax" class="shotNumText number" >
					(average:<?php echo $data[0]["AVG(`SVM`)"] ?>)</p>

				<p>TA Range(0~90): 
					min:<input type="text" name="TAMin" class="shotNumText number" > 
					max:<input type="text" name="TAMax" class="shotNumText number" >
					(average:<?php echo $data[0]["AVG(`TA`)"]?>)</p>
				
				<select id="selectMode">
					<option value="all">use total data</option>
					<option value="key" selected="selected">use keyin id data</option>
				</select>
				<p>
					<div id="keyInIds">
						key-in the id_base, only including the selected id's data (seperate by ",")<br>
						<textarea name="keyInIds" cols="65" rows="5" ></textarea>
					</div>
				</p>
				<p><input type="submit" value="calculate"></p>
			</form>
		</div>
		<!-- <div id="tabs-4">
			<form action="algExecute.php" method="POST">
				<input type="submit" value="calculate">
				<input type="hidden" name="algType" value=4>
			</form>
		</div> -->
		<div id="tabs-4">
			<form action="delete.php" method="POST">
				<p>
					<textarea name="deleteIds" cols="65" rows="5" ></textarea>	
				</p>
				<p>
					<input type="submit" value="delete" >
				</p>
			</form>
			<form action="extract.php" method="POST">
				<p>
					<input type="submit" value="extract">
				</p>
			</form>
		</div>

	</div>
	
			
<script type="text/javascript">
	$( document ).ready(function() {
        console.log( "document loaded" );

    	$("input[name=SMAMin]").focusout(function(){
			$("input[name=SMAMax]").val($(this).val());
		});

    	$("input[name=SVMMin]").focusout(function(){
			$("input[name=SVMMax]").val($(this).val());
		});

		$("input[name=TAMin]").focusout(function(){
			$("input[name=TAMax]").val($(this).val());
		});

		// handle to select event
		
		$("#selectMode").change(function(){
			console.log($(this).val());

			// clean up the setting by default
			$("#keyInIds").hide();
			$("#keyInIds textarea").val("").prop("disabled","disabled");

			switch($(this).val())
			{
				case "all":
					$("#keyInIds").hide(200);
					$("#keyInIds textarea").prop("disabled","disabled");
					break;
				case "key":
					$("#keyInIds").show(100);
					$("#keyInIds textarea").prop("disabled","");
					break;

				default:
					break;
			}
		});

		$('.number').keypress(function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		  	  event.preventDefault();
		  	}
		});
    });
	
</script>

</body>


</html>
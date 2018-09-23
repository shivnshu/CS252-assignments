
<html>
<head>
<title>Test Page</title>
</head>
<body>
<center><h3>Crime Database</h3></center>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<center>
<input type="radio" name="radio" value="Radio1">Get Crimes Per Capita
<br><br>
<input type="radio" name="radio" value="Radio2">Poice Stations Effeciency
<br><br>
<input type="radio" name="radio" value="Radio3">Crime Laws
<br><br>
<input type="submit">
<br>
<br>
<br>
</center>
</form>



<?php
$connection = new MongoClient();
$collection = $connection->cs252->cases;
$set=$_POST['radio'];


/*$query = array(array('$group'=> array("_id"=>$DISTRICT,"count"=>array('$sum'=>1))),);
$cursor = $collection->aggregate($query);

var_dump($cursor)*/
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	if($set=="Radio1"){
		$ages = $collection->aggregateCursor( [
	        [ '$group' => [ '_id' => '$DISTRICT', 'points' => [ '$sum' => 1 ] ] ],
	        [ '$sort' => [ 'points' => -1 ] ],
		] );
		echo "<center><table><tr><th>DISTRICT</th><th>CRIMES</th></tr>";
		foreach ($ages as $person) {
		    echo "<tr><td>{$person['_id']}</td><td> {$person['points']}</td></tr>";
		}
		echo "</table></center>";
	}
	else if($set=="Radio2"){
		$ages = $collection->aggregateCursor( [
			[ '$project' => [ 'PS'=>1,'isok'=>[ '$cond'=>[['$eq'=>['$Status','CS']],1,0]],'notok'=>['$cond'=>[['$or'=>[['$eq'=>['$Status','FR']],['$eq'=>['$Status','Pending']],['$eq'=>['$Status','CS']]]],1,0]]]],
	        [ '$group' => [ '_id' => '$PS', 'completed' => [ '$sum' => '$isok' ],'notcompleted'=>['$sum'=>'$notok'] ] ],
	        [ '$project'=>['_id'=>1,'eff'=>['$divide'=>['$completed','$notcompleted']] ]],
	        ['$sort'=>['eff'=>1]],
		] );
		echo "<center><table><tr><th>Police Station</th><th>Effeciency</th></tr>";
		foreach ($ages as $person) {
			$c1=$person['eff']*100;
		    echo "<tr><td>{$person['_id']}</td><td>".number_format($c1,2)."%</td></tr>";
		}
		echo "</table></center>";

	}
	else if($set=="Radio3"){
		$ages=$collection->aggregateCursor([['$unwind'=>'$Act_Section'],['$match'=>['Act_Section'=>['$regex'=>('^(?!(unknown)$).*$')]]],['$group'=>['_id'=>'$_id','Act_Section'=>['$addToSet'=>'$Act_Section']]],['$unwind'=>'$Act_Section'],['$group'=>['_id'=>'$Act_Section','count'=>['$sum'=>1]]],['$sort'=>['count'=>-1]],['$group'=>['_id'=>'$_id','count'=>['$last'=>'$count']]],['$limit'=>1]]);
		foreach ($ages as $person){
		echo "<center><b>Least Committed crime</b> is crimeNo ".$person['_id']." with count of ".$person['count'];
		}
		$ages=$collection->aggregateCursor([['$unwind'=>'$Act_Section'],['$match'=>['Act_Section'=>['$regex'=>('^(?!(unknown)$).*$')]]],['$group'=>['_id'=>'$_id','Act_Section'=>['$addToSet'=>'$Act_Section']]],['$unwind'=>'$Act_Section'],['$group'=>['_id'=>'$Act_Section','count'=>['$sum'=>1]]],['$sort'=>['count'=>-1]],['$limit'=>1]]);
		foreach ($ages as $person){
		echo "<center><b>Most Committed crime</b> is crimeNo ".$person['_id']." with count of ".$person['count'];
		}

	}

	

}


?>
</body>
</html>
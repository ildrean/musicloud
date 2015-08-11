<?php
	//$user = $_COOKIE['user'];
	$user = "tester";
	
	require_once('db_login.php'); //File on the server containing login and database information
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	
	$stmt = $db->prepare("SELECT userID FROM login WHERE user = ?");	//possibly change incoming data (cookie, session, etc) so it is userID instead of user
	$stmt->execute(array($user));										//so this block would be unused
	$userID = $stmt->fetch();
	
	$stmt = $db->prepare("SELECT songID FROM files WHERE user = ?");	//look into foreign keys
	$stmt->execute(array($userID[0]));									//try doing pdo FETCH_ASSOC instead of default FETCH_BOTH - associative and numerical arrays are returned, ineffiecient
	$IDArray = $stmt->fetchAll(PDO::FETCH_NUM);
								
	$table = "
			<table id=songtable> 
				<thead> 
					<tr class=head> 
						<th>Name</th> 
						<th>Time</th> 
						<th>Artist</th> 
						<th>Album</th> 
						<th>Genre</th>
					</tr> 
				</thead> 
				<tbody> ";
	$j=0;
	for($i=0;$i<count($IDArray);$i++){
		
		$stmt = $db->prepare("SELECT realname, playtime, artist, album, genre FROM music WHERE songID = ?");	//maybe rework this, with fetchall/UNION does not need to be iterated
		$stmt->execute(array($IDArray[$i][0]));																	//possibly memory inefficient,i dont know
		$songDataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);																		//try IN query? - WHERE item_id IN ('001', '012', '103', '500')

			
		//truncates, adds ... to strings over 30 characters - make this clientside
		if(strlen($songDataArray[0]['realname']) > 30){
			$songDataArray[0]['realname'] = substr($songDataArray[0]['realname'],0,30)."...";
		}else if(strlen($songDataArray[0]['playtime']) > 30){
			$songDataArray[0]['playtime'] = substr($songDataArray[0]['playtime'],0,30)."...";
		}else if(strlen($songDataArray[0]['artist']) > 30){
			$songDataArray[0]['artist'] = substr($songDataArray[0]['artist'],0,30)."...";
		}else if(strlen($songDataArray[0]['album']) > 30){
			$songDataArray[0]['album'] = substr($songDataArray[0]['album'],0,30)."...";
		}else if(strlen($songDataArray[0]['genre']) > 30){
			$songDataArray[0]['genre'] = substr($songDataArray[0]['genre'],0,30)."...";
		}
								
								
		$table = $table."
				<tr id=".$IDArray[$i][0]." class=row".(($i%2)+1)."> 
					<td><div class='playarrow'></div>".$songDataArray[0]['realname']."</td> 
					<td>".$songDataArray[0]['playtime']."</td> 
					<td>".$songDataArray[0]['artist']."</td> 
					<td>".$songDataArray[0]['album']."</td> 
					<td>".$songDataArray[0]['genre']."</td> 
				</tr>";
		$j++;
	}	
	while($j < 25){	//fill remaining table space with empty rows - possibly make this clientside
		$table = $table."
				<tr class=row".(($j%2)+1)."> 
					<td class=empty></td> 
					<td class=empty></td> 
					<td class=empty></td> 
					<td class=empty></td> 
					<td class=empty></td> 
				</tr>";
		$j++;
	}
	$table = $table."
				</tbody> 
			</table>";
	echo $table;
	
?>
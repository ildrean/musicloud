<?php
	//$user = $_COOKIE['user'];
	$user = "tester";
	
	require_once('db_login.php'); //File on the server containing login and database information
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	
	$stmt = $db->prepare("SELECT userID FROM login WHERE user = ?");	//change incoming data (cookie, session, etc) so it is userID instead of user
	$stmt->execute(array($user));										//so this block would be unused
	$userID = $stmt->fetch();
	
	$stmt = $db->prepare("SELECT pID, name, songIDs FROM playlists WHERE userID = ?");	
	$stmt->execute(array($userID[0]));										
	$getPlaylists = $stmt->fetchAll();
									
	$table = "
			<table id=playlisttable> 
				<thead> 
					<tr class=head> 
						<th>Playlists</th> 
					</tr> 
				</thead> 
				<tbody>
					<tr id='-1' class='row1'> 
						<td>+ Add New Playlist</td> 
					</tr>
					<tr id='0' class='row2'> 
						<td>All Songs</td> 
					</tr>";
				
	$j=0;
	for($i=0;$i<count($getPlaylists);$i++){
		$table = $table."
				<tr id=".$getPlaylists[$i]['pID']." class=row".(($i%2)+1)."> 
					<td>".$getPlaylists[$i]['name']."</td> 
				</tr>";
		$j++;
	}
	while($j < 24){ //fill remaining table space with empty rows
		$table = $table."
				<tr class=row".(($j%2)+1)."> 
					<td></td> 
				</tr>";
		$j++;
	}

		
	$table = $table."
				</tbody> 
			</table>";
	echo $table;
?>
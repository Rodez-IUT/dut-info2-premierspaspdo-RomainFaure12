<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Les excuses du lundi matin</title>
	  
		<link href="css/monStyle.css" rel="stylesheet">
		
		<!-- Bootstrap CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
	</head>
	<body>
		<?php 
			$host='localhost';
			$db='my_activities';
			$user='root';
			$pass='root';
			$charset='utf8mb4';
			$dsn="mysql:host=$host;dbname=$db;charset=$charset";
			$options=[
				PDO::ATTR_ERRMODE				=>PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=>PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES		=>false,];
			try{
				$pdo=new PDO($dsn,$user,$pass,$options);
			}catch(PDOException$e){
				throw new PDOException($e->getMessage(),(int)$e->getCode());
			}
		?>

		<form action="all_users.php" method="post">
			<input id="lettre" name="lettre" type="texte"></input>
			<select id="status_id" name="status_id">
			<option value="2">Active account</option>
			<option value="1">Waiting for account validation</option>
			<option value="3">Waiting for account deletion</option></select>
			<input type="submit" name="validation" value="rechercher"/>
		</form>

		<?php

			if(isset($_POST['status_id'])){
				$status_id = $_POST['status_id'];
				$lettre = $_POST['lettre'];

				$stmt = $pdo->prepare("SELECT users.id,username,email,name 
								 FROM users 
								 JOIN status 
								 ON users.status_id = status.id 
								 AND status_id = :status_id
								 AND username LIKE :username
								 ORDER BY username");

				$stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
				$stmt->bindValue(':username', $lettre.'%', PDO::PARAM_STR);
				$stmt->execute();

			}else{
				$stmt = $pdo->query("SELECT users.id,username,email,name 
								 FROM users 
								 JOIN status 
								 ON users.status_id = status.id 
								 ORDER BY username");
			}
			echo "<table border=\"1px\">";
			echo "<tr><td>id</td><td>username</td><td>email</td><td>name</td></tr>";
			while($row = $stmt->fetch()){
				echo "<tr>";
				echo "<td>".$row['id']."</td>";
				echo "<td>".$row['username']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['name']."</td>";
				if($row['name']!="Waiting for account deletion"){
					echo "<td><a href=\"all_users.php?status=3&amp;id=".$row['id']."&amp;action=AskDeletion\">Ask deletion</a></td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		?>
	</body>
</html>
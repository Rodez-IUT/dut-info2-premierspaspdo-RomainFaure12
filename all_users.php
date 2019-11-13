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

			$lettre = '%';
			$status_id = '%';

			if(isset($_POST['lettre'])){
				$lettre = $_POST['lettre'];
			}
			if(isset($_POST['status_id'])){
				$status_id = $_POST['status_id'];
			}

			echo "<form method=\"post\">";
			echo "<input id=\"lettre\" type=\"texte\"></input>";
			echo "<select id=\"status_id\">";
			echo "<option value=\"1\">Active account</option>";
			echo "<option value=\"2\">Waiting for account validation</option></select>";
			echo "<input type=\"submit\" name=\"validation\" value=\"rechercher\"/>";
			echo "</form>";

			$stmt = $pdo->query("SELECT users.id,username,email,name 
								 FROM users 
								 JOIN status 
								 ON users.status_id = status.id 
								 AND status_id LIKE '$status_id'
								 AND username LIKE '$lettre%' 
								 ORDER BY username");
			echo "<table border=\"1px\">";
			echo "<tr><td>id</td><td>username</td><td>email</td><td>name</td></tr>";
			while($row = $stmt->fetch()){
				echo "<tr>";
				echo "<td>".$row['id']."</td>";
				echo "<td>".$row['username']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['name']."</td>";
				echo "</tr>";
			}
			echo "</table>";
		?>
	</body>
</html>
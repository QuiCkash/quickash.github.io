<!DOCTYPE html>
<html>
	<head>
		<title>SIgninC Insertion</title>
	</head>
	<body>
		<h3>SIgninC Insertion</h3>
		<?php
			if(!isset($_POST["Prenom_Caissier"] || (!isset($_POST["Nom_Caissier"] || !isset($_POST["pwd_Caissier"]) || !isset($_POST["username_Caissier"])){
				echo "You have not entered the right sign in details.";
			}

			$prenomC = $_POST["Prenom_Caissier"];
			$nomC = $_POST["Nom_Caissier"];
			$pwdC = $_POST["pwd_Caissier"];
			$usernameC = $_POST["username_Caissier"];

			@$db = new mysqli("localhost", "xoosuser", "xoos1234", "XOOS_DB");
			if(mysqli_connect_errno()){
				echo "Error: Could not connect to database";
			}

			$query = "INSERT INTO Caissier VALUES (?, ?, ?, ?)";
			$stmt = $db->prepare($query);
			$stmt->bind_param($prenomC, $nomC, $pwdC, $usernameC);
			$stmt->execute();

			if($stmt->affected_rows > 0){
				echo "Your inscription succeed";
			}
			else{
				echo "An error occured. Please try again later";
			}
		?>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>SIgninC Insertion</title>
	</head>
	<body>
		<h3>LoginC Insertion</h3>
		<?php
			if(!isset(!isset($_POST["username_Caissier"]) || !isset($_POST["pwd_Caissier"])){
				echo "You have not entered the right sign in details.";
			}


			$usernameC = $_POST["username_Caissier"];
			$pwdC = $_POST["pwd_Caissier"];

			@$db = new mysqli("localhost", "xoosuser", "xoos1234", "XOOS_DB");
			if(mysqli_connect_errno()){
				echo "Error: Could not connect to database";
			}

			$query = "INSERT INTO Caissier VALUES (?, ?)";
			$stmt = $db->prepare($query);
			$stmt->bind_param($usernameC, $pwdC);
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
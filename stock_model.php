<?php 

	Class Model{
		private $server = 'localhost';
		private $username = 'bookuser';
		private $password = 'bookpwd';
		private $db = 'XOOS_DB';
		private $conn;

		public function __construct(){
			try {
				$this->conn = new mysqli($this->server, $this->username, $this->password, $this->db);
			} catch (Exception $e) {
				echo "Connection error " . $e->getMessage();
			}
		}

		public function insert($nom_prod, $prix_htva, $tva, $qte){
			$query = "INSERT INTO Produit(`Nom_Prod`, `Prix_HTVA_Prod`, `TVA`, `Quantite_Prod`) VALUES ('$nom_prod', '$prix_htva', '$tva', '$qte')";
			if ($sql = $this->conn->query($query)) {
				return true;
			}else{
				return;
			}
		}

		public function fetch(){
			$data = [];

			$query = "SELECT * FROM Produit";

			if ($sql = $this->conn->query($query)) {
				while ($rows = mysqli_fetch_assoc($sql)) {
					$data[] = $rows;
				}
			}

			return $data;
		}

		public function delete($id){
			$query = "DELETE FROM Produit WHERE `Id_Prod` = '$id'";
			if ($sql = $this->conn->query($query)) {
				return true;
			}else{
				return;
			}
		}

		public function edit($id){
			$data = [];

			$query = "SELECT * FROM Produit WHERE `Id_Prod` = '$id'";
			if ($sql = $this->conn->query($query)) {
				$row = mysqli_fetch_row($sql);
				$data = $row;
			}

			return $data;
		}

		public function update($id, $nom_prod, $prix_htva, $tva, $qte){
			$query = "UPDATE Produit SET `Nom_Produit` = '$nom_prod', `Prix_HTVA_Prod` = '$prix_htva', `TVA` = '$tva', `Quantite_Prod` = '$qte' WHERE `Id_Prod` = '$id'";
			if ($sql = $this->conn->query($query)) {
				return true;
			}else{
				return;
			}
		}
	}

?>
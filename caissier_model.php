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


		public function fetch(){
			$data = [];

			$query = "SELECT * FROM Caissier";

			if ($sql = $this->conn->query($query)) {
				while ($rows = mysqli_fetch_assoc($sql)) {
					$data[] = $rows;
				}
			}

			return $data;
		}

		public function delete($id){
			$query = "DELETE FROM Caissier WHERE `Id_Caissier` = '$id'";
			if ($sql = $this->conn->query($query)) {
				return true;
			}else{
				return;
			}
		}

		
	}

?>
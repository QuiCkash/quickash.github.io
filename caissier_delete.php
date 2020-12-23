<?php 

	if (isset($_POST['id'])) {
		$id = $_POST['id'];

		include 'caissier_model.php';

		$model = new Model();

		if ($model->delete($id)) {
			$data = array('res' => 'success');
		}else{
			$data = array('res' => 'error');
		}

		echo json_encode($data);
	}
	
 ?>
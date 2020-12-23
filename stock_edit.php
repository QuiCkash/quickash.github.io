<?php 

	if (isset($_POST['id'])) {
		$id = $_POST['id'];

		include 'stock_model.php';

		$model = new Model();

		if ($row = $model->edit($id)) {
			$data = array('res' => 'success', 'row' => $row);
		}else{
			$data = array('res' => 'error');
		}

		echo json_encode($data);
	}

 ?>
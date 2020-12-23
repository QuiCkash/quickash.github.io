<?php 

	include 'stock_model.php';

	$model = new Model();

	$rows = $model->fetch();

	$data = array('rows' => $rows);

	echo json_encode($data);

 ?>
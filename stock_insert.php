<?php 

	if (isset($_POST['nom_prod']) && isset($_POST['prix_htva']) && isset($_POST['tva']) && isset($_POST['qte'])) {
		$nom_prod = $_POST['nom_prod'];
        $prix_htva = $_POST['prix_htva'];
        $tva = $_POST['tva'];
        $qte = $_POST['qte'];

		include 'stock_model.php';

		$model = new Model();

		if ($model->insert($nom_prod, $prix_htva, $tva, $qte)) {
			$data = array('res' => 'success');
		}else{
			$data = array('res' => 'error');
		}

		echo json_encode($data);
	}

 ?>
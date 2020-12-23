<?php
 $connect= new PDO("mysql:host=localhost;dbname=XOOS_DB","bookuser","bookpwd");

// Ici on verifie s'il existe une requete pour ajouter un produit
if(isset($_POST["add_to_list"]))
{
  // Si le cookie existe
 if(isset($_COOKIE["shopping_cart"]))
 {
  $cookie_data = stripslashes($_COOKIE['shopping_cart']);
  // On recupere le contenu
  $cart_data = json_decode($cookie_data, true);
 }
 else
 {
   // Sinon on initialise le cookie 
  $cart_data = array();
 }
 // Ici on recupere tous les id_produits qui sont dans le panier

 $item_id_list = array_column($cart_data, 'Id_Prod');

  // Maintenant si l'id_produit existe dans le panier, on va juste incrementer la quantité

 if(in_array($_POST["Id_Prod"], $item_id_list))
 {
  foreach($cart_data as $keys => $values)
  {
   if($cart_data[$keys]["Id_Prod"] == $_POST["Id_Prod"])
   {
    $cart_data[$keys]["Quantite_Prod"] = $cart_data[$keys]["Quantite_Prod"] + $_POST["Quantite_Prod"];
   }
  }
 }
  // Sinon on va juster l'ajouter dans le panier
 else
 {
  $item_array = array(
   'Id_Prod'   => $_POST["Id_Prod"],
   'qte'   => $_POST["qte"],
  );
  $cart_data[] = $item_array;
 }

 
 $item_data = json_encode($cart_data);
 setcookie('shopping_cart', $item_data, time() + (86400 * 30));
 header("location:caisse.php?success=1");
}

// Ici on va juste afficher un message selon l'action effectuée

if(isset($_GET["action"]))
{
 if($_GET["action"] == "delete")
 {
   // Pour supprimer un produit du panier, on recupere le contenu du panier et on le retire 
  $cookie_data = stripslashes($_COOKIE['shopping_cart']);
  $cart_data = json_decode($cookie_data, true);
  foreach($cart_data as $keys => $values)
  {
   if($cart_data[$keys]['Id_Prod'] == $_GET["id"])
   {
    unset($cart_data[$keys]);
    $item_data = json_encode($cart_data);
    setcookie("shopping_cart", $item_data, time() + (86400 * 30));
    header("location:caisse.php?remove=1");
   }
  }
 }
 if($_GET["action"] == "clear")
 {
   // Pour supprimer tous les produits, on va juste réinitialiser le panier
  setcookie("shopping_cart", "", time() - 3600);
  header("location:caisse.php?clearall=1");
 }



 if($_GET["action"] == "enregistrer")

 $date = date('YmdHis');
 {
   // Ici on enregistre d'abord les details de la vente(facture et date) au niveau de la table 'vente'
  
  $query = "INSERT INTO vente (numero_facture,etat) VALUES ('$date','en_cours')"; 
  $statement = $connect->prepare($query);
  $statement->execute();

  // On recupere l'id de la vente

  $id_vente = $connect->lastInsertId();

  // Ensuite pour les produits on va les stocker au niveau de la table 'vente_produit'

  if(isset($_COOKIE["shopping_cart"]))
  {
    // Ici on recupere les produits au niveau du panier si le cookie shopping_cart existe
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);

    $cart_data = json_decode($cookie_data, true);
    // On va inserer un à un 
    foreach($cart_data as $keys => $values)
    {
       
      $singlequery = "INSERT INTO vente_produit (id_produit,id_vente,quantite) VALUES ('".$values['id_produit']."','".$id_vente."','".$values['quantite']."')";
      $statement1 = $connect->prepare($singlequery);
      $statement1->execute();

      // On decrememte le stock du produit 

      $query = "UPDATE  produit SET Quantite_Prod = Quantite_Prod-'".$values['qte']."' WHERE Id_Prod = '".$values['Id_Prod']."' ";    
      $statement2 = $connect->prepare($query);
      $statement2->execute();
    }
  }

  // Apres enregistrement de la vente, on réinitialise le cookie 

  $message = "Vente enregistrée avec succès !";
  setcookie("shopping_cart", "", time() - 3600);
  header("location:caisse.php");
 }


}



if(isset($_GET["success"]))
{
 $message = '
 <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    Vous avez ajouté un nouveau produit !
 </div>
 ';
}

if(isset($_GET["remove"]))
{
 $message = '
 <div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  Vous avez supprimé le produit !
 </div>
 ';
}
if(isset($_GET["clearall"]))
{
 $message = '
 <div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  Vous avez supprimé tous les produits !
 </div>
 ';
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="projet.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="bootstrap-4.5.3-dist\css\bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="stock.css">
</head>

    
<body>
  
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="d-flex ">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success " type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<div class="card text-center">
  <div class="card-header">
    Bienvenue!
  </div>
 <div class="shadow py-2 mb-4">
    <h5 class="card-title" class="text-dark">Nouvelle Vente</h5>
  
<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
 <i class="fa fa-plus"></i> Ajouter un nouveau produit</button>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Vente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nom du Produit:</label>
            <select type="text" class="form-control" id="recipient-name" name="Id_Prod" required="required">
               <?php

                        // Ici on recupere les produits qui ont un stock superieur à O 

                        $query = "SELECT Nom_prod,Prix_HTVA_Prod,TVA FROM produit WHERE Quantite_Prod>0 ";
                        $statement = $connect->prepare($query);
                        $statement->execute();
                        $result = $statement->fetchAll();

                        // Et le caissier peut choisir un produit et une quantité souhaitée 

                        foreach($result as $produit)
                        {
                        ?>
                        <option> <?php echo $produit['Nom_prod'] ?> <?php echo $produit['Prix_HTVA_Prod'] ?>F</option>
                        <?php 
                        }

                        // Apres submit, on stocke juste l'id du produit et la quantité au niveau du cookie

                        ?>
            </select>
          </div>
          
           
           <div class="form-group">
            <label for="message-text" class="col-form-label">Quantite:</label>
            <input type="number" class="form-control" id="message-text" name="qte" required="required">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" required="required" name="add_to_list">Ajouter</button>
      </div>
    </div>
  </div>
</div>
  </div>

 
</div>

<table class="table">

  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>


       <?php
   if(isset($_COOKIE["shopping_cart"]))
   {

    // Si le produit est ajouté au niveau du cookie on doit faire une requete pour recuperer les details

     $num=1;
    $total = 0;
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
    $cart_data = json_decode($cookie_data, true);
    foreach($cart_data as $keys => $values)
    {
   ?>
    <?php
      $query = "SELECT Nom_Prod FROM produit WHERE Id_Prod='".$values['Id_Prod']."'";
      $statement = $connect->prepare($query);
      $statement->execute();
      $result = $statement->fetchAll();
      foreach($result as $produit)
      {
      ?>

        <tr>
        <td>ok</td>
        <td><?php echo $produit["Nom_Prod"]; ?></td>
         <td><?php echo $produit["Prix_HTVA_Prod"]; ?></td>

        <td><?php echo $values["Id_Prod"]; ?></td>
        <td><?php echo number_format($values["qte"] * $produit["Prix_HTVA_Prod"]);?> F</td>
        <td><a href="caisse.php?action=delete&id=<?php echo $values["Id_Prod"]; ?>"><span class="text-danger"><i class="fa fa-trash"></i></span></a></td>
        </tr> 
      <?php 
      $total = $total + ($values["qte"] * $produit["Prix_HTVA_Prod"]);
      $num= $num+1;
      }
      ?>
    </tr>
   <?php 
     // pass
    }
   ?>
    <tr>
     <td colspan="3" align="right" class="mt-4">Total</td>
     <td align="right"><?php echo number_format($total, 2); ?> F</td>
     <td></td>
    </tr>
   <?php
   }
   // Si on ne trouve pas de produit au niveau du cookie on affiche un message 
   else
   {
    echo '
    <tr>
     <td colspan="5" align="center">Aucun produit ajouté</td>
    </tr>
    ';
   }
   ?>
     </tbody>
 
 
</table>

      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
  <button class="btn btn-warning" type="button" data-target="#confirmation">Enregistrer</button>
</div>
<div class="d-grid gap-2 d-md-flex justify-content-md-start">
  <a href="caisse.php?action=clear"class="btn btn-danger" >Annuler</a>
</div>
<div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enregistrement de la vente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <ul class="list-group">
              <?php

              // Ici on affiche un resume de la vente avant l'enregistrement 

                $total = 0;
                if(isset($_COOKIE["shopping_cart"]))
                {
                  $total = 0;
                  $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                  $cart_data = json_decode($cookie_data, true);
                  foreach($cart_data as $keys => $values)
                  {
                ?>
                  <?php
                    $query = "SELECT * FROM produit WHERE Id_Prod='".$values['Id_Prod']."'";
                    $statement = $connect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach($result as $produit)
                    {
                    ?>
                      <li class="list-group-item"><?php echo $produit["Nom_Prod"]; ?> <?php echo $produit["Prix_HTVA_Prod"]; ?> <small>x<?php echo $values["Quantite_Prod"]; ?></small></li>
                    <?php 
                    $total = $total + ($values["qte"] * $produit["Prix_HTVA_Prod"]);
                    }
                    ?>
                <?php 
                  // pass
                  }
                }
                ?>
                <div class="float-right">
                  Total: <?php echo $total ?>F
                </div>
              </ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                <a href="caisse.php?action=enregistrer" class="btn btn-success">Confirmer</a>
                </div>
            </div>
          </div>
        </div>

        </div>
      </div>
    </div>
 <div class="d-grid gap-2 d-md-flex justify-content-md-end fixed-bottom">
  <button class="btn btn-warning" type="button">Voir la liste des produits disponibles.</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
</body>
</html>
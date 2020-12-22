<!DOCTYPE HTML>
<html>
    <head>
        <title>Inserted admin</title>
    </head>
    <body>
        <h3 style="color: blue; text-decoration: underline;">Inscription Result</h3>
        <?php

            if(!$_POST["nom_prod"] || !$_POST["prix_htva"] || !$_POST["tva"] || !$_POST["qte"]){
                echo "<p style = 'color: red;'>You have not entered all the inscription details.<br/>
                Please go back and try again later.
                </p>";
                exit;
            }

            $nomprod = $_POST["nom_prod"];
            $prixhtva = $_POST["prix_htva"];
            $tva = $_POST["tva"];
            $qte = $_POST["qte"];

            @$db = new mysqli('localhost', 'bookuser', 'bookpwd', 'XOOS_DB');
            if(mysqli_connect_errno()){
                echo "<p>Error: Could not connect to database.
                Please try again later.
                </p>";
                exit;
            }

            $query = "INSERT INTO PRODUIT (Nom_Prod, Prix_HTVA_Prod, TVA, Quantite_Prod) VALUES (?, ?, ?, ?)";
            $ins = $db->prepare($query);
            $ins->bind_param('ssss', $nomprod, $prixhtva, $tva, $qte);
            $ins->execute();
            
            

        ?>
    </body>
</html>
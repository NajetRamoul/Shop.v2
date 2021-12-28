
<!DOCTYPE html>
<html lang="fr">
<head>   
<meta charset="UTF-8">
<title>Produits</title> 
<script src="API/api.js"></script>
</head> 
<body>
     <?php
     include ('includes/head.php');
     include ('includes/menu.php');
     require('databases/connexionDatabase.php');
     $bdd = connexionDB($host,$username,$login,$password);

     $requete= $bdd->prepare("SELECT * FROM fournisseurs");
     $requete->execute();
     $requete = $requete->fetchAll();
     
     foreach($requete as $fournisseur) {        
         ?> 
     <section class="section" > 
        <div class="boxx">
            <div class="boxx-inner"> 
                <div class="boxx-front"> 
                    <img src="images/photo6.jpeg" alt="photo d'un fournisseur">
                </div>
                <div class="boxx-back">
                    <ul>
                        <li>Nom fournisseur: <?= htmlspecialchars(strip_tags($fournisseur["nom_fournisseur"]));?></li>
                        <li>Pays: <?= htmlspecialchars(strip_tags($fournisseur["pays"])); ?></li>
                        <div>
                           <input type="text" placeholder="Produit" id="produit<?php echo $fournisseur['id'] ?>">
                           <button onclick="addProduct(<?php echo $fournisseur['id'] ?>)" id="result">Ajouter</button>
                           <button onclick="getProduct(<?php echo $fournisseur['id']?>)">Afficher</button>
                            <p id="fournisseur<?php echo $fournisseur['id']?>"></p>
                        </div>
                        <div>
                         
                        </div>
                     </ul>         
                </div>
            </div>
        </div>
    </section>
 <?php } ?>
 <?php include ('includes/footer.php'); ?>

</body>
</html>


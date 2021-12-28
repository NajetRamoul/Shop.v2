
<!DOCTYPE html>
<html lang="fr">
<head>   
<meta charset="UTF-8">
<title>Clients</title>
</head> 
 
 <body>
     <?php
     include ('includes/head.php');
     include ('includes/menu.php');
     require('databases/connexionDatabase.php');
    
     $bdd = connexionDB($host,$username,$login,$password);
    if(isset($_GET['id'])){
         $commande=htmlspecialchars(strip_tags($_GET['id']));
         $commandeCount=$bdd->prepare("SELECT COUNT(client) as totalCommande FROM commandes WHERE client =$commande");
         $commandeCount->execute();
         $commandeCount = $commandeCount->fetch();

        if($commandeCount['totalCommande'] == 0){
            echo" Aucune commmande n'a été effectuée";
            }else{
                $requete = $bdd->query("SELECT * FROM commandes WHERE client =".$_GET['id']);
                foreach($requete as $commande){ ?>
                <section class="section">  
                    <div class="box">    
                        <div class="boxinner"> 
                            <div class="boxfront">       
                                <h1>Commande <?=$commande['id'] ?></h1>
                                <img src="<?= $commande['enseigne']?>">
                            </div>
                           <div class="boxback"> 
                                <ul>  
                                    <?php $produits=$bdd->query("SELECT * FROM produits
                                    INNER JOIN commandes_produits cp ON cp.produit_id = produits.id
                                    INNER JOIN commandes ON cp.commande_id=commandes.id
                                    WHERE commandes.id=".$commande['id']);                    
                                    foreach($produits as $produit){ ?>
                                    <li> <?php echo $produit['produit'] ?></li>
                                    <?php } ?>         
                                </ul>
                            </div>    
                        </div>
                    </div>
                </section>     
          <?php }  
        }
    }
    include ('includes/footer.php'); ?>
</body>
</html>



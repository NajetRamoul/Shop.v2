<!DOCTYPE html>
<html lang="fr">
<head>   
<meta charset="UTF-8">
<title>Commande</title>
</head> 
 
 <body>
     <?php
     include ('includes/head.php');
     include ('includes/menu.php');
     require('databases/connexionDatabase.php');
     $bdd = connexionDB($host,$username,$login,$password);

     $commandesParPage = 5;
     $commandesTotalesReq = $bdd->query('SELECT id FROM commandes');
     $commandesTotales = $commandesTotalesReq -> rowCount();
     $pagesTotales = ceil($commandesTotales / $commandesParPage);

     if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0){
        $_GET['page'] = intval($_GET['page']);
        $pageCourante = $_GET['page'];
         }else{
        $pageCourante = 1;
    }
     $depart = ($pageCourante-1)* $commandesParPage;
    
     $requete = $bdd->prepare('SELECT * FROM commandes LIMIT '.$depart. ',' .$commandesParPage);
     $requete->execute();
     $requete = $requete->fetchAll();

     foreach($requete as $commande) 
     {?>
     <section class="section">

        <div class="box"> 
            <div class="boxinner"> 
                 <div class="boxfront">  
                     <h1>Commande <?=$commande['id'] ?></h1>
                     <img src="<?= $commande['enseigne']?>">
                </div>
                 <div class="boxback">  
                    <ul>  
                        <li>id :<?= htmlspecialchars(strip_tags($commande["id"])); ?></li>
                        <li>client :<?= htmlspecialchars(strip_tags($commande["client"])); ?></li>
                        <li>date : <?= htmlspecialchars(strip_tags($commande["dateAchat"])); ?></li>
                        <?php $produits=$bdd->query("SELECT * FROM produits
                        INNER JOIN commandes_produits cp ON cp.produit_id = produits.id
                        INNER JOIN commandes ON cp.commande_id=commandes.id
                        WHERE commandes.id=".$commande['id']);                    
                        foreach($produits as $produit){ ?>
                        <li> <?php echo $produit['produit'] ?></li>
                        <?php  } ?>               
                        <li>Date de livraison: <?= htmlspecialchars(strip_tags($commande["dateDeLivraison"])); ?></li> 
                        <li> Payement: <?= htmlspecialchars(strip_tags($commande['payement']));?></li> 
                    </ul>
                 </div>    
            </div>
        </div>
    </section>     
    <?php }?>
    <?php for($i=1; $i<=$pagesTotales; $i++){
        if($i == $pageCourante) {
             echo $i. ' ' ;
            }else{
                echo'<a href= commandes.php?page=' .$i. '">' .$i.'</a> '; 
            }
        }
    ?>
<?php include ('includes/footer.php'); ?>   
</body>
</html>

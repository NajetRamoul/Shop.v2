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

     $requete= $bdd->prepare("SELECT * FROM clients");
     $requete->execute();
     $requete = $requete->fetchAll();

     foreach($requete as $client) 
     {?>  
     <section class="section"> 
     <div class="box"> 
         <div class="box-inner"> 
             <div class="box-front"> 
                 <img src="images/photo8.jpg" alt="photo d'un client">
             </div>
             <div class="box-back">
                <ul>
                <li>
                    <a href="commande.php?id=<?php echo $client['id'] ?>"><?= htmlspecialchars(strip_tags($client["nom"])); ?></a>
                </li>
                <li>Pseudo: <?= htmlspecialchars(strip_tags($client["pseudo"])); ?></li>
                <li>Age: <?= htmlspecialchars(strip_tags($client["age"])); ?></li>
                <li>Mail<?=  htmlspecialchars(strip_tags($client["mail"])); ?></li>
                <li>Adresse: <?= htmlspecialchars(strip_tags($client["adresse"])); ?></li>    
                </ul>
             </div>
         </div>
     </div>
 </section>
 <?php }?>
 <?php include ('includes/footer.php'); ?>
</body>
</html>


    


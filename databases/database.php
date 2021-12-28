<?php
require('connexionDatabase.php');

$bdd = connexion($host,$login,$password);

$clients = file_get_contents ("https://filrouge.uha4point0.fr/Shop/clients");
$apiClients = json_decode($clients, true);


$commandes = file_get_contents ("https://filrouge.uha4point0.fr/Shop/commandes");
$apiCommandes = json_decode($commandes, true);

$sql = (" drop database if exists $username");
$bdd->exec($sql);

$sql = ("create database if not exists $username");
$bdd->exec($sql);

$bdd = connexionDB($host,$username,$login,$password);
 
 $sql = ("CREATE TABLE IF NOT EXISTS clients (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` varchar(255) NOT NULL,
    `pseudo` varchar(255) NOT NULL,
    `age` int(11) NOT NULL,
    `mail` varchar(255) NOT NULL,
    `adresse` varchar(255) NOT NULL
    )");
          
     $bdd->exec($sql);   

     $sql = ("CREATE TABLE IF NOT EXISTS commandes (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `client` int NOT NULL,
        `dateAchat` varchar(11) NOT NULL,
        `dateDeLivraison` varchar(11) NOT NULL,
        `payement` varchar(255) NOT NULL,
        `enseigne` varchar(255) NOT NULL,
        FOREIGN KEY (client) REFERENCES clients(id)
        )");
        $bdd->exec($sql);
    
    $sql = ("CREATE TABLE IF NOT EXISTS produits (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `produit` VARCHAR(255) NOT NULL 
         )");
         $bdd->exec($sql);
                   
    $sql = ("CREATE TABLE IF NOT EXISTS commandes_produits (
        `produit_id` INT NOT NULL,
        `commande_id` INT NOT NULL,
        PRIMARY KEY (produit_id, commande_id),
        FOREIGN KEY (produit_id) REFERENCES produits(id),
        FOREIGN KEY (commande_id) REFERENCES commandes(id)
        )");
         $bdd->exec($sql);   
      
    $sql =("CREATE TABLE IF NOT EXISTS fournisseurs (
        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `nom_fournisseur` VARCHAR(150) UNIQUE NOT NULL,
        `pays` VARCHAR(255) NOT NULL,
        FOREIGN KEY (id) REFERENCES produits(id)
         )");
         $bdd->exec($sql);
                    
     $sql = ("CREATE TABLE IF NOT EXISTS fournisseurs_produits (
            `produit_id` INT NOT NULL,
            `fournisseur_id` INT NOT NULL,
            PRIMARY KEY (produit_id, fournisseur_id),
            FOREIGN KEY (produit_id) REFERENCES produits(id),
            FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
            )");
             $bdd->exec($sql);   
    
    foreach( $apiClients as $client){ 
        $nom = htmlspecialchars($client['nom']);
        $pseudo = htmlspecialchars($client['pseudo']);
        $age = htmlspecialchars($client['age']);
        $mail = htmlspecialchars($client['mail']);
        $adresse = htmlspecialchars($client['adresse']);

        $dataClients= $bdd->query("SELECT * FROM clients WHERE nom = '$nom'");
        $dataClient = $dataClients->fetch();
         if(!$dataClient){
        
        $requete = $bdd->prepare("INSERT INTO clients (nom, pseudo, age, mail, adresse) VALUES (:nom, :pseudo, :age, :mail, :adresse)");
        $requete->execute(array(
            'nom' => $nom,
            'pseudo' => $pseudo,
            'age' => $age,
            'mail' => $mail,
            'adresse' => $adresse
        ));  
   
        $requete->closeCursor();  
        }
    }                
$produit = []; 
foreach( $apiCommandes as $commande){
    $id = $commande['id'];
    $client = $commande['client'];
    $dateAchat = htmlspecialchars($commande['date']);
    $dateDeLivraison = htmlspecialchars($commande['dateDeLivraison']);
    $payement= htmlspecialchars($commande['payement']);
    $enseigne= htmlspecialchars($commande['enseigne']);

    $dataCommandes= $bdd->query("SELECT * FROM commandes WHERE  id = '$id'");
    $dataCommande = $dataCommandes->fetch();

        if(!$dataCommande){
            $requete = $bdd->prepare("INSERT INTO commandes (client, dateAchat, dateDeLivraison, payement, enseigne) VALUES (:client,:dateAchat,:dateDeLivraison,:payement, :enseigne)");
             $requete->execute(array(
              'client' => $client,
              'dateAchat' => $dateAchat,
              'dateDeLivraison' => $dateDeLivraison,
              'payement' => $payement,
              'enseigne' => $enseigne
            ));  
            $requete->closeCursor();  
        }  
    foreach($commande['products'] as $produit){

        $requeteSearch = $bdd->prepare("SELECT * FROM produits WHERE produit = :produits");
        $requeteSearch->execute(array(
            'produits'=>($produit)
        ));

        if($requeteSearch->fetch()===false){
            $requete = $bdd->prepare("INSERT INTO produits (produit) VALUES (:produit)");
            $requete->execute(array('produit' => $produit));     
        }

            $requete = $bdd->prepare("SELECT id FROM produits WHERE produit = :produits");
            $requete->execute(array(
                'produits'=>$produit
            ));      

        $produitId=$requete->fetch();
        if(!$dataCommande){

            $requetes=$bdd->prepare("INSERT INTO commandes_produits (produit_id, commande_id) VALUES (:produit_id, :commande_id)");
            $requetes->execute(array(
                'produit_id'=>$produitId['id'],
                'commande_id'=>$commande['id']
            ));
            $requete->closeCursor();
        }    
    }
}
$sql =("INSERT INTO fournisseurs (nom_fournisseur, pays) VALUES
   ('Conforama', 'France'),
   ('But', 'Chine'),
   ('Buittoni', 'USA')");
   $bdd->exec($sql);   

$sql =("INSERT INTO fournisseurs_produits (produit_id, fournisseur_id) VALUES
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,2),
(8,2),
(9,2),
(10,2),
(11,2),
(12,3),
(13,3),
(14,2),
(15,3),
(16,3),
(17,3),
(18,3),
(19,3),
(20,3),
(21,3)");
$bdd->exec($sql);  
header("location: ../index.php");
?>


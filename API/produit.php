<?php
header("Content-Type: application/json; charset=UTF-8");

include("../databases/connexionDatabase.php");

$bdd = new PDO("mysql:host=$host;dbname=$username;charset=utf8", $login, $password);

$request_method= $_SERVER["REQUEST_METHOD"];

switch($request_method)
{
    case 'GET':
        if (!empty($_GET['id'])) {
            $id = intval($_GET["id"]);
            getProduit($id);
        }else if (!empty($_GET['page'])){
            $page = intval($_GET['page']);
           getProduitsPage($page);
        } else {
            getProduits();
        } break;
        default:
    
        header("HTTP//1.0 405 Method Not Allowed");
        break;
     // ajout 
    case 'POST':
        if(isset($_POST['fournisseur_id']) ){
            $produitNom = htmlspecialchars($_POST['produit']);
            $fournisseurID = htmlspecialchars($_POST['fournisseur_id']);
            addProduitWithFournisseur($produitNom, $fournisseurID);
        }
        else{    
           addProduit();
        }
    break;
    // modifier
    case 'PUT':
        $id = ($_GET["id"]);
        updateProduit($id);
        break;  
    case 'DELETE':
        $id = htmlspecialchars(intval($_GET["id"]));
        deleteProduit($id);
    break;  
}
function getProduitsPage($page=0){
    global $bdd;
    //si le numero de page dans l'url est invalide, renvoie à la page 1
    if(empty($page)){
        $page=1;
    }
    $getPage = 5;
    $offSet = ($page-1)*$getPage;

    $selectProduitPage = "SELECT * FROM produits LIMIT $offSet, $getPage";
    $selectedProduitPage= array();
    //Stock le resultat de la requête selectProduitPage.
    $resultProduitPage = $bdd->query($selectProduitPage);
    //On met le resultat de la requete dans le tableau pour afficher en JSON
    while($produitRow = $resultProduitPage->fetch(PDO::FETCH_ASSOC))
    {
        $selectedProduitPage[] = $produitRow;
    }
    echo json_encode($selectedProduitPage, JSON_PRETTY_PRINT);
}
function getProduits()
{ 
    global $bdd;
    $sql = $bdd->query("SELECT * FROM produits");
    $api = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($api, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);      
}
function getProduit($id=0)
{
    global $bdd;
    if($id != 0) {
        $sql = $bdd->query("SELECT * FROM produits WHERE id = $id");
        $fournisseur = $sql->fetch(PDO::FETCH_ASSOC);
    }
    else{
        $sql = $bdd->query("SELECT * FROM produits");
        $fournisseur = $sql->fetch(PDO::FETCH_ASSOC);
    }
    if($fournisseur) {
        echo json_encode($fournisseur, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);   
    }
    else {
        echo "Aucune données !";
    }
}
function addProduit() 
{
    global $bdd;
    $produitNom = htmlspecialchars($_POST['produit']);

     $addProduit = "INSERT INTO produits(produit) VALUES('" .$produitNom.  "')";

    if ($resultAddProduit = $bdd->query($addProduit)) {
        $result = array(
            'status' => 1,
            'status_message' => "Le produit a été ajouté avec succes.",
            'produitNom' =>  $produitNom,
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de l'ajout !."
        );
    }
    echo json_encode($result);
}
function addProduitWithFournisseur($produitNom, $fournisseurID) 
{
    global $bdd;
    $produitNom = htmlspecialchars($_POST['produit']);

     $addProduit = "INSERT INTO produits(produit) VALUES('" .$produitNom.  "')";

    if ($resultAddProduit = $bdd->query($addProduit)) {
        // récupérer id du produit ajouté
        $produitID = $bdd->lastInsertId();
        addLienProduitFournisseur($produitID, $fournisseurID);

        
        $result = array(
            'status' => 1,
            'status_message' => "Le produit a été ajouté avec succes.",
            'produitNom' =>  $produitNom,
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de l'ajout !."
        );
    }
    echo json_encode($result);
}
function addLienProduitFournisseur($produitId, $fournisseurId) 
{
    global $bdd;
   
     $addProduit = "INSERT INTO fournisseurs_produits (produit_id, fournisseur_id) VALUES('" . $produitId . "', '" .$fournisseurId. "')";
    if ($resultAddProduit = $bdd->query($addProduit)) {
        $result = array(
            'status' => 1,
            'status_message' => "Le produit a été ajouté avec succes."
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de l'ajout !."
        );
    }
    return json_encode($result);
}
function updateProduit($id)
 { 
    global $bdd;
    $_PUT = array(); // save received data
    parse_str(file_get_contents('php://input'), $_PUT);
    $produitId = htmlspecialchars($_PUT["produit"]);
     $updateProduit = "UPDATE produits SET produit = '$produitId' WHERE id=$id";

    if ($resultUpdateProduit = $bdd->query($updateProduit )) {
        $result = array(
            'status' => 1,
            'status_message' => "Le produit a été modifié avec succes",
            'produit' => $produitId
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de la modification !."
        );
    }
    echo json_encode($result);
}
function deleteProduit($id) 
{ 
    global $bdd;
     $deleteFournisseur = "DELETE FROM produits WHERE id =" . $id;

     if ($DeleteProduit  = $bdd->query($deleteProduit)) {
        $result = array(
            'status' => 1,
            'status_message' => 'Le produit a été supprimé avec succes'
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => 'Il y a eu une ERREUR lors de la suppression!.'
        );
    }
    echo json_encode($result);
}
?>

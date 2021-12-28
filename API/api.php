<?php
header("Content-Type: application/json; charset=UTF-8");

include("../databases/connexionDatabase.php");
$bdd = new PDO("mysql:host=$host;dbname=$username;charset=utf8", $login, $password);
$request_method= $_SERVER["REQUEST_METHOD"];

switch($request_method)
{
  case 'GET':
      if (!empty($_GET['fournisseur_id'])) {
          $id = $_GET['fournisseur_id'];
          getProduit($id);
        }else if (!empty($_GET['page'])){
            $page = intval($_GET['page']);
           produitPage($page);
        } else {
            getProduits();
        } break;
        default:
        header("HTTP//1.0 405 Method Not Allowed");
        break;
     // ajout 
    case 'POST':
        $produitId = htmlspecialchars($_POST['produit_id']);
        $fournisseurId = htmlspecialchars($_POST['fournisseur_id']);
        addLienProduitFournisseur($produitId, $fournisseurId);
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
function produitPage($page=0){
    global $bdd;
    //si le numero de page dans l'url est invalide, renvoie à la page 1
    if(empty($page)){
        $page=1;
    }
    $getPage = 5;
    $offSet = ($page-1)*$getPage;

    $selectProduitPerPage = "SELECT * FROM fournisseurs_produits LIMIT $offSet, $getPage";
    $selectedProduitPerPage= array();

    $resultProduitPerPage = $bdd->query($selectProduitPerPage);

    while($productRow = $resultProduitPerPage->fetch(PDO::FETCH_ASSOC))
    {
        $selectedProduitPerPage[] = $productRow;
    }
    echo json_encode($selectedProduitPerPage, JSON_PRETTY_PRINT);
}   
function getProduits()
{ 
    global $bdd;
    $sql = $bdd->query("SELECT * FROM produits INNER JOIN fournisseurs_produits fp ON produit_id = produits.id INNER JOIN fournisseurs ON fp.fournisseur_id=fournisseurs.id");
    $api = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($api, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);    
}
function getProduit($id=0)
{
    global $bdd;
    if($id != 0) {
        $sql = $bdd->query(" SELECT * FROM fournisseurs_produits fp INNER JOIN produits ON produits.id = fp.produit_id INNER JOIN fournisseurs ON fournisseurs.id=fp.fournisseur_id WHERE fournisseur_id= $id");
        $fournisseur = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $sql = $bdd->query("SELECT * FROM produits INNER JOIN fournisseurs_produits fp ON produit_id = produits.id INNER JOIN fournisseurs ON fp.fournisseur_id=fournisseurs.id");
        $fournisseur = $sql->fetch(PDO::FETCH_ASSOC);
    }
    if($fournisseur) {
        echo json_encode($fournisseur, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);   
    }
    else {
        echo "Aucune données !";
    }
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
    echo json_encode($result);
}

function updateProduit($id)
 { 
    global $bdd;
    $_PUT = array(); // save received data
    parse_str(file_get_contents('php://input'), $_PUT);

    $produitId = htmlspecialchars($_POST['produit_id']);
    $fournisseurId = htmlspecialchars($_POST['fournisseur_id']);
   
     $updateProduit = "UPDATE  fournisseurs_produits( SET produit_id = '$produitId', fournisseur_id='$fournisseurId WHERE id=$id";

    if ($resultUpdateProduit = $bdd->query($updateProduit )) {
        $result = array(
            'status' => 1,
            'status_message' => "Le produit a été modifié avec succes."
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
     $deleteFournisseur = "DELETE FROM fournisseurs_produits WHERE produit_id =" . $id;

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


<?php
header("Content-Type: application/json; charset=UTF-8");

include("../databases/connexionDatabase.php");

$bdd = new PDO("mysql:host=$host;dbname=$username;charset=utf8", $login, $password);

$request_method= $_SERVER["REQUEST_METHOD"];

  switch($request_method)
  {
    case 'GET':
        if (!empty($_GET['id'])) {
            $id = $_GET["id"];
            getfournisseur($id);

        } else if(!empty($_GET['page'])){
            $page = intval($_GET['page']);
            getFournisseurPage($page);
        }else {
            getFournisseurs();
        } break;
        default:
    
        header("HTTP//1.0 405 Method Not Allowed");
        break;
     // ajout 
    case 'POST':
        addFournisseur();
    break;
    // modifier
    case 'PUT':
        $id = ($_GET["id"]);
        updateFournisseur($id);
        break;  
    case 'DELETE':
        $id = htmlspecialchars(intval($_GET["id"]));
        deleteFournisseur($id);
    break;
}
function getFournisseurPage($page=0){
    global $bdd;
    if(empty($page)){
        $page=1;
    }
    $getPage = 5;
    $offSet = ($page-1)*$getPage;

    $selectFournisseurPage = "SELECT * FROM fournisseurs LIMIT $offSet, $getPage";
    $selectedFournisseurPage= array();
    //Stock le resultat de la requête selectFournisseurPage.
    $resultFournisseurPage = $bdd->query($selectFournisseurPage);
    //On met le resultat de la requete dans le tableau pour afficher en JSON
    while($fournisseurRow = $resultFournisseurPage->fetch(PDO::FETCH_ASSOC))
    {
        $selectedFournisseurPage[] = $fournisseurRow;
    }
    echo json_encode($selectedFournisseurPage, JSON_PRETTY_PRINT);
}
function getFournisseurs()
{ 
    global $bdd;
    $sql = $bdd->query("SELECT * FROM fournisseurs");
    $api = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($api, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);       
}
function getFournisseur($id=0)
{
    global $bdd;
    if($id != 0) {
        $sql = $bdd->query("SELECT * FROM fournisseurs WHERE id = $id");
        $fournisseur = $sql->fetch(PDO::FETCH_ASSOC);
    }
    else{
        $sql = $bdd->query("SELECT * FROM fournisseurs");
        $fournisseur = $sql->fetch(PDO::FETCH_ASSOC);
    }
    if($fournisseur) {
        echo json_encode($fournisseur, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);   
    }
    else {
        echo "Aucune données !";
    }
}
function addFournisseur() 
{
    global $bdd;

    $nomFournisseur = htmlspecialchars($_POST['nom_fournisseur']);
    $pays = htmlspecialchars($_POST['pays']);
   

     $addFournisseur = "INSERT INTO fournisseurs(nom_fournisseur, pays) VALUES('" . $nomFournisseur. "', '" .$pays. "')";

    if ($resultAddFournisseur = $bdd->query($addFournisseur)) {
        $result = array(
            'status' => 1,
            'status_message' => "Le fournisseur a été ajouté avec succes."
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de l'ajout !."
        );
    }
    echo json_encode($result);
}
function updateFournisseur($id)
 { 
    global $bdd;
    $_PUT = array(); // save received data
    parse_str(file_get_contents('php://input'), $_PUT);

    $nomFournisseur = htmlspecialchars($_PUT["nom_fournisseur"]);
    $pays = htmlspecialchars($_PUT['pays']);
   
     $updateFournisseur = "UPDATE fournisseurs SET nom_fournisseur ='$nomFournisseur', pays='$pays' WHERE id=$id";

    if ($resultUpdateFournisseur = $bdd->query($updateFournisseur)) {
        $result = array(
            'status' => 1,
            'status_message' => "Le fournisseur a été modifié avec succes."
        );
    } else {
        $result = array(
            'status' => 0,
            'status_message' => "Il y a eu une erreur lors de la modification !."
        );
    }
    echo json_encode($result);
}
function deleteFournisseur($id) 
{ 
    global $bdd;
     $deleteFournisseur = "DELETE FROM fournisseurs WHERE id =" . $id;

     if ($DeleteFournisseur = $bdd->query($deleteFournisseur)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Le fournisseur a été supprimé avec succes'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'Il y a eu une ERREUR lors de la suppression!.'
        );
    }
    echo json_encode($response);
}
?>
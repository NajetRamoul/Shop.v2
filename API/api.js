/*Affiche les produits*/
function getProduct(id) {

    const produit = document.getElementById("fournisseur"+id)
  
    if(produit.innerHTML.length > 0){
      produit.innerHTML="";
    }else{
      fetch("http://localhost/najet.ramoul_shop/API/api.php?fournisseur_id="+id)
        .then(function(res) {
          if (res.ok) {
            return res.json();
          }
       })
      .then(function(value) {
        for (let i =0; i < value.length; i++){
          produit.innerHTML += "<ul></li>" + value[i].produit + "</ul></li>";
        }
    })
      .catch(function(err) {
      });
    }
  }
  /*Ajoute un produit*/
  function addProduct(idFournisseur){
    let produit = document.getElementById('produit'+idFournisseur).value
  
    fetch("http://localhost/najet.ramoul_shop/API/produit.php",{
  
      method: 'POST',
      body:"produit="+encodeURIComponent(produit)+"&fournisseur_id="+encodeURIComponent(idFournisseur), 
      headers:{
        'Accept': 'application/json, text/plain',
        'Content-type': 'application/x-www-form-urlencoded;charset=UTF-8'
      }
    })
    .then(function(response){
      console.log(response);
      return response.json()
    })
    .then(function(data){
      console.log(data)
      const deleteProduit = document.getElementById(produit.toString());
      deleteProduit.remove();
      let result = document.getElementById('result'+idFournisseur)
      result.innerHTML = `<p> produit: ${data.produitNom}</p>` 
    })
    location.reload();
  }
  
  
  
    
    
  
  
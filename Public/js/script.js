// L'événement 'onload' est attaché à la fenêtre. Cette fonction s'exécute lorsque toute la page, y compris toutes les dépendances
// comme les feuilles de style et les images, sont complètement chargées.
window.onload = () => {
    // Sélection de tous les éléments avec la classe 'custom-control-input'
    let buttons = document.querySelectorAll(".custom-control-input");

    // Boucle pour parcourir chaque bouton récupéré par la requête précédente
    for(let button of buttons){
        // Ajout d'un écouteur d'événements sur chaque bouton. Lorsqu'un bouton est cliqué, la fonction 'activer' est appelée.
        button.addEventListener("click", activer)
    }
}

// Définition de la fonction 'activer' qui sera appelée chaque fois qu'un bouton est cliqué.
function activer(){
    // Création d'un nouvel objet XMLHttpRequest pour permettre la communication avec un serveur
    let xmlhttp = new XMLHttpRequest();
    
    // Configuration de la requête HTTP 'GET'. L'URL inclut l'ID spécifique de l'annonce, tiré de l'attribut 'data-id' du bouton cliqué.
    // `this.dataset.id` fait référence à l'attribut 'data-id' de l'élément qui a déclenché l'événement (le bouton cliqué).
    xmlhttp.open("GET", "/PHP/MVC_Annonces/admin/activeAd/" + this.dataset.id);

    // Envoi de la requête au serveur.
    xmlhttp.send();
}
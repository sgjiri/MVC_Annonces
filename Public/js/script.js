window.onload = () => {
    let buttons = document.querySelectorAll(".custom-control-input");

    for(let button of buttons){
        button.addEventListener("click", activer)
    }
}

function activer(){

    let xmlhtpp = new XMLHttpRequest;
    xmlhtpp.open("GET", "/PHP/MVC_Annonces/admin/activeAd/" + this.dataset.id);
    xmlhtpp.send();
}
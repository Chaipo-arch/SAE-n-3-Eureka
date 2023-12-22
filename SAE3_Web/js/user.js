



// Récupérer les éléments du DOM
var modal = document.getElementById('myModal');
var btnOpenModal = document.getElementById('openModalBtn');
var btnCloseModal = document.getElementById('closeModalBtn');

// Ajouter un gestionnaire d'événement pour ouvrir la modal
btnOpenModal.addEventListener('click', function() {
modal.style.display = 'block';
});

// Ajouter un gestionnaire d'événement pour fermer la modal
btnCloseModal.addEventListener('click', function() {
modal.style.display = 'none';
});

// Fermer la modal si l'utilisateur clique en dehors du contenu
window.addEventListener('click', function(event) {
if (event.target === modal) {
  modal.style.display = 'none';
}
});

document.getElementById('role').addEventListener('change', function() {
  if(this.value == 2){
    document.getElementById('filiere').hidden=false;
  }else{
    document.getElementById('filiere').hidden=true;
  }
});





let tableauElements = [];
let element;
for (let i = 1; i <= 60; i++) {
  let idElement = 'id' + i;
  element = document.getElementById(idElement);

  // Vérifier si l'élément existe avant de l'ajouter au tableau
  if (element) {
    tableauElements.push(element);
  }
}

document.getElementById('roles').addEventListener('change', function () {
  console.log(this.value);
  tableauElements.forEach(element => {
    let partieCoupee = element.id.substring(2);
    document.getElementById("id"+partieCoupee).hidden = false;
    console.log(element.value);
    // Utilisez innerHTML ou textContent pour obtenir le contenu texte de l'élément
    if (this.value !== element.value && this.value !== "0") {
      document.getElementById("id"+partieCoupee).hidden = true;
    }
  });
});


tableauElements.forEach(element => {
  let partieCoupee = element.id.substring(2);
  console.log(partieCoupee);
  document.getElementById('modif'+partieCoupee).addEventListener('click', function() {
      console.log("eho");
        let partieCoupee = element.id.substring(2);
        var paragraphe = document.getElementById('nom'+partieCoupee);
        if(paragraphe.tagName.toLowerCase() != 'input'){

        

        // Créer une nouvelle balise input
        var input = document.createElement('input');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.value = paragraphe.textContent;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);
        
        paragraphe = document.getElementById('prenom'+partieCoupee);
        // Créer une nouvelle balise input
        var input = document.createElement('input');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.value = paragraphe.textContent;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);

        paragraphe = document.getElementById('password'+partieCoupee);
        // Créer une nouvelle balise input
        var input = document.createElement('input');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.value = paragraphe.textContent;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);

        document.getElementById('valideLaModif'+partieCoupee).hidden = false;
        
      }else{
        document.getElementById('valideLaModif'+partieCoupee).hidden = true;
        // Créer une nouvelle balise input
        var input = document.createElement('p');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.textContent = paragraphe.value;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);
       
        paragraphe = document.getElementById('prenom'+partieCoupee);
        // Créer une nouvelle balise input
        var input = document.createElement('p');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.textContent = paragraphe.value;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);

        paragraphe = document.getElementById('password'+partieCoupee);
        // Créer une nouvelle balise input
        var input = document.createElement('p');
        
        // Copier le texte du paragraphe dans la valeur de l'input
        input.textContent = paragraphe.value;
        input.id = paragraphe.id;
        input.name = paragraphe.name;
        paragraphe.parentNode.replaceChild(input, paragraphe);
      }
      });
    

});
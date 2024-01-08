



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
  if(this.value == 3){
    document.getElementById('filiere').hidden=false;
  }else{
    document.getElementById('filiere').hidden=true;
  }
});





let tableauElements = [];
let element;
for (let i = 1; i <= 200000; i++) {
  let idElement = 'id' + i;
  element = document.getElementById(idElement);

  // Vérifier si l'élément existe avant de l'ajouter au tableau
  if (element) {
    tableauElements.push(element);
  }
}




tableauElements.forEach(element => {
  let partieCoupee = element.id.substring(2);
  console.log(partieCoupee);
  document.getElementById('modif' + partieCoupee).addEventListener('click', function () {
      let partieCoupeeLocal = partieCoupee;  // Utilisez une variable locale ici
      var paragraphes = ["nom", "prenom", "password", "username"];

      paragraphes.forEach(type => {
        var paragraphe = document.getElementById(type + partieCoupeeLocal);
      if (paragraphe.tagName.toLowerCase() != 'input') {

          // Stocker temporairement le nom

          // Créer une nouvelle balise input
          var input = document.createElement('input');
          
          // Copier le texte du paragraphe dans la valeur de l'input
          input.value = paragraphe.textContent;
          input.id = paragraphe.id;
          input.setAttribute("name", paragraphe.getAttribute('name'));
         
          paragraphe.parentNode.replaceChild(input, paragraphe);
          
          // Répéter le processus pour les autres champs (prénom, password)

          document.getElementById('valideLaModif' + partieCoupee).hidden = false;

      } else {
          document.getElementById('valideLaModif' + partieCoupee).hidden = true;

          // Récupérer temporairement le nom depuis l'attribut data-name

          // Créer une nouvelle balise input
          var input = document.createElement('p');

          // Copier le texte du paragraphe dans la valeur de l'input
          input.textContent = paragraphe.value;
          input.id = paragraphe.id;
          input.setAttribute("name", paragraphe.getAttribute('name'));
          paragraphe.parentNode.replaceChild(input, paragraphe);

          // Répéter le processus pour les autres champs (prénom, password)
      }
      
      });
  });
});

    


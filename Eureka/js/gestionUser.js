
annuler =  document.getElementById("annuler");

annuler.addEventListener("click", function() {

    document.getElementById('myModal').style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = 'none';
    }
});



let tableauElements = [];
let element;
for (let i = 1; i <= 6; i++) {
  let idElement = 'supprimerUtilisateur' + i;
  element = document.getElementById(idElement);
  element.addEventListener("click", function() {

    document.getElementById('myModal'+i).style.display = 'block';
});
  // Vérifier si l'élément existe avant de l'ajouter au tableau
  if (element) {
    tableauElements.push(element);
  }
}
// Ajouter des écouteurs pour les éléments annuler
for (let i = 1; i <= 6; i++) {
  let idElement = 'annuler' + i;
  let element = document.getElementById(idElement);

  if (element) {
    element.addEventListener("click", function() {
      document.getElementById('myModal'+i).style.display = 'none';
    });
  }
}

// Ajouter des écouteurs pour les éléments supprimerUtilisateur
for (let i = 1; i <= 6; i++) {
  let idElement = 'supprimerUtilisateur' + i;
  let element = document.getElementById(idElement);

  if (element) {
    element.addEventListener("click", function() {
      document.getElementById('myModal' + i).style.display = 'block';
    });
  }
}

// Ajouter un écouteur pour la fenêtre
window.addEventListener('click', function(event) {
  if (event.target == document.getElementById('myModal')) {
    document.getElementById('myModal').style.display = 'none';
  }
});

reinitialisation = document.getElementById("deco");

reinitialisation.addEventListener("click", function() {
    document.getElementById('myModals').style.display = 'block';
    console.log("affiche");
  });

annulez = document.getElementById("annul");


annulez.addEventListener("click", function() {
      document.getElementById('myModals').style.display = 'none';
      console.log("affichePas");
    });

  window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('myModals')) {
      document.getElementById('myModals').style.display = 'none';
    }
  });
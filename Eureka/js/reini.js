reinitialisation = document.getElementById("reini");

reinitialisation.addEventListener("click", function() {
    document.getElementById('myModal').style.display = 'block';
    console.log("affiche");
  });

annulez = document.getElementById("annuler");


annulez.addEventListener("click", function() {
      document.getElementById('myModal').style.display = 'none';
      console.log("affichePas");
    });

  window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('myModal')) {
      document.getElementById('myModal').style.display = 'none';
    }
  });
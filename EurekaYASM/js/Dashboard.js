// Utilisez jQuery pour simplifier la requête AJAX


$(document).ready(function() {
  $.ajax({
      url: 'http://localhost/sae/Eureka/API/statNombreEtudiantParFiliere', // Remplacez par le chemin correct vers votre script PHP
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        updateChart(data);; // Appel de la fonction pour mettre à jour le graphique avec les données récupérées
      },
      error: function(error) {
          console.log('Erreur lors de la récupération des données:', error);
      }
  });
});


$(document).ready(function() {
  $.ajax({
      url: 'http://localhost/sae/Eureka/API/statNombreEtudiantParFiliere', // Remplacez par le chemin correct vers votre script PHP
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        updateChart(data);; // Appel de la fonction pour mettre à jour le graphique avec les données récupérées
      },
      error: function(error) {
          console.log('Erreur lors de la récupération des données:', error);
      }
  });
});

function updateChart(data) {
  const ctx = document.getElementById('myChart');
  const labels = data.map(item => item.field);
  const values = data.map(item => item.nombreEtudiant);
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Nombre d\'étudiants',
        data: values,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}




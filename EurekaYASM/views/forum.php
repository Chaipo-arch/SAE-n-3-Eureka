<?php
if (!isset($_SESSION['connecte']) || !$_SESSION['connecte']) {
    //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
    header('Location: index.php?action=renvoi');
    exit();
}
?>
<html>
<head>
	<title>Forum</title>
	<meta charset ="utf-8"/>
	<link href="bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet"/>
	<link href="css/HeaderCss.css" rel="stylesheet">
	<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"/>
</head>	
<body>
<?php 
    include("fonctions/viewHelper.php");
    headerHelper();
    
  ?>
  <br>
  <br><br>
  <h1>Stat : </h1><br><br>
  <div class="col-md-3" ><canvas id="myChart"></canvas></div>

    <?php footerHelper(); ?>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/Dashboard.js"></script>
</body>
</html>
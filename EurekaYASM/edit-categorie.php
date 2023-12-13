<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mezabi - Edit category</title>
    <link rel="stylesheet" href="/mezabi/static/css/mezabi.css">
</head>
<body>

<?php
const PREFIX_TO_RELATIVE_PATH = "/mezabi";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use yasmf\HttpHelper;
use yasmf\DataSource;

$code = HttpHelper::getParam('code_categorie');
$designation = HttpHelper::getParam('categorie');
$modeEdition = (bool)HttpHelper::getParam('edition');
$message = null;

if ($modeEdition) {
    try {
        $dataSource = new DataSource(
            $host = 'mezabi-1-db',
            $port = '3306', 
            $db = 'mezabi-1', 
            $user = 'mezabi-1', 
            $pass = 'mezabi-1', 
            $charset = 'utf8mb4'
        );

        $sql = "update a_categories set designation = ? where code_categorie = ?";
        $searchStmt = $dataSource->getPdo()->prepare($sql);
        $searchStmt->execute([$designation, $code]);
        $message = "Catégorie modifiée !";
    } catch(PDOException $exception) {
        throw new PDOException($exception->getMessage(), (int)$exception->getCode());
    }

}
?>

<h1>Mezabi</h1>

<a href="/mezabi">Catégories</a> > Edition catégorie

<h2>Catégorie <?php echo $code ?></h2>

<?php if ($message != null)  { ?>
<p style="color: darkgreen"><?php echo $message ?></p>
<?php } ?>

<form action="edit-categorie.php" method="post">
    <input type="hidden" name="edition" value="true">
    <input type="hidden" name="code_categorie" value="<?php echo $code ?>">
    <input name="categorie" type="text" placeholder="Désignation" value="<?php echo $designation ?>">
    <input type="submit" value="OK">
</form>

</body>
</html>

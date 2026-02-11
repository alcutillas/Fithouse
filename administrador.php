<?php
require_once("templates/header.php");
if($_SESSION["rol"] != "admin"){
    header("Location:index.php");
}
?>

<?php
require_once("templates/footer.php");
?>
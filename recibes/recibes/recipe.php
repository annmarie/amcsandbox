<?php
require_once("config.php");


$s = new Recipes();


echo "<pre>";

$recipes = $s->search('headline');
foreach ($recipes as $r) {

    $r->getIngredients();
    print_r($r);
}

echo "</pre>";


echo "\nhello";

?>



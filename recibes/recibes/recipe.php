<?php
require_once("config.php");

$nr = new Recipe();
$nr->healine = "ddd";
$nr->body = "xxx";
$nr->addIngredient("sugar");
$nr->addTag("sugar");
$nr->save();

echo "<pre>";

$s = new Recipes();
$recipes = $s->all();
foreach ($recipes as $r) {
    $r->getIngredients();
    print_r($r);
}

echo "</pre>";


echo "\nhello";

?>



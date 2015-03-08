<?php
require_once("config.php");

$db = new Database($conf);

$s = new Recipes();
$all = $s->all();
echo "<pre>";
print_r($all);
echo "</pre>";


#$nr = new Recipe(8);
#print_r($nr);

echo "\nhello";

?>



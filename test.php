<?php
require 'typography.php';

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

$text_fr = <<<EOT
voici un texte , avec.. un nombre impressionnnnnnant
de fautes!?!! c'est pas ma soeur qui dira le contraire:
ca marche "correctement".
huhu [test] 4 36
EOT;

$text_en = <<<EOT
this is , an example in   : english....
but i'm not a good "writer" !
huhu [test] 4 36
EOT;

echo " == Test en français :\n";
echo $text_fr;
echo "\n == Correction :\n";
echo typography($text_fr, "fr");
echo "\n == Test in english:\n";
echo $text_en;
echo "\n == Correction:\n";
echo typography($text_en, "en");
?>

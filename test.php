<?php
require 'typography.php';

$text = <<<EOT
voici un texte , avec.. un nombre impressionnant
de fautes!?!! c'est pas ma soeur qui dira le contraire:
ca marche "correctement"
EOT;

echo $text;
echo "\n***\n";
echo typography($text, "fr");
?>

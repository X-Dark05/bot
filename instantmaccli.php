<?php
include "curl.php";
function instantmac($reff, $firebase_token){
$curl = curl("https://x-dark.000webhostapp.com/instantmac.php?Reff=".$reff."&Token=".$firebase_token);
return $curl;
}
echo color("red","|===============================|\n");
echo color("red","|                               |\n");
echo color("red","|      Created By X-Dark        |\n");
echo "|    Auto Reff Instantmac v1    |\n";
echo "|                               |\n";
echo "|===============================|\n";
echo color("nevy","Reffmu: ");
$reff = trim(fgets(STDIN));
echo color("nevy","Token: ");
$firebase_token = trim(fgets(STDIN));
sleep(5);
echo instantmac($reff, $firebase_token);
?>

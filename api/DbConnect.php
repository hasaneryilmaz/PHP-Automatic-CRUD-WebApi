<?php


// Database Config
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "autocreate";

try
{
    $baglan = new PDO('mysql:host='.$host.';dbname='.$dbname, $user,$pass);
}catch (Exception $e)
{
    echo $e->getMessage();
}



// Config

$perpageitem = 10;


?>
<?php


try
{
    $baglan = new PDO('mysql:host=localhost;dbname=autocreate', 'root', '');
}catch (Exception $e)
{
    echo $e->getMessage();
}


?>
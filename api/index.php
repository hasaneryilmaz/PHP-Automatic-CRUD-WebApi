<?php

require_once("DbConnect.php");

$tab =  $baglan->query("SHOW TABLES");
$tab->execute();
$tablolar = $tab->fetchAll(PDO::FETCH_COLUMN);


$t = explode("/", @$_GET["tur"]);
$tur = @$t[0];
$method = @$t[1];
$param = @$t[1]."/".@$t[2];

function http($param)
{
    $code;
    switch ($param)
    {
        case "OK":
            $code =  200;
            break;
        case "NOT_FOUND":
            $code =  404;
            break;
        case "BAD_REQUEST":
            $code =  400;
            break;
        case "ADD_OK":
            $code =  201;
            break;

    }
    return http_response_code($code);
}

function jsoncikti($p){
    header('Content-Type: application/json');
    echo json_encode($p);
}


foreach($tablolar as $tablo)
{
    ${$tablo}["Get"] = function() use ($baglan,$tablo)
    {
        $sql = "SELECT * FROM ". $tablo;
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() >0 )
        {
            http("OK");
            return ["success" => true, "data" => $sorgu->fetchAll(PDO::FETCH_ASSOC) ];
        }else
        {
            http("BAD_REQUEST");
            return ["error"=>"There are no records in this table","succeed"=>false];
        }

    };


    ${$tablo}["GetBy"] = function($param) use ($baglan,$tablo)
    {
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $sql = "SELECT * FROM ". $tablo ." WHERE ".$where." = '".$what."'";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0)
        {
            http("OK");
            return ["success" => true, "data" => $sorgu->fetchAll(PDO::FETCH_ASSOC)];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"There is no such record","succeed"=>false];
        }
    };

    ${$tablo}["Delete"] = function($param)  use ($baglan,$tablo)
    {
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $sql = "DELETE FROM ". $tablo ." WHERE ".$where." = '".$what."'";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("OK");
            return ["succeed"=>true,"message"=>"Deletion successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"You tried to delete a record that does not exist","succeed"=>false];
        }
    };

    ${$tablo}["DeleteAll"] = function()  use ($baglan,$tablo)
    {
        $sql = "DELETE FROM ". $tablo;
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        http("OK");
        return ["succeed"=>true,"message"=>"All records in the table have been deleted successfully."];
    };

    ${$tablo}["Insert"] = function($param) use ($baglan,$tablo){
        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);
        $data = json_decode(file_get_contents("php://input"), true);
        $i=0;
        foreach ($data as $key => $value) {
            $newdata[$i] = $key." = '".$value."'";
            $i++;
        }
        $newdata = implode(" , ",$newdata);
        $sql = "INSERT INTO ". $tablo ." SET {$newdata}";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("ADD_OK");
            return ["succeed"=>true,"message"=>"Adding successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"A problem occurred while adding","succeed"=>false];
        }

    };

    ${$tablo}["Update"] = function($param) use ($baglan,$tablo){

        $param = str_replace(array("=","'","or","and",";","SHOW","DROP","UNION","SELECT","WHERE"),"",$param);

        $data = json_decode(file_get_contents("php://input"), true);
        $i=0;
        foreach ($data as $key => $value) {
            $newdata[$i] = $key." = '".$value."'";
            $i++;
        }
        $newdata = implode(" , ",$newdata);
        $p = explode("/",$param);
        $where = $p[0];
        $what = $p[1];
        $sql = "UPDATE ". $tablo ." SET {$newdata} WHERE ".$where." = '".$what."'";
        $sorgu = $baglan->prepare($sql);
        $sorgu->execute();
        if($sorgu->rowCount() > 0 )
        {
            http("OK");
            return ["succeed"=>true,"message"=>"Update process successful"];
        }else
        {
            http("NOT_FOUND");
            return ["error"=>"There was a problem during the update","succeed"=>false];
        }

    };

};

    foreach ($tablolar as $tablo) {
        switch ($tur) {
            case $tablo:

                $httprequest = $_SERVER['REQUEST_METHOD'];
                switch ($httprequest)
                {

                    case "GET":
                        if($param != "/")
                        {
                            jsoncikti(${$tablo}["GetBy"]($param));
                        }else
                        {
                            jsoncikti(${$tablo}["Get"]());
                        }
                    break;

                    case "DELETE":
                        $p = explode("/",$param);
                        if($p[0] == "delete")
                        {
                            jsoncikti(${$tablo}["DeleteAll"]());
                        }else
                        {
                            jsoncikti(${$tablo}["Delete"]($param));
                        }
                    break;

                    case "POST":
                        jsoncikti(${$tablo}["Insert"]($param));
                    break;

                    case "PUT":
                        jsoncikti(${$tablo}["Update"]($param));
                    break;

                }
            break;
        }
    }

    if($tur == ""){
        echo "Please choose a table for the api";
    }


